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
 * DELETE adsAdIdDelete
 * Summary: Delete ad
 * Notes: Deletes a single ad based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ads/{adId}', function ($request, $response, $args) {

    global $authUser;
    $result = array();
    $ad = Models\Ad::find($request->getAttribute('adId'));
    try {
        if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $ad->user_id)) {
            $ad->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Invalid user. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAd'));
/**
 * GET adsAdIdGet
 * Summary: Fetch ad
 * Notes: Returns a ad based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ads/{adId}', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    global $authUser;
    $result = array();
    if (!empty($queryParams['type'])) {
        $adView = new Models\AdView();
        $adView->ad_id = $request->getAttribute('adId');
        if (!empty($authUser['id'])) {
            $adView->user_id = $authUser['id'];
        } else {
            $adView->user_id = null;
        }
        $adView->ip_id = saveIp();
        $adView->save();
        Models\Ad::find($request->getAttribute('adId'))->increment('ad_view_count', 1);
    }
        $ad = Models\Ad::with('attachment', 'ad_view','ad_owner', 'category','ad_report', 'advertiser_type', 'ad_form_field', 'city', 'state', 'country')->with(array(
            'ad_favorite' => function ($q) use ($authUser) {
                if (!empty($authUser->id)) {
                    $q->where('user_id', $authUser->id);
                }
            }
        ))->find($request->getAttribute('adId'));    
        if (!empty($queryParams['show_number']) || ($authUser->role_id == \Constants\ConstUserTypes::Admin) || $authUser->id == $ad->user_id) {
        $ad->makeVisible('phone_number');
    }
    if (!empty($ad)) {
        $result['data'] = $ad->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
});
/**
 * PUT adsAdIdPut
 * Summary: Update ad by its id
 * Notes: Update ad by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/ads/{adId}', function ($request, $response, $args) {

    global $authUser;
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $ad = Models\Ad::find($request->getAttribute('adId'));
    if (empty($ad)) {
        return renderWithJson($result, 'Invalid ad details.', '', 1);
    }
    $old_category = $ad->category_id;
    $ad_price = $ad->price;
    $ad->fill($args);
    if (!empty($args['title'])) {
        $ad->slug = Inflector::slug(strtolower($args['title']), '-');
    }
    $category = Models\Category::select('allowed_free_ads_count', 'post_ad_fee')->find($ad->category_id);
    $checkPayment = Models\Category::checkPayment($authUser['id'], $ad->category_id, $category);
    //get country, state and city ids
    $ad->country_id = !empty($args['country_iso2']) ? findCountryIdFromIso2($args['country_iso2']) : (!empty($ad->country_id) ? $ad->country_id : 0);
    $ad->state_id = !empty($args['state_name']) ? findOrSaveAndGetStateId($args['state_name'], $ad->country_id) : (!empty($ad->state_id) ? $ad->state_id : 0);
    $ad->city_id = !empty($args['city_name']) ? findOrSaveAndGetCityId($args['city_name'], $ad->country_id, $ad->state_id) : (!empty($ad->city_id) ? $ad->city_id : 0);
    unset($ad->city_name);
    unset($ad->state_name);
    unset($ad->country_iso2);
    unset($ad->ad_extra_id);
    $this->geohash = new Geohash();
    if (!empty($args['latitude'])) {
        $ad->hash = $this->geohash->encode(round($args['latitude'], 6), round($args['longitude'], 6));
    }
    $result = array();
    $payment_response = array();
    try {
        $validationErrorFields = $ad->validate($args);
        if (empty($validationErrorFields)) {
            if (!empty($args['price']) && $ad_price > $args['price']) {
                $ad->is_price_reduced = 1;
            }
            $ad->save();
            if (!empty($args['ad_form_field']) && $ad->id) {
                if ($old_category != $args['category_id']) {
                    Models\AdFormField::where('ad_id', $ad->id)->delete();
                }
                foreach ($args['ad_form_field'] as $adFormField) {
                    foreach ($adFormField as $fieldName => $value) {
                        $formField = Models\FormField::where('name', $fieldName)->where('category_id', $ad->category_id)->select('id')->first();
                        $adFormField = Models\AdFormField::where('ad_id', $ad->id)->where('form_field_id', $formField->id)->select('id')->first();
                        // check already exists in ad form field and update response
                        if (!empty($adFormField)) {
                            $adFormField = Models\AdFormField::where('id', $adFormField->id)->update(array(
                                'response' => $value
                            ));
                        } else { // else add new data in table
                            $adFormField = new Models\AdFormField;
                            $adFormField->ad_id = $ad->id;
                            $adFormField->response = $value;
                            $adFormField->form_field_id = $formField->id;
                            $adFormField->save();
                        }
                    }
                }
            }
            if (!empty($args['image'])) {
                if (is_array($args['image'])) {
                    $is_multi = 1;
                    foreach ($args['image'] as $image) {
                        if ((!empty($image)) && (file_exists(APP_PATH . '/media/tmp/' . $image))) {
                            saveImage('Ad', $image, $ad->id, $is_multi);
                        }
                    }
                } elseif ((!empty($args['image'])) && (file_exists(APP_PATH . '/media/tmp/' . $args['image']))) {
                    saveImage('Ad', $args['image'], $ad->id);
                }
            }
            // Payment process
            $user = Models\User::find($ad->user_id);
            $ad_price = 0.00;
            $ad_extra_amount = $checkPayment['amount'];
            $payment_response = array();
            if (!empty($args['ad_extra_id'])) {
                foreach ($args['ad_extra_id'] as $ad_extra_id) {
                    // check ad extra days from id
                    $ad_extra_days = Models\AdExtraDay::where('ad_extra_id', $ad_extra_id['id'])->where('category_id', $ad->category_id)->first();                    
                    if (!empty($ad_extra_days)) {       
                        $ad_extra_amount = $ad_extra_amount + $ad_extra_days->amount;
                    }
                }
            }
            if ($ad_extra_amount > 0) {
                $extra_days_points = $extra_points = $ad_extra_amount / AMOUNT_PER_POINT;
                if ($user->available_points > 0) {
                    $points = $user->available_points;
                    if ($points >= $extra_days_points) {
                        $extra_points = $points - $extra_days_points;
                        $user->available_points = $user->available_points - $extra_days_points;                        
                    } else {
                        $extra_points = $extra_days_points - $points;
                        $user->available_points = 0;
                    }
                    //update user points
                    $user->update();
                }
                if ($extra_points > 0) {
                    if (!empty($args['ad_extra_id'])) { 
                    if (!empty($args['ad_extra_id'])) {
                        $ad->pending_payment_log = serialize($args['ad_extra_id']);
                        $ad->update();
                    }
                    $amount = $extra_points * AMOUNT_PER_POINT;
                    $data = $args;
                    $data['amount'] = $amount + $ad_price;
                    $data['user_id'] = $authUser['id'];
                    $data['ad_id'] = $ad->id;
                    $data['name'] = $ad->title;
                    $data['description'] = $ad->description;
                    $data['ad_extra_payment'] = true;
                    $data['ad_extra_ids'] = (array)$args['ad_extra_id'];
                    $data['ad_payment'] = false;
                    $data['payment_gateway_id'] = $args['payment_gateway_id'];
                    $payment = new Models\Payment;
                    $data['notify_url'] = $_server_domain_url . '/ipn/process_ipn/Ad/' . $ad->id . '/' . md5(SECURITY_SALT . $ad->id . SITE_NAME);
                    $data['success_url'] = $_server_domain_url . '/#!/ads/payment/' .$ad->id. '?error_code=0';
                    $data['cancel_url'] = $_server_domain_url . '/#!/ads/payment/' .$ad->id. '?error_code=512';
                    $payment_response = $payment->processPayment($ad->id, $data, 'Ad');
                    /*$adminId = Models\User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
                    insertTransaction($authUser['id'], $adminId['id'], $ad->id, 'Ad', \Constants\TransactionType::AmountAddedToAd, $args['payment_gateway_id'], $data['amount'], $data['amount'], 0);*/
                    }
                } else {
                    if (!empty($args['ad_extra_id'])) { 
                        foreach ($args['ad_extra_id'] as $ad_extra_id) {
                            $ad_extra_days = Models\AdExtraDay::where('ad_extra_id', $ad_extra_id['id'])->where('category_id', $ad->category_id)->first();                    
                            if (!empty($ad_extra_days)) {
                                $days+= $ad_extra_days->days;
                                $ad_extra_day_id = $ad_extra_days->id;
                                $user_ad_extra = new Models\UserAdExtra;
                                $user_ad_extra->ad_extra_day_id = $ad_extra_day_id;
                                $user_ad_extra->ad_id = $ad->id;
                                $user_ad_extra->ad_extra_id = $ad_extra_id['id'];
                                $user_ad_extra->amount = $ad_extra_days->amount;
                                $user_ad_extra->payment_gateway_id = $args['payment_gateway_id'];
                                $user_ad_extra->is_payment_completed = 1;
                                $user_ad_extra->user_id = $authUser['id'];
                                $user_ad_extra->save();                        
                                $ad_extra_amount = $ad_extra_amount + $ad_extra_days->amount;
                                $validity_days = $ad_extra_days->days;
                                if ($ad_extra_id['id'] == \Constants\ConstAdExtra::TopAd) {
                                    $ad->is_show_as_top_ads = 1;
                                    $ad->top_ads_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::Highlight) {
                                    $ad->is_highlighted = 1;
                                    $ad->highlighted_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::Urgent) {
                                    $ad->is_urgent = 1;
                                    $ad->urgent_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::InTop) {
                                    $ad->is_show_ad_in_top = 1;
                                    $ad->ad_in_top_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                }
                            }
                        }
                    }
                    $ad->update();
                    $payment_response = array(
                        'status' => 'Captured',
                        'error' => array(
                            'code' => 0,
                            'message' => 'Ad extras added successfully'
                        )
                    );
                }
            }
            return renderWithJson($payment_response);
        } else {
            return renderWithJson($result, 'Ad could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateAd'));
