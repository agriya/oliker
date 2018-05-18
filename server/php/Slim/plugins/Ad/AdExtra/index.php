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
 * GET adExtrasAdExtraIdGet
 * Summary: Fetch ad extra
 * Notes: Returns a ad extra based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_extras/{adExtraId}', function ($request, $response, $args) {

    $result = array();
    $adExtra = Models\AdExtra::find($request->getAttribute('adExtraId'));
    if (!empty($adExtra)) {
        $result['data'] = $adExtra->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
})->add(new ACL('canViewAdExtra'));
/**
 * PUT adExtrasAdExtraIdPut
 * Summary: Update ad extra by its id
 * Notes: Update ad extra by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/ad_extras/{adExtraId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $adExtra = Models\AdExtra::find($request->getAttribute('adExtraId'));
    $adExtra->fill($args);
    $result = array();
    try {
        $validationErrorFields = $adExtra->validate($args);
        if (empty($validationErrorFields)) {
            $adExtra->save();
            $result = $adExtra->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad extra could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad extra could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateAdExtra'));
/**
 * GET adExtrasGet
 * Summary: Fetch all ad extras
 * Notes: Returns all ad extras from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_extras', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $adExtras = Models\AdExtra::Filter($queryParams)->paginate($count)->toArray();
        $data = $adExtras['data'];
        unset($adExtras['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $adExtras
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * DELETE adExtraDaysAdExtraDayIdDelete
 * Summary: Delete ad extra day
 * Notes: Deletes a single ad extra day based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ad_extra_days/{adExtraDayId}', function ($request, $response, $args) {

    $adExtraDay = Models\AdExtraDay::find($request->getAttribute('adExtraDayId'));
    try {
        $adExtraDay->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad extra day could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdExtraDay'));
/**
 * GET adExtraDaysAdExtraDayIdGet
 * Summary: Fetch ad extra day
 * Notes: Returns a ad extra day based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_extra_days/{adExtraDayId}', function ($request, $response, $args) {

    $result = array();
    $adExtraDay = Models\AdExtraDay::with('ad_extra', 'category')->find($request->getAttribute('adExtraDayId'));
    if (!empty($adExtraDay)) {
        $result['data'] = $adExtraDay->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
})->add(new ACL('canViewAdExtraDay'));
/**
 * PUT adExtraDaysAdExtraDayIdPut
 * Summary: Update ad extra day by its id
 * Notes: Update ad extra day by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/ad_extra_days/{adExtraDayId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $adExtraDay = Models\AdExtraDay::find($request->getAttribute('adExtraDayId'));
    $adExtraDay->fill($args);
    $result = array();
    try {
        $validationErrorFields = $adExtraDay->validate($args);
        if (empty($validationErrorFields)) {
            $adExtraDay->save();
            $result = $adExtraDay->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad extra day could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad extra day could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateAdExtraDay'));
/**
 * GET adExtraDaysGet
 * Summary: Fetch all ad extra days
 * Notes: Returns all ad extra days from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_extra_days', function ($request, $response, $args) {

    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $adExtraDays = Models\AdExtraDay::leftJoin('categories', 'ad_extra_days.category_id', '=', 'categories.id');
        $adExtraDays = $adExtraDays->leftJoin('ad_extras', 'ad_extra_days.ad_extra_id', '=', 'ad_extras.id');
        $adExtraDays = $adExtraDays->select('ad_extra_days.*', 'categories.name as category_name', 'ad_extras.name AS ad_extra_name');
        if (empty($authUser) || (!empty($authUser) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin)) {
            $adExtraDays = $adExtraDays->where('ad_extra_days.is_active', 1);
        }
        $adExtraDays = $adExtraDays->with('ad_extra', 'category')->Filter($queryParams)->paginate($count)->toArray();
        $data = $adExtraDays['data'];
        unset($adExtraDays['data']);
        if(!empty($queryParams['category_id'])) {
            $ad_count = Models\Ad::where('user_id', $authUser->id)->where('category_id', $queryParams['category_id'])->count();
            $adExtraDays['user_ad_count'] = $ad_count;         
        }
        $results = array(
            'data' => $data,
            '_metadata' => $adExtraDays
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST adExtraDaysPost
 * Summary: Creates a new ad extra day
 * Notes: Creates a new ad extra day
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/ad_extra_days', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $adExtraDay = new Models\AdExtraDay($args);
    $result = array();
    try {
        $validationErrorFields = $adExtraDay->validate($args);
        if (empty($validationErrorFields)) {
            $adExtraDay->save();
            $result = $adExtraDay->toArray();
            $userDetails = Models\User::find($authUser['id'])->toArray();
            $adExtra = Models\AdExtra::find($args['ad_extra_id'])->toArray();
            $category = Models\Category::find($adExtraDay->category_id)->toArray();
            $emailFindReplace_user = array(
                '##USERNAME##' => $userDetails['username'],
                '##AD_EXTRA_NAME##' => $adExtra['name'],
                '##CATEGORY_NAME##' => $category['name'],
                '##DAYS##' => $args['days'],
                '##AMOUNT##' => $args['amount']
            );
            sendMail('purchaseextras', $emailFindReplace_user, $userDetails['email']);
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad extra day could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad extra day could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAdExtraDay'));
/**
 * GET userAdExtrasGet
 * Summary: Fetch all user ad extras
 * Notes: Returns all user ad extras from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_ad_extras', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $userAdExtras = Models\UserAdExtra::with('ad_extra', 'ad', 'ad_extra_day', 'payment_gateway', 'user')->Filter($queryParams)->paginate($count)->toArray();
        $data = $userAdExtras['data'];
        unset($userAdExtras['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $userAdExtras
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListUserAdExtra'));
/**
 * POST userAdExtrasPost
 * Summary: Creates a new user ad extra
 * Notes: Creates a new user ad extra
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/user_ad_extras', function ($request, $response, $args) {

    global $authUser;
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $userAdExtra = new Models\UserAdExtra($args);
    $result = array();
    $user = Models\User::find($authUser['id']);
    try {
        $ad_extra_day_id = $userAdExtra->ad_extra_day_id;
        if (!empty($ad_extra_day_id)) {
            $ad_extra_days = Models\AdExtraDay::find($ad_extra_day_id);
            $ad = Models\Ad::find($userAdExtra->ad_id);
            if (!empty($ad_extra_days) && !empty($ad)) {
                $validity_days = $ad_extra_days->days;
                // Ad extras points process
                $userAdExtra->amount = $ad_extra_days->amount;
                $points = $userAdExtra->amount / AMOUNT_PER_POINT;
                if ($points > $user->available_points) {
                    $points_deduct = $points - $user->available_points;
                    $user->available_points = 0;
                    $user->update();
                    $userAdExtra->is_payment_completed = 0;
                    $userAdExtra->payment_gateway_id = $args['payment_gateway_id'];
                    $userAdExtra->user_id = $authUser['id'];
                    $userAdExtra->save();
                    $data = $args;
                    $amount = $points_deduct * AMOUNT_PER_POINT;
                    $data['amount'] = $amount;
                    $data['user_id'] = $authUser['id'];
                    $data['ad_id'] = $userAdExtra->ad_id;
                    $data['ad_extra_payment'] = true;
                    $data['ad_extra_ids'] = (array)$args['ad_extra_id'];
                    $data['ad_payment'] = false;
                    $data['name'] = $ad->title;
                    $data['description'] = $ad->description;
                    $payment = new Models\Payment;
                    $data['notify_url'] = $_server_domain_url . '/ipn/process_ipn/UserAdExtra/' . $userAdExtra->id . '/' . md5(SECURITY_SALT . $userAdExtra->id . SITE_NAME);
                    $data['success_url'] = $_server_domain_url . '/#!/user_ad_extra?error_code=0';
                    $data['cancel_url'] = $_server_domain_url . '/#!/user_ad_extra?error_code=512';
                    $payment_response = $payment->processPayment($userAdExtra->id, $data, 'UserAdExtra');
                    /*$adminId = Models\User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
                    insertTransaction($authUser['id'], $adminId['id'], $userAdExtra->id, 'UserAdExtra', \Constants\TransactionType::AmountAddedToUserAdExtra, $args['payment_gateway_id'], $data['amount'], $data['amount'], 0);*/
                    $result = $userAdExtra->toArray();
                    return renderWithJson($payment_response, 'User ad Extras added successfully.', '', 0);
                } else {
                    $user->available_points = ($user->available_points - $points);
                    $user->update();
                    $userAdExtra->is_payment_completed = 1;
                    $userAdExtra->payment_gateway_id = \Constants\PaymentGateways::Credits;
                    $userAdExtra->user_id = $authUser['id'];
                    $userAdExtra->save();
                    $ad_extra_id = $userAdExtra->ad_extra_id;
                    $ad_extra_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                    $diff_days = (strtotime($ad_extra_end_date) - strtotime($ad->ad_end_date)) / 24 / 3600;
                    if ($diff_days > 1) {
                        $ad->ad_end_date = $ad_extra_end_date;
                    }
                    if ($ad_extra_id == \Constants\ConstAdExtra::TopAd) {
                        $ad->is_show_as_top_ads = 1;
                        $ad->top_ads_end_date = $ad_extra_end_date;
                    } elseif ($ad_extra_id == \Constants\ConstAdExtra::Highlight) {
                        $ad->is_highlighted = 1;
                        $ad->highlighted_end_date = $ad_extra_end_date;
                    } elseif ($ad_extra_id == \Constants\ConstAdExtra::Urgent) {
                        $ad->is_urgent = 1;
                        $ad->urgent_end_date = $ad_extra_end_date;
                    } elseif ($ad_extra_id == \Constants\ConstAdExtra::InTop) {
                        $ad->is_show_ad_in_top = 1;
                        $ad->ad_in_top_end_date = $ad_extra_end_date;
                    }
                    $ad->update();
                    $result = $userAdExtra->toArray();
                    return renderWithJson($result, 'User ad Extras added successfully.', '', 0);
                }
            } else {
                return renderWithJson($result, 'ad or ad_extra are not present. Please, try again.', '', 1);
            }
        } else {
            return renderWithJson($result, 'ad or ad_extra are not present. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'User ad extra could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateUserAdExtra'));
