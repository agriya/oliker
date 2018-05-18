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
 * DELETE adPackagesAdPackageIdDelete
 * Summary: Delete ad package
 * Notes: Deletes a single ad package based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ad_packages/{adPackageId}', function ($request, $response, $args) {

    $adPackage = Models\AdPackage::find($request->getAttribute('adPackageId'));
    try {
        $adPackage->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad package could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdPackage'));
/**
 * GET adPackagesAdPackageIdGet
 * Summary: Fetch ad package
 * Notes: Returns a ad package based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_packages/{adPackageId}', function ($request, $response, $args) {

    $result = array();
    $adPackage = Models\AdPackage::with('category')->find($request->getAttribute('adPackageId'));
    if (!empty($adPackage)) {
        $result['data'] = $adPackage->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
})->add(new ACL('canViewAdPackage'));
/**
 * PUT adPackagesAdPackageIdPut
 * Summary: Update ad package by its id
 * Notes: Update ad package by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/ad_packages/{adPackageId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $adPackage = Models\AdPackage::find($request->getAttribute('adPackageId'));
    $adPackage->fill($args);
    $result = array();
    try {
        $validationErrorFields = $adPackage->validate($args);
        if (empty($validationErrorFields)) {
            $adPackage->save();
            $result = $adPackage->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad package could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad package could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateAdPackage'));
/**
 * GET adPackagesGet
 * Summary: Fetch all ad packages
 * Notes: Returns all ad packages from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_packages', function ($request, $response, $args) {

    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $adPackages = Models\AdPackage::leftJoin('categories', 'ad_packages.category_id', '=', 'categories.id');
        $adPackages = $adPackages->select('ad_packages.*', 'categories.name as category_name');
        if (!empty($authUser)) {
            $adPackages = $adPackages->with('user_ad_package');
        }
        $adPackages = $adPackages->with('category')->Filter($queryParams);
        $adPackages = $adPackages->paginate($count)->toArray();
        $data = $adPackages['data'];
        unset($adPackages['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $adPackages
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST adPackagesPost
 * Summary: Creates a new ad package
 * Notes: Creates a new ad package
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/ad_packages', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $adPackage = new Models\AdPackage($args);
    $result = array();
    try {
        $validationErrorFields = $adPackage->validate($args);
        if (empty($validationErrorFields)) {
            $adPackage->save();
            $result = $adPackage->toArray();
            $userDetails = Models\User::find($authUser['id'])->toArray();
            ;
            $emailFindReplace_user = array(
                '##USERNAME##' => $userDetails['username'],
                '##PACKAGE_NAME##' => $args['name'],
                '##VALIDITY_DAYS##' => $args['validity_days'],
                '##AMOUNT##' => $args['amount'],
                '##ADDITIONAL_ADS_ALLOWED##' => $args['additional_ads_allowed'],
                '##CREDIT_POINT##' => $args['credit_points'],
                '##POINTS_VALID_DAYS##' => $args['points_valid_days']
            );
            sendMail('purchasepackage', $emailFindReplace_user, $userDetails['email']);
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad package could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad package could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAdPackage'));
/**
 * GET userAdPackagesGet
 * Summary: Fetch all user ad packages
 * Notes: Returns all user ad packages from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_ad_packages', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    global $authUser;
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $userAdPackages = Models\UserAdPackage::with('user', 'ad_package', 'payment_gateway')->Filter($queryParams)->paginate($count)->toArray();
        $data = $userAdPackages['data'];
        unset($userAdPackages['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $userAdPackages
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListUserAdPackage'));
/**
 * POST userAdPackagesPost
 * Summary: Creates a new user ad package
 * Notes: Creates a new user ad package
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/user_ad_packages', function ($request, $response, $args) {

    global $authUser;
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $userAdPackage = new Models\UserAdPackage($args);
    $result = array();
    try {
        if (!empty($args['ad_package_id'])) {
            $user_ad_package = Models\UserAdPackage::where('ad_package_id', $args['ad_package_id'])->where('user_id', $authUser['id'])->whereDate('expiry_date', '>=', date('Y-m-d'))->first();
            if (!empty($user_ad_package)) {
                $validationErrorFields['unique'] = 'ad_package_id';
                return renderWithJson($result, 'User ad package could not be added. Please, try again.', $validationErrorFields, 1);
            }
            $ad_package = Models\AdPackage::find($args['ad_package_id']);
            if (!empty($ad_package)) {
                $validity_days = $ad_package->validity_days;
                $userAdPackage->amount = $ad_package->amount;
                $userAdPackage->allowed_ad_count = $ad_package->additional_ads_allowed;
                $userAdPackage->points = $ad_package->credit_points;
                $userAdPackage->expiry_date = date('Y-m-d', strtotime($validity_days . ' days'));
                $userAdPackage->user_id = $authUser['id'];
                $validationErrorFields = $userAdPackage->validate($args);
            } else {
                $validationErrorFields['invalid'] = 'ad_package_id';
            }
            if (empty($validationErrorFields)) {
                $userAdPackage->save();
                $payment = new Models\Payment;
                $user_ad_package_id = $userAdPackage->id;
                $args['user_id'] = $authUser['id'];
                $args['id'] = $userAdPackage->id;
                $args['amount'] = $userAdPackage->amount;
                $args['name'] = $ad_package['name'];
                $args['description'] = $ad_package['name'] . 'purchased for ads ';
                $args['notify_url'] = $_server_domain_url . '/ipn/process_ipn/UserAdPackage/' . $userAdPackage->id . '/' . md5(SECURITY_SALT . $userAdPackage->id . SITE_NAME);
                $args['success_url'] = $_server_domain_url . '/#!/user_ad_packages?error_code=0';
                $args['cancel_url'] = $_server_domain_url . '/#!/user_ad_packages?error_code=512';
                $response = $payment->processPayment($user_ad_package_id, $args, 'UserAdPackage');
                /*$adminId = Models\User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
                insertTransaction($authUser['id'], $adminId['id'], $userAdPackage->id, 'UserAdPackage', \Constants\TransactionType::AmountAddedToUserAdPackage, $args['payment_gateway_id'], $args['amount'], $args['amount'], 0);*/
                $result = $response;
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'User ad package could not be added. Please, try again.', $validationErrorFields, 1);
            }
        } else {
            return renderWithJson($result, 'Please choose one package.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'User ad package could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateUserAdPackage'));