/**
 * GET adsGet
 * Summary: Fetch all ads
 * Notes: Returns all ads from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/me/ads', function ($request, $response, $args) {

    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $conditions = array();
        $ads = Models\Ad::with('attachment', 'ad_owner', 'category', 'advertiser_type', 'ad_form_field', 'city', 'state', 'country');
        // filter by ad type
        if (!empty($queryParams['type'])) {
        } // related ad get
        // ad list
        $ads = $ads->Filter($queryParams)->where('user_id', $authUser['id'])->paginate($count)->toArray();
        $data = $ads['data'];
        unset($ads['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $ads
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canViewMyAd'));
/**
 * GET adsGet
 * Summary: Fetch all ads
 * Notes: Returns all ads from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ads', function ($request, $response, $args) {

    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $conditions = array();
        if (!empty($queryParams['q'])) {
            $search_keywords = Models\SearchKeyword::where('keyword', 'ilike', $queryParams['q'])->first();
            if (!empty($search_keywords)) {
                $search_keywords->search_log_count = $search_keywords->search_log_count + 1;
                $search_keywords->update();
            } else {
                $search_keyword = new Models\SearchKeyword;
                $search_keyword->keyword = $queryParams['q'];
                $search_keyword->search_log_count = 1;
                $search_keyword->save();
            }
        }
        $ads = Models\Ad::leftJoin('users', 'ads.user_id', '=', 'users.id');
        $ads = $ads->leftJoin('categories', 'ads.category_id', '=', 'categories.id');
        $ads = $ads->leftJoin('advertiser_types', 'ads.advertiser_type_id', '=', 'advertiser_types.id');
        $ads = $ads->select('ads.*', 'users.username as ad_owner_username', 'categories.name as category_name', 'advertiser_types.name as advertiser_type_name');
        $ads = $ads->with('attachment', 'ad_owner', 'category', 'advertiser_type', 'ad_form_field', 'city', 'state', 'country')->with(array(
            'ad_favorite' => function ($q) use ($authUser) {
            
                if (!empty($authUser->id)) {
                    $q->where('user_id', $authUser->id);
                }
            }
        ));
        if (empty($authUser) || (!empty($authUser['role_id']) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin)) {
            $now = date('Y-m-d h:i:s');
            $ads->where('ad_start_date', '<=', $now);
            $ads->where('ad_end_date', '>=', $now);
        }
        // filter by ad type
        if (!empty($queryParams['type'])) {
            $type = $queryParams['type'];
            if ($type == 'highlight') {
                $ads = $ads->where('is_highlighted', 1);
            } elseif ($type == 'top') {
                $ads = $ads->where('is_show_as_top_ads', 1);
            } elseif ($type == 'urgent') {
                $ads = $ads->where('is_urgent', 1);
            }
        }
        if (!empty($queryParams['latitude']) && !empty($queryParams['longitude'])) {
            $lat = $latitude = $queryParams['latitude'];
            $lng = $longitude = $queryParams['longitude'];
            if (!empty($queryParams['radius'])) {
                $radius = $queryParams['radius'];
            } else {
                $radius = 5;
            }
            $distance = 'ROUND(( 6371 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ')) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) )))';
            $ads = $ads->select('ads.*');
            $ads = $ads->selectRaw($distance . ' AS distance');
            $ads = $ads->whereRaw('(' . $distance . ')<=' . $radius);
            $ads = $ads->orderBy("distance", 'desc');
        }
        // ad list
        $ads = $ads->Filter($queryParams)->paginate($count)->toArray();
        $min_amount = Models\Ad::where('is_active', 1)->min('price');
        $max_amount = Models\Ad::where('is_active', 1)->max('price');
        $data = $ads['data'];
        unset($ads['data']);
        $ads['min_price'] = $min_amount;
        $ads['max_price'] = $max_amount;
        $results = array(
            'data' => $data,
            '_metadata' => $ads
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST adsPost
 * Summary: Creates a new ad
 * Notes: Creates a new ad
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/ads', function ($request, $response, $args) {

    global $authUser;
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $ad = new Models\Ad($args);
    $ad->slug = Inflector::slug(strtolower($args['title']), '-');
    //get country, state and city ids
    $ad->country_id = !empty($args['country_iso2']) ? findCountryIdFromIso2($args['country_iso2']) : 0;
    $ad->state_id = !empty($args['state_name']) ? findOrSaveAndGetStateId($args['state_name'], $ad->country_id) : 0;
    $ad->city_id = !empty($args['city_name']) ? findOrSaveAndGetCityId($args['city_name'], $ad->country_id, $ad->state_id) : 0;
    unset($ad->country_iso2);
    unset($ad->state_name);
    unset($ad->city_name);
    $this->geohash = new Geohash();
    if (!empty($args['latitude']) && !empty($args['longitude'])) {
        $ad->hash = $this->geohash->encode(round($args['latitude'], 6), round($args['longitude'], 6));
    }
    $ad->user_id = $authUser['id'];
    $result = array();
    $payment_response = array();
    try {
        $validationErrorFields = $ad->validate($args);
        $category = Models\Category::select('allowed_free_ads_count', 'post_ad_fee')->find($args['category_id']);
        $checkPayment = Models\Category::checkPayment($authUser['id'], $args['category_id'], $category);
        $userAdPackage = Models\UserAdPackage::getUserAdPackage($authUser['id'], $args['category_id']);
        if (isset($args['ad_fee']) && $args['ad_fee'] != $checkPayment['amount']) {
            return renderWithJson($result, 'Ad could not be added. Please, try again.', '', 1);
        }
        if (empty($validationErrorFields)) {
            $ad->save();
            // Check key exists in form field and update response in adformfield table
            if (!empty($args['ad_form_field']) && !empty($args['ad_form_field'][0]) && $ad->id) {            
                foreach ($args['ad_form_field'] as $adFormField) {
                    foreach ($adFormField as $field_name => $value) {
                        $formField = Models\FormField::where('name', $field_name)->where('category_id', $ad->category_id)->select('id')->first();
                        if (!empty($formField)) {
                            $adFormField = new Models\AdFormField;
                            $adFormField->ad_id = $ad->id;
                            $adFormField->response = $value;
                            $adFormField->form_field_id = $formField->id;
                            $adFormField->save();
                        }
                    }
                }
            }
            if (!empty($args['image'])) {
                if (is_array($args['image'])) {
                    $is_multi = 1;
                    foreach ($args['image'] as $image) {
                        if ((!empty($image)) && (file_exists(APP_PATH . '/media/tmp/' . $image))) {
                            saveImage('Ad', $image, $ad->id, $is_multi);
                        }
                    }
                } elseif ((!empty($args['image'])) && (file_exists(APP_PATH . '/media/tmp/' . $args['image']))) {
                    saveImage('Ad', $args['image'], $ad->id);
                }
            }
            // Payment process
            $user = Models\User::find($ad->user_id);
            $ad_price = 0.00;
            // check ad package purchased by user
            if (!empty($userAdPackage)) {                
                $userAdPackage->allowed_ad_count = $userAdPackage->allowed_ad_count - 1;                
                $userAdPackage->update();
                // update ad_count in users table
                $user->ad_count = $user->ad_count - 1;
                $user->update();               
            }
            $days = 0;
            $ad_extra_amount = $checkPayment['amount'];
            if (!empty($args['ad_extra_id'])) {
                foreach ($args['ad_extra_id'] as $ad_extra_id) {
                    // check ad extra days from id
                    $ad_extra_days = Models\AdExtraDay::where('ad_extra_id', $ad_extra_id['id'])->where('category_id', $ad->category_id)->first();                    
                    if (!empty($ad_extra_days)) {       
                        $ad_extra_amount = $ad_extra_amount + $ad_extra_days->amount;
                    }
                }
            }
            if ($ad_extra_amount > 0) {
                $extra_days_points = $extra_points = $ad_extra_amount / AMOUNT_PER_POINT;
                if ($user->available_points > 0) {
                    $points = $user->available_points;
                    if ($points >= $extra_days_points) {
                        $extra_points = $points - $extra_days_points;
                        $user->available_points = $user->available_points - $extra_days_points;                        
                    } else {
                        $extra_points = $extra_days_points - $points;
                        $user->available_points = 0;
                    }
                    //update user points
                    $user->update();
                }
                if ($extra_points > 0) {
                    if (!empty($args['ad_extra_id'])) {
                        $ad->pending_payment_log = serialize($args['ad_extra_id']);
                        $ad->update();
                    }
                    $amount = $extra_points * AMOUNT_PER_POINT;
                    $data = $args;
                    $data['amount'] = $amount + $ad_price;
                    $data['user_id'] = $authUser['id'];
                    $data['name'] = $ad->title;
                    $data['description'] = $ad->description;
                    $data['ad_id'] = $ad->id;
                    $data['ad_extra_payment'] = true;
                    if(isset($args['ad_extra_id'])) {
                        $data['ad_extra_ids'] = (array)$args['ad_extra_id'];
                    }
                    $data['ad_payment'] = false;
                    $data['payment_gateway_id'] = $args['payment_gateway_id'];
                    $payment = new Models\Payment;
                    $data['notify_url'] = $_server_domain_url . '/ipn/process_ipn/Ad/' . $ad->id . '/' . md5(SECURITY_SALT . $ad->id . SITE_NAME);
                    $data['success_url'] = $_server_domain_url . '/#!/ads/add?error_code=0';
                    $data['cancel_url'] = $_server_domain_url . '/#!/ads/add?error_code=512';
                    $payment_response = $payment->processPayment($ad->id, $data, 'Ad');
                    /* $adminId = Models\User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
                    insertTransaction($authUser['id'], $adminId['id'], $ad->id, 'Ad', \Constants\TransactionType::AmountAddedToAd, $args['payment_gateway_id'], $data['amount'], $data['amount'], 0);*/
                } else {
                    if (!empty($args['ad_extra_id'])) { 
                        foreach ($args['ad_extra_id'] as $ad_extra_id) {
                            $ad_extra_days = Models\AdExtraDay::where('ad_extra_id', $ad_extra_id['id'])->where('category_id', $ad->category_id)->first();                    
                            if (!empty($ad_extra_days)) {
                                $days+= $ad_extra_days->days;
                                $ad_extra_day_id = $ad_extra_days->id;
                                $user_ad_extra = new Models\UserAdExtra;
                                $user_ad_extra->ad_extra_day_id = $ad_extra_day_id;
                                $user_ad_extra->ad_id = $ad->id;
                                $user_ad_extra->ad_extra_id = $ad_extra_id['id'];
                                $user_ad_extra->amount = $ad_extra_days->amount;
                                $user_ad_extra->payment_gateway_id = $args['payment_gateway_id'];
                                $user_ad_extra->is_payment_completed = 1;
                                $user_ad_extra->user_id = $authUser['id'];
                                $user_ad_extra->save();                        
                                $ad_extra_amount = $ad_extra_amount + $ad_extra_days->amount;
                                $validity_days = $ad_extra_days->days;
                                if ($ad_extra_id['id'] == \Constants\ConstAdExtra::TopAd) {
                                    $ad->is_show_as_top_ads = 1;
                                    $ad->top_ads_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::Highlight) {
                                    $ad->is_highlighted = 1;
                                    $ad->highlighted_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::Urgent) {
                                    $ad->is_urgent = 1;
                                    $ad->urgent_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::InTop) {
                                    $ad->is_show_ad_in_top = 1;
                                    $ad->ad_in_top_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                                }
                            }
                        }
                    }
                    if (!empty($userAdPackage)) {
                        $userAdPackage->used_ad_count = $userAdPackage->used_ad_count + 1;
                        $userAdPackage->update();
                    }
                    $ad->update();
                    $payment_response = array(
                        'status' => 'Captured',
                        'error' => array(
                            'code' => 0,
                            'message' => 'Ad extras added successfully'
                        )
                    );
                }
            }
            $categoryDetails = Models\Category::where('id', $args['category_id'])->select('name', 'allowed_days_to_display_ad')->first();
            // Ad end date update calculation
            if (empty($categoryDetails->allowed_days_to_display_ad) && $categoryDetails->allowed_days_to_display_ad == 0) {
                $ad_display_days = DAYS_TO_DISPLAY_POSTED_AD;
            } else {
                $ad_display_days = $categoryDetails->allowed_days_to_display_ad;
            }
            $ad->ad_start_date = date('Y-m-d');
            if ($ad_display_days > $days) {
                $ad->ad_end_date = date('Y-m-d', strtotime($ad_display_days . ' days'));
            } else {
                $ad->ad_end_date = date('Y-m-d', strtotime($days . ' days'));
            }
            $ad->update();
            $advertiserTypeDetails = Models\AdvertiserType::where('id', $args['advertiser_type_id'])->select('name')->first();
            $emailFindReplace_user = array(
                '##USERNAME##' => $user['username'],
                '##AD_NAME##' => $args['title'],
                '##CATEGORY_NAME##' => $categoryDetails['name'],
                '##ADVERTISER_TYPE##' => $advertiserTypeDetails['name'],
                '##PRICE##' => $args['price'],
                '##DESCRIPTION##' => $args['description'],
                '##ADVERTISER_NAME##' => $args['advertiser_name'],
                '##PHONE_NUMBER##' => $args['phone_number'],
                '##AD_URL##' => $_server_domain_url . '/#!/ad/' . $ad->id . '/' . $ad->slug
            );
            sendMail('createad', $emailFindReplace_user, $user['email']);
            $ad = Models\Ad::with('attachment', 'ad_owner', 'category', 'advertiser_type', 'ad_form_field')->find($ad->id);
            return renderWithJson($payment_response, 'Ads added successfully.', '', 0);
        } else {
            if (empty($userAdPackage)) {
                $validationErrorFields['invalid'] = 'ad_package';
            }
            return renderWithJson($result, 'Ad could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAd'));
