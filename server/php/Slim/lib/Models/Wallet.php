<?php
/**
 * Wallet
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * Wallet
*/
class Wallet extends AppModel
{
    protected $table = 'wallets';
    protected $fillable = array(
        'amount',
        'payment_gateway_id',
        'gateway_id',
        'buyer_name',
        'buyer_email',
        'buyer_address',
        'buyer_city',
        'buyer_state',
        'buyer_country_iso2',
        'buyer_phone',
        'buyer_zipcode',
        'credit_card_code',
        'credit_card_expire',
        'credit_card_name_on_card',
        'credit_card_number'
    );
    public $rules = array(
        'amount' => 'sometimes|required',
        'paymentGatewayId' => 'sometimes|required',
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function foreign_transactions()
    {
        return $this->morphMany('Models\Transaction', 'foreign_transaction');
    }
    public function processCaptured($payment_response, $id)
    {
        $wallet = Wallet::where('id', $id)->where('is_payment_completed', false)->first();
        if (!empty($wallet)) {
            $wallet->is_payment_completed = 1;
            $wallet->update();
            //Transaction process
            $adminId = User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
            Transaction::insertTransaction($wallet->user_id, $adminId['id'], $wallet->id, 'Wallet', $payment_response['payment_gateway_id'],$wallet->amount,'AmountAddedToWallet');           
            $user = User::find($wallet->user_id);
            $user->available_wallet_amount = $user->available_wallet_amount + $wallet->amount;
            $user->update();
        }
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment successfully completed'
            )
        );
        return $response;
    }
    public function processInitiated($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment initiated',
                'fields' => ''
            )
        );
        return $response;
    }
    public function processPending($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment is in pending state.'
            )
        );
        return $response;
    }
}
