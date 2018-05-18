<?php
/**
 * Process IPN
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2017 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
require_once __DIR__ . '/../../config.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../lib/vendors/Zazpay/zazpay.php';
require_once '../lib/database.php';
require_once '../lib/core.php';
require_once '../lib/constants.php';
require_once '../lib/settings.php';
$request_uri = $_SERVER['REQUEST_URI'];
if (strpos($request_uri, "payments/") !== false || strpos($request_uri, "receiver_accounts/") !== false || strpos($request_uri, "paypal_subscribe/") !== false) {
    $s = getZazPayObject();
    $print_raw_response = !empty($_GET['print_raw_response']) ? true : false;
    $response = $s->callIndirectRegisteredWebsiteHits($request_uri, $_GET, $_POST, $print_raw_response);
    if (!empty($response['success_url'])) {
        header('location: ' . $response['success_url']);
        exit;
    }
    if (!empty($response['cancel_url'])) {
        header('location: ' . $response['cancel_url']);
        exit;
    }
} else {
    $ipn_data = $_POST;
    $ipn_data['post_variable'] = serialize($_POST);
    $ipn_data['ip_id'] = (!empty(saveIp())) ? saveIp() : null;
    $payment = new Models\Payment;
    $payment->_saveIPNLog($ipn_data['post_variable']);
    if ($_GET['hash'] == md5(SECURITY_SALT . $_GET['id'] . SITE_NAME)) {
        $zazPaymentSettings = Models\PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::ZazPay)->get();
        foreach ($zazPaymentSettings as $value) {
            $zazpay[$value->name] = $value->test_mode_value;
        }
        $modelName = 'Models' . '\\' . $_GET['model'];
        $modelResponse = $modelName::where('id', $_GET['id'])->with('user')->first();
        if (!empty($modelResponse)) {
            if (!empty($ipn_data['status']) && $ipn_data['status'] == 'Captured' && $ipn_data['error_code'] == 0) {
                $post['amount'] = $ipn_data['amount'];
                $post['paykey'] = $ipn_data['paykey'];
                $post['merchant_id'] = $zazpay['zazpay_merchant_id'];
                $post['payment_id'] = $ipn_data['id'];
                $post['gateway_id'] = $ipn_data['gateway_id'];
                ;
                $post['status'] = 'Captured';
                $post['payment_type'] = 'Capture';
                $post['buyer_id'] = $modelResponse['user_id'];
                $post['buyer_email'] = $modelResponse['user']['email'];
                $response = $modelName::processCaptured($ipn_data, $_GET['id']);
                $payment->_savePaidLog($modelResponse['id'], $post, $_GET['model'], \Constants\PaymentGateways::ZazPay);
                //$payment->addTransactions($contest, 'Contest');
                
            } elseif (!empty($ipn_data['status']) && $ipn_data['status'] == 'Pending' && $ipn_data['error_code'] != 0) {
                $response = $modelName::processInitiated($ipn_data);
            } else {
                $response = $modelName::processPending($ipn_data);
            }
        }
    }
}