/**
 * DELETE adSearchesAdSearchIdDelete
 * Summary: Delete ad search
 * Notes: Deletes a single ad search based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ad_searches/{adSearchId}', function ($request, $response, $args) {

    $adSearch = Models\AdSearch::find($request->getAttribute('adSearchId'));
    try {
        $adSearch->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad search could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdSearch'));
/**
 * GET adSearchesAdSearchIdGet
 * Summary: Fetch ad search
 * Notes: Returns a ad search based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_searches/{adSearchId}', function ($request, $response, $args) {

    $adSearch = Models\AdSearch::with('category', 'user', 'ip')->find($request->getAttribute('adSearchId'));
    $result['data'] = $adSearch->toArray();
    return renderWithJson($result);
})->add(new ACL('canViewAdSearch'));
/**
 * PUT adSearchesAdSearchIdPut
 * Summary: Update ad search by its id
 * Notes: Update ad search by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/ad_searches/{adSearchId}', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $adSearch = Models\AdSearch::find($request->getAttribute('adSearchId'));
    $adSearch->fill($args);
    if (!empty($authUser['id'])) {
        $adSearch->user_id = $authUser['id'];
    } else {
        $adSearch->user_id = 0;
    }
    $result = array();
    try {
        $adSearch->ip_id = saveIp();
        $validationErrorFields = $adSearch->validate($args);
        if (empty($validationErrorFields)) {
            $adSearch->save();
            $adSearch = Models\AdSearch::with('category', 'user', 'ip')->find($adSearch->id);
            $result = $adSearch->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad search could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad search could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateAdSearch'));
/**
 * GET adSearchesGet
 * Summary: Fetch all ad searches
 * Notes: Returns all ad searches from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_searches', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $adSearches = Models\AdSearch::leftJoin('categories', 'ad_searches.category_id', '=', 'categories.id');
        $adSearches = $adSearches->leftJoin('users', 'ad_searches.user_id', '=', 'users.id');
        $adSearches = $adSearches->leftJoin('ips', 'ad_searches.ip_id', '=', 'ips.id');
        $adSearches = $adSearches->select('ad_searches.*', 'categories.name as category_name', 'users.username as user_username', 'ips.ip as ip_ip');
        $adSearches = $adSearches->with('category', 'user', 'ip')->Filter($queryParams)->paginate($count)->toArray();
        $data = $adSearches['data'];
        unset($adSearches['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $adSearches
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAdSearch'));
/**
 * POST adSearchesPost
 * Summary: Creates a new ad search
 * Notes: Creates a new ad search
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/ad_searches', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $adSearch = new Models\AdSearch($args);
    if (!empty($authUser['id'])) {
        $adSearch->user_id = $authUser['id'];
    } else {
        $adSearch->user_id = 0;
    }
    $result = array();
    try {
        $adSearch->ip_id = saveIp();
        $validationErrorFields = $adSearch->validate($args);
        if (empty($validationErrorFields)) {
            $adSearch->save();
            $adSearch = Models\AdSearch::with('category', 'user', 'ip')->find($adSearch->id);
            $result = $adSearch->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad search could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad search could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAdSearch'));
/**
 * GET Adviews
 * Summary: Creates a new Adviews
 * Notes: Creates a new Adviews
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_views', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $adView = Models\AdView::leftJoin('ads', 'ad_views.ad_id', '=', 'ads.id');
        $adView = $adView->leftJoin('users', 'ad_views.user_id', '=', 'users.id');
        $adView = $adView->leftJoin('ips', 'ad_views.ip_id', '=', 'ips.id');
        $adView = $adView->select('ad_views.*', 'ads.title as ad_title', 'users.username as user_username','ips.ip as ip_ip');
        $adView = $adView->with('ad', 'user', 'ip')->Filter($queryParams)->paginate(PAGE_LIMIT)->toArray();
        $data = $adView['data'];
        unset($adView['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $adView
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAdView'));
/**
 * DELETE adViewsAdViewIdDelete
 * Summary: Delete ad view
 * Notes: Deletes a single ad view based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ad_views/{adViewId}', function ($request, $response, $args) {

    $adView = Models\AdView::find($request->getAttribute('adViewId'));
    try {
        $adView->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad view could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdView'));
/**
 * GET adViewsAdViewIdGet
 * Summary: Fetch ad view
 * Notes: Returns a ad view based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_views/{adViewId}', function ($request, $response, $args) {

    $result = array();
    $adView = Models\AdView::find($request->getAttribute('adViewId'));
    if (!empty($adView)) {
        $result['data'] = $adView;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewAdView'));
/**
 * GET AdFormField
 * Summary: Creates a new AdFormField
 * Notes: Creates a new AdFormField
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_form_fields', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $adFormField = Models\AdFormField::with('form_field', 'ad')->Filter($queryParams)->paginate(PAGE_LIMIT)->toArray();
        $data = $adFormField['data'];
        unset($adFormField['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $adFormField
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAdFormField'));
/**
 * DELETE advertiserTypesAdvertiserTypeIdDelete
 * Summary: Delete advertiser type
 * Notes: Deletes a single advertiser type based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/advertiser_types/{advertiserTypeId}', function ($request, $response, $args) {

    $advertiserType = Models\AdvertiserType::find($request->getAttribute('advertiserTypeId'));
    try {
        $advertiserType->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Advertiser type could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdvertiserType'));
/**
 * GET advertiserTypesAdvertiserTypeIdGet
 * Summary: Fetch advertiser type
 * Notes: Returns a advertiser type based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/advertiser_types/{advertiserTypeId}', function ($request, $response, $args) {

    $result = array();
    $advertiserType = Models\AdvertiserType::find($request->getAttribute('advertiserTypeId'));
    if (!empty($advertiserType)) {
        $result['data'] = $advertiserType->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
})->add(new ACL('canViewAdvertiserType'));
/**
 * PUT advertiserTypesAdvertiserTypeIdPut
 * Summary: Update advertiser type by its id
 * Notes: Update advertiser type by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/advertiser_types/{advertiserTypeId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $advertiserType = Models\AdvertiserType::find($request->getAttribute('advertiserTypeId'));
    foreach ($args as $key => $arg) {
        if (!is_array($arg)) {
            $advertiserType->{$key} = $arg;
        }
    }
    $result = array();
    try {
        $validationErrorFields = $advertiserType->validate($args);
        if (empty($validationErrorFields)) {
            $advertiserType->save();
            $result = $advertiserType->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Advertiser type could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Advertiser type could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateAdvertiserType'));
/**
 * GET advertiserTypesGet
 * Summary: Fetch all advertiser types
 * Notes: Returns all advertiser types from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/advertiser_types', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $advertiserTypes = Models\AdvertiserType::Filter($queryParams)->paginate($count)->toArray();
        $data = $advertiserTypes['data'];
        unset($advertiserTypes['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $advertiserTypes
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST advertiserTypesPost
 * Summary: Creates a new advertiser type
 * Notes: Creates a new advertiser type
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/advertiser_types', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $advertiserType = new Models\AdvertiserType;
    foreach ($args as $key => $arg) {
        if (!is_array($arg)) {
            $advertiserType->{$key} = $arg;
        }
    }
    $result = array();
    try {
        $validationErrorFields = $advertiserType->validate($args);
        if (empty($validationErrorFields)) {
            $advertiserType->save();
            $result = $advertiserType->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Advertiser type could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Advertiser type could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAdvertiserType'));
/**
 * DELETE categoriesCategoryIdDelete
 * Summary: Delete category
 * Notes: Deletes a single category based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/categories/{categoryId}', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    if (!empty($request->getAttribute('categoryId'))) {
        if (!empty($request->getAttribute('categoryId') && !empty($queryParams['form_field_id']))) {
            Models\FormField::where('category_id', $request->getAttribute('categoryId'))->where('id', $queryParams['form_field_id'])->delete();
        }
        $category = Models\Category::find($request->getAttribute('categoryId'));
    }
    try {
        Models\FormField::where('category_id', $request->getAttribute('categoryId'))->delete();
        $category->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Category could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteCategory'));
/**
 * GET categoriesCategoryIdGet
 * Summary: Fetch category
 * Notes: Returns a category based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/categories/{categoryId}', function ($request, $response, $args) {

    $result = array();
    $category = Models\Category::with('form_field', 'attachment', 'subcategories', 'parent')->find($request->getAttribute('categoryId'));
    if (!empty($category)) {
        $result['data'] = $category->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
});
/**
 * PUT categoriesCategoryIdPut
 * Summary: Update category by its id
 * Notes: Update category by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/categories/{categoryId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $category = Models\Category::find($request->getAttribute('categoryId'));
    if (!$category) {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    $category->fill($args);
    if (isset($args['name'])) {
        $category->slug = Inflector::slug(strtolower($args['name']), '-');
    }
    try {
        $validationErrorFields = $category->validate($args);
        if (empty($validationErrorFields)) {
            $category->save();
            if (!empty($args['form_field'])) {
                foreach ($args['form_field'] as $value) {
                    if (!empty($value['id'])) {
                        $formField = Models\FormField::where('category_id', $request->getAttribute('categoryId'))->where('id', $value['id'])->get()->toArray();
                        Models\FormField::where('id', $formField['0']['id'])->update(['name' => $value['name'], 'display_name' => $value['display_name'], 'label' => $value['label'], 'input_type_id' => $value['input_type_id'], 'info' => $value['info'], 'is_required' => $value['is_required'], 'depends_on' => $value['depends_on'], 'depend_value' => $value['depend_value'], 'display_order' => $value['display_order'], 'is_active' => $value['is_active'], 'category_id' => $category->id]);
                    } else {
                        $formFieldCount = Models\Category::where('id', $category->id)->select('form_field_count')->first()->toArray();
                        $formField = new Models\FormField;
                        $formField->name = $value['name'];
                        $formField->display_name = $value['display_name'];
                        $formField->label = $value['label'];
                        $formField->input_type_id = $value['input_type_id'];
                        $formField->info = $value['info'];
                        $formField->is_required = $value['is_required'];
                        $formField->depends_on = $value['depends_on'];
                        $formField->depend_value = $value['depend_value'];
                        $formField->display_order = $value['display_order'];
                        $formField->is_active = $value['is_active'];
                        $formField->options = $value['options'];
                        $formField->category_id = $category->id;
                        $formField->save();
                        $formFieldCount['form_field_count']++;
                        Models\Category::where('id', $category->id)->update(['form_field_count' => $formFieldCount['form_field_count']]);
                    }
                }
            }
            if (!empty($args['image'])) {
                saveImage('Category', $args['image'], $category->id);
            }
            $category = Models\Category::with('form_field', 'attachment')->find($category->id);
            $result = $category->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Category could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Category could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateCategory'));
/**
 * GET categoriesGet
 * Summary: Fetch all categories
 * Notes: Returns all categories from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/categories', function ($request, $response, $args) {

    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    $max_level = 0;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $categories = Models\Category::leftJoin('categories as parent', 'parent.id', '=', 'categories.parent_id');
        $categories = $categories->select('categories.*', 'parent.name as parent_name');
        if (empty($authUser) || (!empty($authUser) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin)) {
            $categories = $categories->where('categories.is_active', 1);
        }
        if (isset($queryParams['parent_id'])) {
            $categories = $categories->where('categories.parent_id', $queryParams['parent_id']);
        }
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $result['data'] = $categories->with('form_field', 'attachment', 'subcategories', 'parent')->Filter($queryParams)->get()->toArray();
        } else {
            $result = $categories->with('form_field', 'attachment', 'subcategories', 'parent')->Filter($queryParams)->paginate($count)->toArray();
        }
        if (!empty($result['data'])) {
            $max_level = getMaxLevel($result['data']);
            $i = 0;
            foreach ($result['data'] as $category) {
                $new_categories[$i] = $category;
                $category_count = Models\Category::where('parent_id', $category['id'])->count();
                $new_categories[$i]['category_count'] = $category_count;
                $i++;
            }
            $data = $new_categories;
        } else {
            $data = array();
        }
        $result['max_level'] = $max_level;
        unset($result['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $result
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST categoriesPost
 * Summary: Creates a new category
 * Notes: Creates a new category
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/categories', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $category = new Models\Category($args);
    $category->slug = Inflector::slug(strtolower($args['name']), '-');
    $result = array();
    try {
        $validationErrorFields = $category->validate($args);
        if (empty($validationErrorFields)) {
            if (empty($category->parent_id)) {
                $category->parent_id = 0;
            }
            $category->save();
            $formFieldCount = Models\Category::where('id', $category->id)->select('form_field_count')->first()->toArray();
            if (!empty($args['form_field'])) {
                foreach ($args['form_field'] as $formfieldvalue) {
                    $formField = new Models\FormField;
                    $formField->name = $formfieldvalue['name'];
                    $formField->display_name = $formfieldvalue['display_name'];
                    $formField->label = $formfieldvalue['label'];
                    $formField->input_type_id = $formfieldvalue['input_type_id'];
                    $formField->info = $formfieldvalue['info'];
                    $formField->is_required = $formfieldvalue['is_required'];
                    $formField->depends_on = $formfieldvalue['depends_on'];
                    $formField->depend_value = $formfieldvalue['depend_value'];
                    $formField->display_order = $formfieldvalue['display_order'];
                    $formField->is_active = $formfieldvalue['is_active'];
                    $formField->options = $formfieldvalue['options'];
                    $formField->category_id = $category->id;
                    $formField->save();
                    $formFieldCount['form_field_count']++;
                }
            }
            if (!empty($args['image'])) {
                saveImage('Category', $args['image'], $category->id);
            }
            Models\Category::where('id', $category->id)->update(['form_field_count' => $formFieldCount['form_field_count']]);
            $category = Models\Category::with('form_field', 'attachment')->find($category->id);
            $result = $category->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Category could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Category could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateCategory'));
/**
 * GET categoriesCategoryIdGet
 * Summary: Fetch category
 * Notes: Returns a category based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/categories/{categoryId}/check_payment', function ($request, $response, $args) {

    global $authUser;
    $result = array();
    $categoryId = $request->getAttribute('categoryId');
    $category = Models\Category::find($categoryId);
    if (!empty($category)) {
        $result['data'] = Models\Category::checkPayment($authUser['id'], $categoryId, $category);
        return renderWithJson($result);
    } else {
          return renderWithJson($result, 'No record found.', '', 1);
    }    
})->add(new ACL('canCheckCategoryPayment'));
