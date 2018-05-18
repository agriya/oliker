<?php
/**
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 *
 */
/**
 * GET Gateways
 * Summary: Get Gateways
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/post_gateways', function ($request, $response, $args) {

    $body = $request->getParsedBody();
    $result = array();
    $ZazPay_credential_for_live = '';
    $ZazPay_credential_for_test = '';
    if (!empty($body['test_mode_value'])) {
        $ZazPay_credential_for_test = $body['test_mode_value'];
        $uploaded_to_zazpay = $body['test_mode_value'];
    }
    if (!empty($body['live_mode_value'])) {
        $ZazPay_credential_for_live = $body['live_mode_value'];
        $uploaded_to_zazpay = $body['live_mode_value'];
    }
    $payment_gateway_settings = Models\PaymentGatewaySetting::where('name', 'payment_gateway_all_credentials')->update(array(
        "test_mode_value" => serialize($ZazPay_credential_for_test) ,
        "live_mode_value" => serialize($ZazPay_credential_for_live)
    ));
    foreach ($uploaded_to_zazpay as $gateway_id => $data) {
        $s = getZazPayObject();
        $s->callUpdateGatewayCredentials($gateway_id, $data);
    }
    $response = array(
        'error' => array(
            'code' => 0,
            'message' => 'Payment Gateways Updated',
            'fields' => ''
        )
    );
    return renderWithJson($response);
});
/**
 * GET Gateways
 * Summary: Get Gateways
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/get_gateways', function ($request, $response, $args) {

    $zazpay_payment_gateways = Models\ZazpayPaymentGateway::get()->toArray();
    $payment_gateway_settings = Models\PaymentGatewaySetting::where('name', 'payment_gateway_all_credentials')->first()->toArray();
    if (!empty($payment_gateway_settings)) {
        $test_mode_value = unserialize($payment_gateway_settings['test_mode_value']);
        $live_mode_value = unserialize($payment_gateway_settings['live_mode_value']);
    }
    if (!empty($zazpay_payment_gateways)) {
        foreach ($zazpay_payment_gateways as $zazpay_payment_gateway) {
            $gateway_id = $zazpay_payment_gateway['zazpay_gateway_id'];
            $zazpay_gateway_details = unserialize($zazpay_payment_gateway['zazpay_gateway_details']);
            $zazpay_live_mode_value = $zazpay_gateway_details;
            $zazpay_test_mode_value = $zazpay_gateway_details;
            if (!empty($zazpay_gateway_details)) {
                $zazpay_payment_gateway_form[$gateway_id]['name'] = $zazpay_gateway_details['name'];
                $zazpay_payment_gateway_form[$gateway_id]['id'] = $zazpay_gateway_details['id'];
                $zazpay_payment_gateway_form[$gateway_id]['display_name'] = $zazpay_gateway_details['display_name'];
            }
            if (!empty($zazpay_gateway_details['merchant_credential_fields'])) {
                if (!empty($payment_gateway_settings)) {
                    $merchant_credentials = $zazpay_gateway_details['merchant_credential_fields'];
                    $zazpay_live_mode_value = $merchant_credentials;
                    $zazpay_test_mode_value = $merchant_credentials;
                    foreach ($merchant_credentials as $field => $values) {
                        if ($live_mode_value[$gateway_id] != null) {
                            if (array_key_exists($field, $live_mode_value[$gateway_id])) {
                                $zazpay_live_mode_value[$field]['value'] = $live_mode_value[$gateway_id][$field];
                            }
                        }
                        if ($test_mode_value[$gateway_id] != null) {
                            if (array_key_exists($field, $test_mode_value[$gateway_id])) {
                                $zazpay_test_mode_value[$field]['value'] = $test_mode_value[$gateway_id][$field];
                            }
                        }
                    }
                } else {
                    $zazpay_test_mode_value = $zazpay_gateway_details['merchant_credential_fields'];
                    $zazpay_live_mode_value = $zazpay_gateway_details['merchant_credential_fields'];
                }
            }
            $zazpay_payment_gateway_form[$gateway_id]['test_mode_value'] = $zazpay_test_mode_value;
            $zazpay_payment_gateway_form[$gateway_id]['live_mode_value'] = $zazpay_live_mode_value;
        }
        $result['data'] = $zazpay_payment_gateway_form;
        return renderWithJson($result);
    } else {
        $response = array(
            'error' => array(
                'code' => 1,
                'message' => 'No Payment Gateways found',
                'fields' => ''
            )
        );
        return renderWithJson($response);
    }
});
