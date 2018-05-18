<?php
/**
 * Payment
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

class Payment extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = '';
    public function processPayment($id, $body, $type = 'UserAdPackage')
    {
        $modelName = 'Models' . '\\' . $type;
        global $_server_domain_url;
        $payment_response = array();
        if ($body['payment_gateway_id'] == \Constants\PaymentGateways::ZazPay) {
            $zaz_payment_settings = PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::ZazPay)->get();
            foreach ($zaz_payment_settings as $value) {
                $zazpay[$value->name] = $value->test_mode_value;
            }
            $s = new \ZazPay_API(array(
                'api_key' => $zazpay['zazpay_api_key'],
                'merchant_id' => $zazpay['zazpay_merchant_id'],
                'website_id' => $zazpay['zazpay_website_id'],
                'secret_string' => $zazpay['zazpay_secret_string'],
                'is_test' => true,
                'cache_path' => ''
            ));
            $post['gateway_id'] = $body['gateway_id'];
            $post['website_id'] = $zazpay['zazpay_website_id'];
            $post['currency_code'] = 'USD';
            $post['notify_url'] = $body['notify_url'];
            $post['success_url'] = $body['success_url'];
            $post['cancel_url'] = $body['cancel_url'];
            $post['amount'] = $body['amount'];
            $post['item_name'] = $body['name'];
            $post['item_description'] = substr($body['description'], 0, 50);
            $post['buyer_email'] = $body['buyer_email'];
            $post['buyer_phone'] = $body['buyer_phone'];
            if (empty($body['zip_code'])) {
                $post['buyer_zip_code'] = $body['buyer_zipcode'];
                $post['buyer_address'] = $body['buyer_address'];
                $post['buyer_city'] = $body['buyer_city'];
                $post['buyer_state'] = $body['buyer_state'];
                $post['buyer_country'] = $body['buyer_country_iso2'];
            } else {
                $post['buyer_zip_code'] = $body['zip_code'];
                $post['buyer_address'] = $body['address'];
                $post['buyer_city'] = $body['city'];
                $post['buyer_state'] = $body['state'];
                $post['buyer_country'] = $body['country'];
            }
            if (!empty($body['credit_card_number'])) {
                $post['credit_card_number'] = $body['credit_card_number'];
                $post['credit_card_expire'] = $body['credit_card_expire'];
                $post['credit_card_name_on_card'] = $body['credit_card_name_on_card'];
                $post['credit_card_code'] = $body['credit_card_code'];
            } elseif (!empty($body['payment_note'])) {
                $post['payment_note'] = $body['payment_note'];
            }
            $payment_response = $s->callCapture($post);
            if (!isset($payment_response['payment_gateway_id'])) {
                $payment_response['payment_gateway_id'] = $body['payment_gateway_id'];
            }
            if (!empty($body['ad_extra_payment'])) {
                $payment_response['ad_extra_payment'] = $body['ad_extra_payment'];
            }
            if (!empty($body['ad_extra_ids'])) {
                $payment_response['ad_extra_ids'] = $body['ad_extra_ids'];
            }
            $data_response = $modelName::with('user')->find($id);
            if (!empty($payment_response['status']) && $payment_response['status'] == 'Captured' && $payment_response['error']['code'] == 0) { //Direct payment
                $post['paykey'] = $payment_response['paykey'];
                $post['status'] = 'Captured';
                $post['payment_type'] = 'Capture';
                $post['amount'] = $body['amount'];
                $post['merchant_id'] = $zazpay['zazpay_merchant_id'];
                $post['payment_id'] = $payment_response['id'];
                $post['buyer_id'] = $body['user_id'];
                $payment_response['amount'] = $body['amount'];
                $modelName::processCaptured($payment_response, $id);
                Payment::_savePaidLog($id, $post, $type, $body['payment_gateway_id']);
                $payment_response = array(
                    'data' => $data_response,
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 0,
                        'message' => 'order successfully completed'
                    )
                );
            } elseif (!empty($payment_response['status']) && $payment_response['status'] == 'Initiated' && $payment_response['error']['code'] <= 0) { //Offline payment
                $modelName::processInitiated($payment_response);
                if (!empty($payment_response['gateway_callback_url'])) {
                    $payment_response = array(
                        'data' => $data_response,
                        'redirect_url' => $payment_response['gateway_callback_url'],
                        'payment_response' => $payment_response,
                        'error' => array(
                            'code' => 0,
                            'message' => 'redirect to payment url',
                            'fields' => ''
                        )
                    );
                } else {
                    $payment_response = array(
                        'data' => $data_response,
                        'payment_response' => $payment_response,
                        'error' => array(
                            'code' => 0,
                            'message' => 'Initiated Payment without error code',
                            'fields' => ''
                        )
                    );
                }
            } elseif (!empty($payment_response['status']) && $payment_response['status'] == 'Pending' && $payment_response['error']['code'] == '-8') {
                $modelName::processPending($payment_response);
                $payment_response = array(
                    'data' => $data_response,
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 0,
                        'message' => 'You order has been completed and payment is progress.'
                    )
                );
            } else {
                $payment_response = array(
                    'data' => '',
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 1,
                        'message' => 'Payment could not be completed.Please try again...',
                        'fields' => ''
                    )
                );
            }
        } elseif ($body['payment_gateway_id'] == \Constants\PaymentGateways::Wallet) {
            $user = User::find($body['user_id']);
            $available_wallet_amount = $user['available_wallet_amount'];
            if ($available_wallet_amount >= $body['amount']) {
                $post = array();
                $post['amount'] = $body['amount'];
                $payment_response = array(
                    'status' => 'Captured',
                    'amount' => $body['amount']
                );
                if (!isset($payment_response['payment_gateway_id'])) {
                    $payment_response['payment_gateway_id'] = $body['payment_gateway_id'];
                }
                if (!empty($body['ad_extra_payment'])) {
                    $payment_response['ad_extra_payment'] = $body['ad_extra_payment'];
                }
                if (!empty($body['ad_extra_ids'])) {
                    $payment_response['ad_extra_ids'] = $body['ad_extra_ids'];
                }
                $this->_savePaidLog($id, $post, $type, $body['payment_gateway_id']);
                $modelName::processCaptured($payment_response, $id);
                Payment::updateUserWalletAmount($body['user_id'], $body['amount']);
                $data_response = $modelName::with('user')->find($id);
                $payment_response = array(
                    'data' => $data_response,
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 0,
                        'message' => 'Order successfully completed'
                    )
                );
            } else {
                $payment_response = array(
                    'data' => '',
                    'error' => array(
                        'code' => 1,
                        'message' => 'Insufficient balance. Please add amount to wallet.',
                        'fields' => ''
                    )
                );
            }
        } elseif ($body['payment_gateway_id'] == \Constants\PaymentGateways::Paypal) {
            //require_once APP_PATH . DIRECTORY_SEPARATOR . 'server' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Slim/plugins' . DIRECTORY_SEPARATOR . 'Common' .DIRECTORY_SEPARATOR . 'PaypalREST' . DIRECTORY_SEPARATOR . 'functions.php';
            $paymentGatewaySettings = PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::Paypal)->get();
            foreach ($paymentGatewaySettings as $value) {
                $paypal[$value->name] = $value->test_mode_value;
            }
            $apiContext = getApiContext();
            $body['success_url'] = $_server_domain_url. '/api/v1/paypal/process_payment?id=' . $id. '&model=' . $type;
            $payment = createPayment($id, $body);   
            $data_response = $modelName::find($id);
            //print_r($payment);die;            
            if (!empty($payment) && empty($payment->message)) {
                if ($payment->getState() == 'created') {
                    $payment->status = 'Initiated';
                    $data_response->paypal_pay_key = $payment->getId();
                    $data_response->update();
                    if (!empty($payment->getApprovalLink())) {
                        $payment_response = array(
                            'data' => $data_response,
                            'redirect_url' => $payment->getApprovalLink(),
                            'payment_response' => $payment->toArray(),
                            'error' => array(
                                'code' => 0,
                                'message' => 'redirect to payment url',
                                'fields' => ''
                            )
                        );
                    } else {
                        $payment_response = array(
                            'data' => $data_response,
                            'payment_response' => $payment->toArray(),
                            'error' => array(
                                'code' => 0,
                                'message' => 'Initiated Payment without error code',
                                'fields' => ''
                            )
                        );
                    }
                } else if ($payment->getState() == 'approved') {
                    $transactions = $payment->getTransactions();
                    $relatedResources = $transactions[0]->getRelatedResources();
                    $sale = $relatedResources[0]->getSale();                   
                    $response = array(
                        'status' => 'Captured',
                        'paykey' => $payment->getId(),
                        'payment_gateway_id' => $body['payment_gateway_id']
                    );
                    $payment->status = 'Captured';
                    $modelName::processCaptured($response, $id);
                    $data_response = $modelName::find($id);                    
                    $payment_response = array(
                        'data' => $data_response,
                        'payment_response' => $payment->toArray(),
                        'error' => array(
                            'code' => 0,
                            'message' => 'order successfully completed'
                        )
                    );
                    
                } else {
                    $payment_response = array(
                        'data' => '',
                        'payment_response' => $payment,
                        'error' => array(
                            'code' => 1,
                            'message' => 'Payment could not be completed.Please try again...',
                            'fields' => ''
                        )
                    );
                }
            } else {
                $payment_response = array(
                        'data' => $payment->data,
                        'payment_response' => $payment->message,
                        'error' => array(
                            'code' => 1,
                            'message' => 'Payment could not be completed.Please try again...',
                            'fields' => ''
                        )
                    );
            }
        } else {
            $payment_response = array(
                'data' => '',
                'payment_response' => $payment_response,
                'error' => array(
                    'code' => 1,
                    'message' => 'Payment could not be completed.Please try again...',
                    'fields' => ''
                )
            );
        }
        return $payment_response;
    }
    public function _saveIPNLog($post_variable)
    {
        $zazpayIpnLog = new ZazpayIpnLog;
        $zazpayIpnLog->post_variable = $post_variable;
        $zazpayIpnLog->ip = saveIp();
        $zazpayIpnLog->save();
    }
    public function _savePaidLog($foreign_id, $paymentDetails, $class = '', $payment_gateway_id)
    {
        if ($payment_gateway_id == \Constants\PaymentGateways::ZazPay) {
            $ZazpayTransactionLog = new ZazpayTransactionLog;
            $ZazpayTransactionLog->foreign_id = $foreign_id;
            $ZazpayTransactionLog->class = $class;
            $ZazpayTransactionLog->amount = !empty($paymentDetails['amount']) ? $paymentDetails['amount'] : '';
            $ZazpayTransactionLog->zazpay_pay_key = !empty($paymentDetails['paykey']) ? $paymentDetails['paykey'] : '';
            $ZazpayTransactionLog->merchant_id = !empty($paymentDetails['merchant_id']) ? $paymentDetails['merchant_id'] : '';
            $ZazpayTransactionLog->gateway_id = !empty($paymentDetails['gateway_id']) ? $paymentDetails['gateway_id'] : '';
            $ZazpayTransactionLog->status = !empty($paymentDetails['status']) ? $paymentDetails['status'] : '';
            $ZazpayTransactionLog->payment_type = !empty($paymentDetails['payment_type']) ? $paymentDetails['payment_type'] : '';
            $ZazpayTransactionLog->buyer_id = !empty($paymentDetails['buyer_id']) ? $paymentDetails['buyer_id'] : '';
            $ZazpayTransactionLog->buyer_email = !empty($paymentDetails['buyer_email']) ? $paymentDetails['buyer_email'] : '';
            $ZazpayTransactionLog->buyer_address = !empty($paymentDetails['buyer_address']) ? $paymentDetails['buyer_address'] : '';
            $ZazpayTransactionLog->save();
        } elseif ($payment_gateway_id == \Constants\PaymentGateways::Wallet) {
            $walletTransactionLog = new WalletTransactionLog;
            $walletTransactionLog->foreign_id = $foreign_id;
            $walletTransactionLog->class = $class;
            $walletTransactionLog->amount = !empty($paymentDetails['amount']) ? $paymentDetails['amount'] : '0.00';
            $walletTransactionLog->status = 'Captured';
            $walletTransactionLog->payment_type = 'Capture';
            $walletTransactionLog->save();
        }
    }
    public function addTransactions($order, $type)
    {
        if ($type == 'AdPackage') {
            $transaction = new Transaction;
            $transaction->user_id = $order['user_id'];
            $transaction->amount = $order['amount'];
            $transaction->foreign_id = $order['id'];
            $transaction->class = \Constants\TransactionKeys::AdPackage;
            $transaction->other_user_id = 0;
            $transaction->transaction_type = \Constants\ConstTransactionTypes::AddedPackage;
            $transaction->payment_gateway_id = !empty($order['payment_gateway_id']) ? $order['payment_gateway_id'] : '';
            $transaction->gateway_fees = !empty($order['payment_gateway']['gateway_fees']) ? $order['payment_gateway']['gateway_fees'] : '0.00';
            $transaction->save();
        }
        if ($type == 'Wallet') {
            $transaction = new Transaction;
            $transaction->user_id = $order['user_id'];
            $transaction->amount = $order['amount'];
            $transaction->foreign_id = $order['user_id'];
            $transaction->other_user_id = 0;
            $transaction->class = \Constants\TransactionKeys::Wallet;
            $transaction->transaction_type = \Constants\ConstTransactionTypes::AddedToWallet;
            $transaction->payment_gateway_id = !empty($order['payment_gateway_id']) ? $order['payment_gateway_id'] : '';
            $transaction->gateway_fees = !empty($order['payment_gateway']['gateway_fees']) ? $order['payment_gateway']['gateway_fees'] : '0.00';
            $transaction->save();
        }
        return true;
    }
    public function updateUserWalletAmount($user_id, $amount)
    {
        $user = User::where('id', $user_id)->first();
        $user->available_wallet_amount = $user->available_wallet_amount - $amount;
        $user->save();
    }
}
