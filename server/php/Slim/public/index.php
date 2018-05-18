<?php
/**
 * Base API
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
require_once '../lib/bootstrap.php';
global $result;
/**
 * GET oauthGet
 * Summary: Get site token
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/oauth/token', function ($request, $response, $args) {

    $post_val = array(
        'grant_type' => 'client_credentials',
        'client_id' => OAUTH_CLIENT_ID,
        'client_secret' => OAUTH_CLIENT_SECRET
    );
    $response = getToken($post_val);
    return renderWithJson($response);
});
/**
 * GET oauthRefreshTokenGet
 * Summary: Get site refresh token
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/oauth/refresh_token', function ($request, $response, $args) {

    $post_val = array(
        'grant_type' => 'refresh_token',
        'refresh_token' => $_GET['token'],
        'client_id' => OAUTH_CLIENT_ID,
        'client_secret' => OAUTH_CLIENT_SECRET
    );
    $response = getToken($post_val);
    return renderWithJson($response);
});
/**
 * POST usersRegisterPost
 * Summary: new user
 * Notes: Post new user.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/register', function ($request, $response, $args) {

    global $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    $user = new Models\User($args);
    $validationErrorFields = $user->validate($args);
    if (checkAlreadyUsernameExists($args['username'])) {
        $validationErrorFields['unique'][] = 'username';
    }
    if (checkAlreadyEmailExists($args['email'])) {
        $validationErrorFields['unique'][] = 'email';
    }
    if (empty($validationErrorFields)) {
        $user->password = getCryptHash($args['password']);
        try {
            if (USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER == 0 || USER_IS_AUTO_LOGIN_AFTER_REGISTER == 1) {
                $user->is_active = 1;
                $user->is_email_confirmed = 1;
            }
            $user->save();
            // insert user notifications table
            $userNotification = new Models\UserNotification;
            $userNotification->user_id = $user->id;
            $userNotification->save();
            if (USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER == 0 || USER_IS_AUTO_LOGIN_AFTER_REGISTER == 1) {
                $emailFindReplace = array(
                    '##USERNAME##' => $user->username,
                    '##SUPPORT_EMAIL##' => SUPPORT_EMAIL
                );
                // send to admin mail if USER_IS_ADMIN_MAIL_AFTER_REGISTER is true
                if (USER_IS_ADMIN_MAIL_AFTER_REGISTER == 1) {
                    $emailFindReplace = array(
                        '##USERNAME##' => $user->username,
                        '##USEREMAIL##' => $user->email,
                        '##SUPPORT_EMAIL##' => SUPPORT_EMAIL
                    );
                    sendMail('newuserjoin', $emailFindReplace, SITE_CONTACT_EMAIL);
                }
                // send welcome mail to user if USER_IS_WELCOME_MAIL_AFTER_REGISTER is true
                if (USER_IS_WELCOME_MAIL_AFTER_REGISTER == 1) {
                    sendMail('welcomemail', $emailFindReplace, $user->email);
                }
            } elseif (USER_IS_EMAIL_VERIFICATION_FOR_REGISTER == 1) {
                $emailFindReplace = array(
                    '##USERNAME##' => $user->username,
                    '##ACTIVATION_URL##' => $_server_domain_url . '/#!/users/activation/' . $user->id . '/' . md5($user->username)
                );
                sendMail('activationrequest', $emailFindReplace, $user->email);
            } else {
            }
            if (USER_IS_AUTO_LOGIN_AFTER_REGISTER == 1) {
                $scopes = '';
                if (isset($user->role_id) && $user->role_id == \Constants\ConstUserTypes::User) {
                    $scopes = implode(' ', $user['user_scopes']);
                } else {
                    $scopes = '';
                }
                $post_val = array(
                    'grant_type' => 'password',
                    'username' => $user->username,
                    'password' => $user->password,
                    'client_id' => OAUTH_CLIENT_ID,
                    'client_secret' => OAUTH_CLIENT_SECRET,
                    'scope' => $scopes
                );
                $response = getToken($post_val);
                $result = $response + $user->toArray();
            } else {
                $user = Models\User::find($user->id);
                $result = $user->toArray();
            }
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'User could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'User could not be added. Please, try again.', $validationErrorFields, 1);
    }
});
/**
 * PUT usersUserIdActivationHashPut
 * Summary: User activation
 * Notes: Send activation hash code to user for activation. \n
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}/activation/{hash}', function ($request, $response, $args) {

    $result = array();
    $user = Models\User::where('id', $args['userId'])->first();
    if (!empty($user)) {
        if (md5($user['username']) == $args['hash']) {
            $user->is_active = 1;
            $user->is_agree_terms_conditions = 1;
            $user->is_email_confirmed = 1;
            $user->save();
            $result['data'] = $user->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Invalid user deatails.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Invalid user deatails.', '', 1);
    }
});
/**
 * POST usersLoginPost
 * Summary: User login
 * Notes: User login information post
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/login', function ($request, $response, $args) {

    $body = $request->getParsedBody();
    $result = array();
    $user = new Models\User;
    if (USER_USING_TO_LOGIN == 'username') {
        $log_user = $user->with('attachment')->where('username', $body['username'])->where('is_active', 1)->where('is_email_confirmed', 1)->first();
    } else {
        $log_user = $user->with('attachment')->where('email', $body['email'])->where('is_active', 1)->where('is_email_confirmed', 1)->first();
    }
    $password = crypt($body['password'], $log_user['password']);
    $validationErrorFields = $user->validate($body);
    if (empty($validationErrorFields) && !empty($log_user) && ($password == $log_user['password'])) {
        $scopes = '';
        if (!empty($log_user['scopes_' . $log_user['role_id']])) {
            $scopes = implode(' ', $log_user['scopes_' . $log_user['role_id']]);
        }
        $post_val = array(
            'grant_type' => 'password',
            'username' => $log_user['username'],
            'password' => $password,
            'client_id' => OAUTH_CLIENT_ID,
            'client_secret' => OAUTH_CLIENT_SECRET,
            'scope' => $scopes
        );
        $response = getToken($post_val);
        if (!empty($response['refresh_token'])) {
            $result = $response + $log_user->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Your login credentials are invalid.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Your login credentials are invalid.', $validationErrorFields, 1);
    }
});
/**
 * Get userSocialLoginGet
 * Summary: Social Login for twitter
 * Notes: Social Login for twitter
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/social_login', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    if (!empty($queryParams['type'])) {
        $response = social_auth_login($queryParams['type']);
        return renderWithJson($response);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * POST userSocialLoginPost
 * Summary: User Social Login
 * Notes:  Social Login
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/social_login', function ($request, $response, $args) {

    $body = $request->getParsedBody();
    $result = array();
    if (!empty($_GET['type'])) {
        $response = social_auth_login($_GET['type'], $body);
        return renderWithJson($response);
    } else {
        return renderWithJson($result, 'Please choose one provider.', '', 1);
    }
});
/**
 * POST usersForgotPasswordPost
 * Summary: User forgot password
 * Notes: User forgot password
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/forgot_password', function ($request, $response, $args) {

    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::where('email', $args['email'])->first();
    if (!empty($user)) {
        $validationErrorFields = $user->validate($args);
        if (empty($validationErrorFields) && !empty($user)) {
            $password = uniqid();
            $user->password = getCryptHash($password);
            try {
                $user->save();
                $emailFindReplace = array(
                    '##USERNAME##' => $user['username'],
                    '##PASSWORD##' => $password,
                );
                sendMail('forgotpassword', $emailFindReplace, $user['email']);
                return renderWithJson($result, 'An email has been sent with your new password', '', 0);
            } catch (Exception $e) {
                return renderWithJson($result, 'Email Not found', '', 1);
            }
        } else {
            return renderWithJson($result, 'Process could not be found', $validationErrorFields, 1);
        }
    } else {
        return renderWithJson($result, 'No data found', '', 1);
    }
});
/**
 * PUT UsersuserIdChangePasswordPut .
 * Summary: update change password
 * Notes: update change password
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}/change_password', function ($request, $response, $args) {

    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::find($request->getAttribute('userId'));
    $validationErrorFields = $user->validate($args);
    $password = crypt($args['password'], $user['password']);
    if (empty($validationErrorFields)) {
        if ($password == $user['password']) {
            $change_password = $args['new_password'];
            $user->password = getCryptHash($change_password);
            try {
                $user->save();
                $emailFindReplace = array(
                    '##PASSWORD##' => $args['new_password'],
                    '##USERNAME##' => $user['username']
                );
                if ($authUser['role_id'] == \Constants\ConstUserTypes::Admin) {
                    sendMail('adminchangepassword', $emailFindReplace, $user->email);
                } else {
                    sendMail('changepassword', $emailFindReplace, $user['email']);
                }
                $result['data'] = $user->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, 'User Password could not be updated. Please, try again', '', 1);
            }
        } else {
            return renderWithJson($result, 'Password is invalid . Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'User Password could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateUser'));
/**
 * GET usersLogoutGet
 * Summary: User Logout
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/logout', function ($request, $response, $args) {

    if (!empty($_GET['token'])) {
        try {
            $oauth = Models\OauthAccessToken::where('access_token', $_GET['token'])->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson(array(), 'Please verify in your token', '', 1);
        }
    }
});
/**
 * GET UsersGet
 * Summary: Filter  users
 * Notes: Filter users.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $users = Models\User::leftJoin('roles', 'users.role_id', '=', 'roles.id');
        $users = $users->select('users.*', 'roles.name as role_name');
        $users = $users->with('city', 'state', 'country', 'attachment', 'role')->Filter($queryParams);
        $users = $users->paginate($count)->toArray();
        $data = $users['data'];
        unset($users['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $users
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListUser'));
/**
 * POST UserPost
 * Summary: Create New user by admin
 * Notes: Create New user by admin
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $user = new Models\User($args);
    $validationErrorFields = $user->validate($args);
    $validationErrorFields['unique'] = array();
    if (checkAlreadyUsernameExists($args['username'])) {
        array_push($validationErrorFields['unique'], 'username');
    }
    if (checkAlreadyEmailExists($args['email'])) {
        array_push($validationErrorFields['unique'], 'email');
    }
    if (empty($validationErrorFields['unique'])) {
        unset($validationErrorFields['unique']);
    }
    $user->password = getCryptHash($args['password']);
    if (empty($validationErrorFields)) {
        try {
            $user->is_active = 1;
            $user->is_email_confirmed = 1;
            if(isset($args['is_active'])) {
                $user->is_active = $args['is_active'];
            }
            if(isset($args['is_email_confirmed'])) {
                $user->is_email_confirmed = $args['is_email_confirmed'];
            }            
            $user->save();
            $emailFindReplace_user = array(
                '##USERNAME##' => $user->username,
                '##LOGINLABEL##' => (USER_USING_TO_LOGIN == 'username') ? 'Username' : 'Email',
                '##USEDTOLOGIN##' => (USER_USING_TO_LOGIN == 'username') ? $user->username : $user->email,
                '##PASSWORD##' => $args['password']
            );
            sendMail('adminuseradd', $emailFindReplace_user, $user->email);
            $result = $user->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'User could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'User could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canCreateUser'));
/**
 * GET UseruserIdGet
 * Summary: Get particular user details
 * Notes: Get particular user details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    if (!empty($queryParams['fields'])) {
        $fieldvalue = explode(',', $queryParams['fields']);
    } else {
        $fieldvalue = '*';
    }
    $result = array();
    $user = Models\User::with('city', 'state', 'country', 'attachment', 'role', 'user_notification')->Filter($queryParams)->where('id', $request->getAttribute('userId'))->select($fieldvalue)->first();
    if (!empty($user)) {
        $user = $user->toArray();
        if (empty($user['city'])) {
            $user['city']['name'] = '';
        }
        if (empty($user['state'])) {
            $user['state']['name'] = '';
        }
        if (empty($user['country'])) {
            $user['country']['iso_alpha2'] = '';
        }
        $result['data'] = $user;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewUser'));
/**
 * GET UseruserIdGet
 * Summary: Get particular user details
 * Notes: Get particular user details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/me', function ($request, $response, $args) {

    global $authUser;
    $queryParams = $request->getQueryParams();
    if (!empty($queryParams['fields'])) {
        $fieldvalue = explode(',', $queryParams['fields']);
    } else {
        $fieldvalue = '*';
    }
    $result = array();
    $user = Models\User::with('city', 'state', 'country', 'attachment', 'role')->Filter($queryParams)->where('id', $authUser->id)->select($fieldvalue)->first();
    if (!empty($user)) {
        $result['data'] = $user;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewMyProfile'));
/**
 * PUT UsersuserIdPut
 * Summary: Update user
 * Notes: Update user
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}', function ($request, $response, $args) {

    $body = $request->getParsedBody();
    $result = array();
    $user = Models\User::find($request->getAttribute('userId'));
    if (!empty($user)) {
        $user->fill($body);
        if (!empty($body['country']['iso_alpha2'])) {
            //get country, state and city ids
            $user->country_id = findCountryIdFromIso2($body['country']['iso_alpha2']);
            $user->state_id = findOrSaveAndGetStateId($body['state']['name'], $user->country_id);
            $user->city_id = findOrSaveAndGetCityId($body['city']['name'], $user->country_id, $user->state_id);
        }
        unset($user->city);
        unset($user->state);
        unset($user->country);
        unset($user->role);
        unset($user->user_notification);
        unset($user->city_name);
        unset($user->state_name);
        unset($user->country_iso2);
        unset($user->location);
        $this->geohash = new Geohash();
        if (!empty($body['latitude']) && !empty($body['longitude'])) {
            $user->hash = $this->geohash->encode(round($body['latitude'], 6), round($body['longitude'], 6));
        }
        //$user->is_email_confirmed = $body['is_email_confirmed'];
        try {
            $user->save();
            if (!empty($body['image']['attachment'])) {
                saveImage('UserAvatar', $body['image']['attachment'], $user->id);
            }
            $user = Models\User::with('attachment')->find($user->id);
            $result['data'] = $user->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'User could not be updated. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Invalid user Details, try again.', '', 1);
    }
})->add(new ACL('canUpdateUser'));
/**
 * DELETE UseruserId Delete
 * Summary: DELETE user by admin
 * Notes: DELETE user by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/users/{userId}', function ($request, $response, $args) {

    $result = array();
    $user = Models\User::find($request->getAttribute('userId'));
    $data = $user;
    if (!empty($user)) {
        try {
            $user->delete();
            $emailFindReplace = array(
                '##USERNAME##' => $data['username']
            );
            sendMail('adminuserdelete', $emailFindReplace, $data['email']);
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'User could not be deleted. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Invalid User details.', '', 1);
    }
})->add(new ACL('canDeleteUser'));
/**
 * GET ProvidersGet
 * Summary: all providers lists
 * Notes: all providers lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/providers', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $providers = Models\Provider::Filter($queryParams)->paginate($count)->toArray();
        $data = $providers['data'];
        unset($providers['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $providers
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * GET  ProvidersProviderIdGet
 * Summary: Get  particular provider details
 * Notes: GEt particular provider details.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/providers/{providerId}', function ($request, $response, $args) {

    $result = array();
    $provider = Models\Provider::find($request->getAttribute('providerId'));
    if (!empty($provider)) {
        $result['data'] = $provider->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewProvider'));
/**
 * PUT ProvidersProviderIdPut
 * Summary: Update provider details
 * Notes: Update provider details.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/providers/{providerId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $provider = Models\Provider::find($request->getAttribute('providerId'));
    $validationErrorFields = $provider->validate($args);
    if (empty($validationErrorFields)) {
        $provider->fill($args);
        try {
            $provider->save();
            $result['data'] = $provider->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Provider could not be updated. Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'Provider could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateProvider'));
/**
 * GET RoleGet
 * Summary: Get roles lists
 * Notes: Get roles lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/roles', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $roles = Models\Role::Filter($queryParams)->paginate($count)->toArray();
        $data = $roles['data'];
        unset($roles['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $roles
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * GET RolesIdGet
 * Summary: Get paticular email templates
 * Notes: Get paticular email templates
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/roles/{roleId}', function ($request, $response, $args) {

    $result = array();
    $role = Models\Role::find($request->getAttribute('roleId'));
    if (!empty($role)) {
        $result['data'] = $role->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * GET MoneyTransferAccountsGet
 * Summary: Get money transfer accounts lists
 * Notes: Get money transfer accounts lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/money_transfer_accounts', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $moneyTransferAccounts = Models\MoneyTransferAccount::Filter($queryParams);
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $moneyTransferAccounts = $moneyTransferAccounts->get()->toArray();
            unset($moneyTransferAccounts['data']);
            $result = array(
                'data' => $moneyTransferAccounts
            );
        } else {
            $moneyTransferAccounts = Models\MoneyTransferAccount::leftJoin('users', 'money_transfer_accounts.user_id', '=', 'users.id');
            $moneyTransferAccounts = $moneyTransferAccounts->select('money_transfer_accounts.*', 'users.username as user_username');
            $moneyTransferAccounts = $moneyTransferAccounts->with('user')->Filter($queryParams)->paginate($count)->toArray();
            $data = $moneyTransferAccounts['data'];
            unset($moneyTransferAccounts['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $moneyTransferAccounts
            );
        }
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListMoneyTransferAccount'));
/**
 * GET usersuserIdMoneyTransferAccountsGet
 * Summary: Get money transfer accounts lists
 * Notes: Get money transfer accounts lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}/money_transfer_accounts', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $moneyTransferAccounts = Models\MoneyTransferAccount::where('user_id', $request->getAttribute('userId'))->Filter($queryParams);
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $moneyTransferAccounts = $moneyTransferAccounts->get()->toArray();
            unset($moneyTransferAccounts['data']);
            $result = array(
                'data' => $moneyTransferAccounts
            );
        } else {
            $moneyTransferAccounts = Models\MoneyTransferAccount::where('user_id', $request->getAttribute('userId'))->Filter($queryParams)->paginate($count)->toArray();
            $data = $moneyTransferAccounts['data'];
            unset($moneyTransferAccounts['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $moneyTransferAccounts
            );
        }
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canUserListMoneyTransferAccount'));
/**
 * POST moneyTransferAccountPost
 * Summary: Create New money transfer account
 * Notes: Create money transfer account.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/money_transfer_accounts', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $moneyTransferAccount = new Models\MoneyTransferAccount($args);
    $validationErrorFields = $moneyTransferAccount->validate($args);
    if (empty($validationErrorFields)) {
        if ($authUser['role_id'] == \Constants\ConstUserTypes::User) {
            $moneyTransferAccount->user_id = $authUser->id;
        }
        try {
            $moneyTransferAccount->save();
            $result = $moneyTransferAccount->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Account could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Account could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canCreateMoneyTransferAccount'));
/**
 * POST usersuserIdmoneyTransferAccountPost
 * Summary: Create New money transfer account
 * Notes: Create money transfer account.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/{userId}/money_transfer_accounts', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $moneyTransferAccount = new Models\MoneyTransferAccount($args);
    $validationErrorFields = $moneyTransferAccount->validate($args);
    if (empty($validationErrorFields)) {
        $moneyTransferAccount->user_id = $request->getAttribute('userId');
        try {
            $moneyTransferAccount->save();
            $result = $moneyTransferAccount->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Account could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Account could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUserCreateMoneyTransferAccount'));
/**
 * GET MoneyTransferAccountsMoneyTransferAccountIdGet
 * Summary: Get particular money transfer accounts
 * Notes: Get particular money transfer accounts
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/money_transfer_accounts/{moneyTransferAccountId}', function ($request, $response, $args) {

    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::with('user')->find($request->getAttribute('moneyTransferAccountId'));
    if (!empty($moneyTransferAccount)) {
        $result['data'] = $moneyTransferAccount->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewMoneyTransferAccount'));
/**
 * GET usersuserIdMoneyTransferAccountsMoneyTransferAccountIdGet
 * Summary: Get particular money transfer accounts
 * Notes: Get particular money transfer accounts
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}/money_transfer_accounts/{moneyTransferAccountId}', function ($request, $response, $args) {

    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::with('user')->where('id', $request->getAttribute('moneyTransferAccountId'))->where('user_id', $request->getAttribute('userId'))->first();
    if (!empty($moneyTransferAccount)) {
        $result['data'] = $moneyTransferAccount;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canUserViewMoneyTransferAccount'));
/**
 * PUT moneyTransferAccountMoneyTransferAccountIdPut
 * Summary: Update money transfer account by its id
 * Notes: Update money transfer account.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/money_transfer_accounts/{MoneyTransferAccountId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::find($request->getAttribute('MoneyTransferAccountId'));
    $validationErrorFields = $moneyTransferAccount->validate($args);
    if (empty($validationErrorFields)) {
        $moneyTransferAccount->fill($args);
        try {
            $moneyTransferAccount->save();
            $result['data'] = $moneyTransferAccount->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Account could not be updated. Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'Account could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateMoneyTransferAccount'));
/**
 * PUT usersuserIdmoneyTransferAccountMoneyTransferAccountIdPut
 * Summary: Update money transfer account by its id
 * Notes: Update money transfer account.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}/money_transfer_accounts/{MoneyTransferAccountId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::where('id', $request->getAttribute('MoneyTransferAccountId'))->where('user_id', $request->getAttribute('userId'))->first();
    $validationErrorFields = $moneyTransferAccount->validate($args);
    if (empty($validationErrorFields)) {
        $moneyTransferAccount->fill($args);
        try {
            $moneyTransferAccount->save();
            $result['data'] = $moneyTransferAccount->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Account could not be updated. Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'Account could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new ACL('canUserUpdateMoneyTransferAccount'));
/**
 * DELETE MoneyTransferAccountsMoneyTransferAccountIdDelete
 * Summary: Delete money transfer account
 * Notes: Delete money transfer account
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/money_transfer_accounts/{moneyTransferAccountId}', function ($request, $response, $args) {

    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::where('id', $request->getAttribute('moneyTransferAccountId'))->first();
    try {
        $moneyTransferAccount->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Money transfer account could not be added. Please, try again', '', 1);
    }
})->add(new ACL('canDeleteMoneyTransferAccount'));
/**
 * DELETE usersuserIdMoneyTransferAccountsMoneyTransferAccountIdDelete
 * Summary: Delete money transfer account
 * Notes: Delete money transfer account
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/users/{userId}/money_transfer_accounts/{moneyTransferAccountId}', function ($request, $response, $args) {

    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::where('id', $request->getAttribute('moneyTransferAccountId'))->first();
    try {
        $moneyTransferAccount->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Money transfer account could not be added. Please, try again', '', 1);
    }
})->add(new ACL('canUserDeleteMoneyTransferAccount'));
/**
 * GET ContactsGet
 * Summary: Get  contact lists
 * Notes: Get contact lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/contacts', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $contacts = Models\Contact::leftJoin('ips', 'contacts.ip_id', '=', 'ips.id');
        $contacts = $contacts->select('contacts.*', 'ips.ip as ip_ip');
        $contacts = $contacts->with('ip')->Filter($queryParams)->paginate($count)->toArray();
        $data = $contacts['data'];
        unset($contacts['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $contacts
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canListContact'));
/**
 * POST contactPost
 * Summary: add contact
 * Notes: add contact
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/contacts', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $contact = new Models\Contact($args);
    $contact->ip_id = saveIp();
    $validationErrorFields = $contact->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $contact->save();
            $contact_list = Models\Contact::where('id', $contact->id)->first();
            $emailFindReplace = array(
                '##FIRST_NAME##' => $contact_list['first_name'],
                '##LAST_NAME##' => $contact_list['last_name'],
                '##FROM_EMAIL##' => $contact_list['email'],
                '##IP##' => $contact_list['ip']['ip'],
                '##TELEPHONE##' => $contact_list['phone'],
                '##MESSAGE##' => $contact_list['message'],
                '##SUBJECT##' => $contact_list['subject']
            );
            sendMail('contactus', $emailFindReplace, SITE_CONTACT_EMAIL);
            $result = $contact->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Contact user could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Contact could not be added. Please, try again.', $validationErrorFields, 1);
    }
});
/**
 * GET ContactscontactIdGet
 * Summary: get particular contact details
 * Notes: get particular contact details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/contacts/{contactId}', function ($request, $response, $args) {

    $result = array();
    $contact = Models\Contact::with('ip')->find($request->getAttribute('contactId'));
    if (!empty($contact)) {
        $result['data'] = $contact->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewContact'));
/**
 * DELETE ContactsContactIdDelete
 * Summary: DELETE contact Id by admin
 * Notes: DELETE contact Id by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/contacts/{contactId}', function ($request, $response, $args) {

    $result = array();
    $contact = Models\Contact::find($request->getAttribute('contactId'));
    try {
        if (!empty($contact)) {
            $contact->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Contact could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteContact'));
/**
 * GET TransactionGet
 * Summary: Get all transactions list.
 * Notes: Get all transactions list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/transactions', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $transactions = Models\Transaction::leftJoin('users', 'transactions.user_id', '=', 'users.id');
        $transactions = $transactions->select('transactions.*', 'users.username as user_username');
        $transactions = $transactions->with('user', 'to_user', 'payment_gateway')->Filter($queryParams)->paginate($count)->toArray();
        $data = $transactions['data'];
        unset($transactions['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $transactions
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAllTransactions'));
/**
 * GET UsersUserIdTransactionsGet
 * Summary: Get user transactions list.
 * Notes: Get user transactions list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}/transactions', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $transactions = Models\Transaction::with('user', 'to_user', 'payment_gateway');
        if (!empty($request->getAttribute('userId'))) {
            $user_id = $request->getAttribute('userId');
            $transactions = $transactions->where(function ($q) use ($user_id) {
            
                $q->where('user_id', $user_id)->orWhere('to_user_id', $user_id);
            });
        }
        $transactions = $transactions->Filter($queryParams)->paginate($count)->toArray();
        $data = $transactions['data'];
        unset($transactions['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $transactions
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListUserTransactions'));
/**
 * GET paymentGatewayGet
 * Summary: Get  payment gateways
 * Notes: Filter payment gateway.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $paymentGateways = Models\PaymentGateway::Filter($queryParams)->paginate($count)->toArray();
        $data = $paymentGateways['data'];
        unset($paymentGateways['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $paymentGateways
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListPaymentGateway'));
/**
 * PUT paymentGatewayspaymentGatewayIdPut
 * Summary: Update Payment gateway by its id
 * Notes: Update Payment gateway.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/payment_gateways/{paymentGatewayId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $paymentGateway = Models\PaymentGateway::find($request->getAttribute('paymentGatewayId'));
    foreach ($args as $key => $arg) {
        if (!is_array($arg)) {
            $paymentGateway->{$key} = $arg;
        }
    }
    try {
        $paymentGateway->save();
        $result['data'] = $paymentGateway->toArray();
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Payment gateway could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdatePaymentGateway'));
$app->PUT('/api/v1/payment_gateway_settings/{paymentGatewayId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $live_mode_value = $args['live_mode_value'];
    $test_mode_value = $args['test_mode_value'];
    $payment_gateway_settings = Models\PaymentGatewaySetting::where('payment_gateway_id', $request->getAttribute('paymentGatewayId'))->get()->toArray();
    try {
        foreach ($payment_gateway_settings as $payment_gateway_setting) {
            $field = $payment_gateway_setting['name'];
            $paymentGatewaySetting = Models\PaymentGatewaySetting::find($payment_gateway_setting['id']);
            if (array_key_exists($field, $live_mode_value)) {
                $paymentGatewaySetting->live_mode_value = $live_mode_value[$field];
            }
            if (array_key_exists($field, $test_mode_value)) {
                $paymentGatewaySetting->test_mode_value = $test_mode_value[$field];
            }
            $paymentGatewaySetting->update();
        }
        $result['data'] = $payment_gateway_settings;
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Payment gateway could not be updated. Please, try again.', '', 1);
    }
});
/**
 * GET Paymentgateways list.
 * Summary: Paymentgateway list.
 * Notes: Paymentgateways list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/list', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    try {
        //Fetch zazpay info
        //$data['zazpay'] = ZazpayPaymentGateway::with('zazpay_group')->get();
        $settings = Models\PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::ZazPay);
        $settings = $settings->get();
        foreach ($settings as $value) {
            $zazpay[$value->name] = $value->test_mode_value;
        }
        $s = new ZazPay_API(array(
            'api_key' => $zazpay['zazpay_api_key'],
            'merchant_id' => $zazpay['zazpay_merchant_id'],
            'website_id' => $zazpay['zazpay_website_id'],
            'secret_string' => $zazpay['zazpay_secret_string'],
            'is_test' => true,
            'cache_path' => APP_PATH . '/tmp/cache/'
        ));
        $data['zazpay'] = $s->callGetGateways();
        $data['wallet'] = array(
            'wallet_enabled' => true
        );
        return renderWithJson($data);
    } catch (Exception $e) {
        return renderWithJson($result, 'No Paymentgateway found.Please try again.', '', 1);
    }
});
/**
 * GET paymentGatewaysZazpaySynchronizeGet
 * Summary: Get zazpay synchronize details
 * Notes: Get zazpay synchronize details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/zazpay_synchronize', function ($request, $response, $args) {

    global $capsule;
    $result = array();
    $paymentGateway = new Models\PaymentGateway();
    $zazPaymentSettings = Models\PaymentGatewaySetting::where('payment_gateway_id', 1)->get();
    foreach ($zazPaymentSettings as $value) {
        $sudpay_synchronize[$value->name] = $value->test_mode_value;
    }
    $s = new ZazPay_API(array(
        'api_key' => $sudpay_synchronize['zazpay_api_key'],
        'merchant_id' => $sudpay_synchronize['zazpay_merchant_id'],
        'website_id' => $sudpay_synchronize['zazpay_website_id'],
        'secret_string' => $sudpay_synchronize['zazpay_secret_string'],
        'is_test' => true,
        'cache_path' => APP_PATH . '/tmp/cache/'
    ));
    $currentPlan = $s->callPlan();
    $plantype = $s->plantype();
    if (!empty($currentPlan['error']['message'])) {
        return renderWithJson($result, $currentPlan['error']['message'], '', 1);
    } else {
        if ($currentPlan['brand'] == 'Transparent Branding') {
            $plan = $plantype['TransparentBranding'];
        } elseif ($currentPlan['brand'] == 'ZazPay Branding') {
            $plan = $plantype['VisibleBranding'];
        } elseif ($currentPlan['brand'] == 'Any Branding') {
            $plan = $plantype['AnyBranding'];
        }
        $paymentGatewaySetting = new Models\PaymentGatewaySetting();
        if ($plantype['is_test_mode']) {
            $payment_gateway_api = $paymentGatewaySetting->where('name', 'is_payment_via_api')->where('payment_gateway_id', 1)->first();
            $payment_gateway_api->test_mode_value = $plan;
            $payment_gateway_api->save();
            $payment_gateway_plan = $paymentGatewaySetting->where('name', 'zazpay_subscription_plan')->where('payment_gateway_id', 1)->first();
            $payment_gateway_plan->test_mode_value = $currentPlan['name'];
            $payment_gateway_plan->save();
        } else {
            $payment_gateway_api = $paymentGatewaySetting->where('name', 'is_payment_via_api')->where('payment_gateway_id', 1)->first();
            $payment_gateway_api->live_mode_value = $plan;
            $payment_gateway_api->save();
            $payment_gateway_plan = $paymentGatewaySetting->where('name', 'zazpay_subscription_plan')->where('payment_gateway_id', 1)->first();
            $payment_gateway_plan->live_mode_value = $currentPlan['name'];
            $payment_gateway_plan->save();
        }
        $gateway_response = $s->callGateways();
        $capsule::table('zazpay_payment_groups')->delete();
        $capsule::table('zazpay_payment_gateways')->delete();
        if (empty($gateway_response['error']['message'])) {
            foreach ($gateway_response['gateways'] as $gateway_group) {
                $zaz_groups = new Models\ZazpayPaymentGroup;
                $zaz_groups->zazpay_group_id = $gateway_group['id'];
                $zaz_groups->name = $gateway_group['name'];
                $zaz_groups->thumb_url = $gateway_group['thumb_url'];
                $zaz_groups->save();
                foreach ($gateway_group['gateways'] as $gateway) {
                    $zaz_payment_gateways = new Models\ZazpayPaymentGateway;
                    $supported_actions = $gateway['supported_features'][0]['actions'];
                    $zaz_payment_gateways->is_marketplace_supported = 0;
                    if (in_array('Marketplace-Auth', $supported_actions)) {
                        $zaz_payment_gateways->is_marketplace_supported = 1;
                    }
                    $zaz_payment_gateways->zazpay_gateway_id = $gateway['id'];
                    $zaz_payment_gateways->zazpay_gateway_details = serialize($gateway);
                    $zaz_payment_gateways->zazpay_gateway_name = $gateway['display_name'];
                    $zaz_payment_gateways->zazpay_payment_group_id = $zaz_groups->id;
                    $zaz_payment_gateways->save();
                }
            }
        }
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    }
});
/**
 * GET paymentGatewayGet
 * Summary: Get  payment gateways
 * Notes: Filter payment gateway.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/{paymentGatewayId}', function ($request, $response, $args) {

    $result = array();
    $paymentGateway = Models\PaymentGateway::with('payment_settings')->find($request->getAttribute('paymentGatewayId'));
    if (!empty($paymentGateway)) {
        $result['data'] = $paymentGateway->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * GET zazpayPaymentPatewaysGet.
 * Summary: Get  zaz payment gateways.
 * Notes: Get  zaz payment gateways.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/zazpay_payment_gateways', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $zazPaymentGateways = Models\ZazpayPaymentGateway::Filter($queryParams)->paginate($count)->toArray();
        $data = $zazPaymentGateways['data'];
        unset($zazPaymentGateways['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $zazPaymentGateways
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * GET PagesGet
 * Summary: Filter  pages
 * Notes: Filter pages.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/pages', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $pages = Models\Page::Filter($queryParams)->paginate($count)->toArray();
        $data = $pages['data'];
        unset($pages['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $pages
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST pagePost
 * Summary: Create New page
 * Notes: Create page.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/pages', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $page = new Models\Page($args);
    $validationErrorFields = $page->validate($args);
    if (empty($validationErrorFields)) {
        $page->slug = Inflector::slug(strtolower($page->title), '-');
        try {
            $page->save();
            $result = $page->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Page user could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Page could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canCreatePage'));
/**
 * GET PagePageIdGet.
 * Summary: Get page.
 * Notes: Get page.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/pages/{pageId}', function ($request, $response, $args) {

    $result = array();
    $page = Models\Page::find($request->getAttribute('pageId'));
    if (!empty($page)) {
        $result['data'] = $page->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Page not found', '', 1);
    }
});
/**
 * PUT PagepageIdPut
 * Summary: Update page by admin
 * Notes: Update page by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/pages/{pageId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $page = Models\Page::find($request->getAttribute('pageId'));
    $validationErrorFields = $page->validate($args);
    if (empty($validationErrorFields)) {
        $page->fill($args);
        $page->slug = Inflector::slug(strtolower($page->title), '-');
        try {
            $page->save();
            $result['data'] = $page->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Page could not be updated. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Page could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdatePage'));
/**
 * DELETE PagepageIdDelete
 * Summary: DELETE page by admin
 * Notes: DELETE page by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/pages/{pageId}', function ($request, $response, $args) {

    $result = array();
    $page = Models\Page::find($request->getAttribute('pageId'));
    try {
        if (!empty($page)) {
            $page->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Page could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeletePage'));
/**
 * GET SettingcategoriesGet
 * Summary: Filter  Setting categories
 * Notes: Filter Setting categories.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/setting_categories', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $settingCategories = Models\SettingCategory::Filter($queryParams)->paginate($count)->toArray();
        $data = $settingCategories['data'];
        unset($settingCategories['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $settingCategories
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListSettingCategory'));
/**
 * GET SettingcategoriesSettingCategoryIdGet
 * Summary: Get setting categories.
 * Notes: GEt setting categories.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/setting_categories/{settingCategoryId}', function ($request, $response, $args) {

    $result = array();
    $settingCategory = Models\SettingCategory::find($request->getAttribute('settingCategoryId'));
    if (!empty($settingCategory)) {
        $result['data'] = $settingCategory->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canListSettingCategory'));
/**
 * GET SettingGet .
 * Summary: Get settings.
 * Notes: GEt settings.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/settings', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        if (!empty($queryParams['fields'])) {
            $fieldvalue = explode(',', $queryParams['fields']);
        } else {
            $fieldvalue = '*';
        }
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $result['data'] = Models\Setting::select($fieldvalue)->get();
        } else {
            $count = PAGE_LIMIT;
            if (!empty($queryParams['limit'])) {
                $count = $queryParams['limit'];
            }
            $Settings = Models\Setting::Filter($queryParams)->paginate($count)->toArray();
            $data = $Settings['data'];
            unset($Settings['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $Settings
            );
        }
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * GET settingssettingIdGet
 * Summary: GET particular Setting.
 * Notes: Get setting.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/settings/{settingId}', function ($request, $response, $args) {

    $result = array();
    $setting = Models\Setting::with('setting_category')->find($request->getAttribute('settingId'));
    if (!empty($setting)) {
        $result['data'] = $setting->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewSetting'));
/**
 * PUT SettingsSettingIdPut
 * Summary: Update setting by admin
 * Notes: Update setting by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/settings/{settingId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $setting = Models\Setting::find($request->getAttribute('settingId'));
    $setting->fill($args);
    try {
        $setting->save();
        $result['data'] = $setting->toArray();
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Setting could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateSetting'));
/**
 * GET EmailTemplateGet
 * Summary: Get email templates lists
 * Notes: Get email templates lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/email_templates', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $emailTemplates = Models\EmailTemplate::Filter($queryParams)->paginate($count)->toArray();
        $data = $emailTemplates['data'];
        unset($emailTemplates['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $emailTemplates
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListEmailTemplate'));
/**
 * GET EmailTemplateemailTemplateIdGet
 * Summary: Get paticular email templates
 * Notes: Get paticular email templates
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/email_templates/{emailTemplateId}', function ($request, $response, $args) {

    $result = array();
    $emailTemplate = Models\EmailTemplate::find($request->getAttribute('emailTemplateId'));
    if (!empty($emailTemplate)) {
        $result['data'] = $emailTemplate->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewEmailTemplate'));
/**
 * PUT EmailTemplateemailTemplateIdPut
 * Summary: Put paticular email templates
 * Notes: Put paticular email templates
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/email_templates/{emailTemplateId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $emailTemplate = Models\EmailTemplate::find($request->getAttribute('emailTemplateId'));
    $validationErrorFields = $emailTemplate->validate($args);
    if (empty($validationErrorFields)) {
        $emailTemplate->fill($args);
        try {
            $emailTemplate->save();
            $result['data'] = $emailTemplate->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Email template could not be updated. Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'Email template could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateEmailTemplate'));
/**
 * GET CitiesGet
 * Summary: Filter  cities
 * Notes: Filter cities.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cities', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $result['data'] = Models\City::where('is_active', 1)->get();
        } else {
            $count = PAGE_LIMIT;
            if (!empty($queryParams['limit'])) {
                $count = $queryParams['limit'];
            }
            $cities = Models\City::leftJoin('states', 'cities.state_id', '=', 'states.id');
            $cities = $cities->leftJoin('countries', 'cities.country_id', '=', 'countries.id');
            $cities = $cities->select('cities.*', 'states.name as state_name', 'countries.name as country_name');
            $cities = $cities->with('country', 'state')->Filter($queryParams);
            $cities = $cities->paginate($count)->toArray();
            $data = $cities['data'];
            unset($cities['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $cities
            );
        }
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST citiesPost
 * Summary: create new city
 * Notes: create new city
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/cities', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $city = new Models\City($args);
    $validationErrorFields = $city->validate($args);
    if (empty($validationErrorFields)) {
        $city->slug = Inflector::slug(strtolower($city->name), '-');
        try {
            $city->save();
            $result = $city->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'City could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'city could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canCreateCity'));
/**
 * GET CitiesGet
 * Summary: Get  particular city
 * Notes: Get  particular city
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cities/{cityId}', function ($request, $response, $args) {

    $result = array();
    $city = Models\City::with('state', 'country')->find($request->getAttribute('cityId'));
    if (!empty($city)) {
        $result['data'] = $city->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * PUT CitiesCityIdPut
 * Summary: Update city by admin
 * Notes: Update city by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/cities/{cityId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $city = Models\City::find($request->getAttribute('cityId'));
    $validationErrorFields = $city->validate($args);
    if (empty($validationErrorFields)) {
        $city->fill($args);
        $city->slug = Inflector::slug(strtolower($city->name), '-');
        try {
            $city->save();
            $result['data'] = $city->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'City could not be updated. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'City could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateCity'));
/**
 * DELETE CitiesCityIdDelete
 * Summary: DELETE city by admin
 * Notes: DELETE city by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/cities/{cityId}', function ($request, $response, $args) {

    $result = array();
    $city = Models\City::find($request->getAttribute('cityId'));
    try {
        $city->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'City could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteCity'));
/**
 * GET StatesGet
 * Summary: Filter  states
 * Notes: Filter states.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/states', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $result['data'] = Models\State::with('country')->where('is_active', 1)->get();
        } else {
            $states = Models\State::leftJoin('countries', 'states.country_id', '=', 'countries.id');
            $states = $states->select('states.*', 'countries.name as country_name');
            $states = $states->with('country')->Filter($queryParams)->paginate($count)->toArray();
            $data = $states['data'];
            unset($states['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $states
            );
        }
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST StatesPost
 * Summary: Create New states
 * Notes: Create states.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/states', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $state = new Models\State($args);
    $validationErrorFields = $state->validate($args);
    if (empty($validationErrorFields)) {
        $state->slug = Inflector::slug(strtolower($state->name), '-');
        try {
            $state->save();
            $result = $state->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'State could not be added. Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'State could not be added. Please, try again', $validationErrorFields, 1);
    }
})->add(new ACL('canCreateState'));
/**
 * GET StatesstateIdGet
 * Summary: Get  particular state
 * Notes: Get  particular state
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/states/{stateId}', function ($request, $response, $args) {

    $result = array();
    $state = Models\State::with('country')->find($request->getAttribute('stateId'));
    if (!empty($state)) {
        $result['data'] = $state->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewState'));
/**
 * PUT StatesStateIdPut
 * Summary: Update states by admin
 * Notes: Update states.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/states/{stateId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $state = Models\State::find($request->getAttribute('stateId'));
    $validationErrorFields = $state->validate($args);
    if (empty($validationErrorFields)) {
        $state->fill($args);
        $state->slug = Inflector::slug(strtolower($state->name), '-');
        try {
            $state->save();
            $result['data'] = $state->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'State could not be updated. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'State could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateState'));
/**
 * DELETE StatesStateIdDelete
 * Summary: DELETE states by admin
 * Notes: DELETE states by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/states/{stateId}', function ($request, $response, $args) {

    $result = array();
    $state = Models\State::find($request->getAttribute('stateId'));
    try {
        if (!empty($state)) {
            $state->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'State could not be added. Please, try again', '', 1);
    }
})->add(new ACL('canDeleteState'));
/**
 * GET countriesGet
 * Summary: Filter  countries
 * Notes: Filter countries.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/countries', function ($request, $response, $args) use ($app) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $result['data'] = Models\Country::get();
        } else {
            $count = PAGE_LIMIT;
            if (!empty($queryParams['limit'])) {
                $count = $queryParams['limit'];
            }
            $countries = Models\Country::Filter($queryParams)->paginate($count)->toArray();
            $data = $countries['data'];
            unset($countries['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $countries
            );
        }
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST countriesPost
 * Summary: Create New countries
 * Notes: Create countries.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/countries', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $country = new Models\Country($args);
    $validationErrorFields = $country->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $country->save();
            $result = $country->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Country could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Country could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canCreateCountry'));
/**
 * GET countriescountryIdGet
 * Summary: Get countries
 * Notes: Get countries.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/countries/{countryId}', function ($request, $response, $args) {

    $result = array();
    $country = Models\Country::find($request->getAttribute('countryId'));
    if (!empty($country)) {
        $result['data'] = $country->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canViewCountry'));
/**
 * PUT countriesCountryIdPut
 * Summary: Update countries by admin
 * Notes: Update countries.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/countries/{countryId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $country = Models\Country::find($request->getAttribute('countryId'));
    $validationErrorFields = $country->validate($args);
    if (empty($validationErrorFields)) {
        $country->fill($args);
        try {
            $country->save();
            $result['data'] = $country->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Country could not be updated. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Country could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateCountry'));
/**
 * DELETE countrycountryIdDelete
 * Summary: DELETE country by admin
 * Notes: DELETE country.
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/countries/{countryId}', function ($request, $response, $args) {

    $result = array();
    $country = Models\Country::find($request->getAttribute('countryId'));
    try {
        if (!empty($country)) {
            $country->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Country could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteCountry'));
/**
 * GET LanguageGet
 * Summary: Filter  language
 * Notes: Filter language.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/languages', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $result['data'] = Models\Language::Filter($queryParams)->get();
        } else {
            $count = PAGE_LIMIT;
            if (!empty($queryParams['limit'])) {
                $count = $queryParams['limit'];
            }
            $languages = Models\Language::Filter($queryParams)->paginate($count)->toArray();
            $data = $languages['data'];
            unset($languages['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $languages
            );
        }
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * POST LanguagePost
 * Summary: add language
 * Notes: add language
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/languages', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $language = new Models\Language($args);
    $validationErrorFields = $language->validate($args);
    if (checkAlreadyLanguageExists($args['name'])) {
        $validationErrorFields['unique'] = 'name';
    }
    if (empty($validationErrorFields)) {
        try {
            $language->save();
            $result = $language->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Language user could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Language could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canCreateLanguage'));
/**
 * GET LanguagelanguageIdGet
 * Summary: Get particular language
 * Notes: Get particular language.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/languages/{languageId}', function ($request, $response, $args) {

    $result = array();
    $language = Models\Language::find($request->getAttribute('languageId'));
    if (!empty($language)) {
        $result['data'] = $language->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Language not found', '', 1);
    }
})->add(new ACL('canViewLanguage'));
/**
 * PUT LanguagelanguageIdPut
 * Summary: Update language by admin
 * Notes: Update language by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/languages/{languageId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $result = array();
    $language = Models\Language::find($request->getAttribute('languageId'));
    $validationErrorFields = $language->validate($args);
    if (empty($validationErrorFields)) {
        $language->fill($args);
        try {
            $language->save();
            $result['data'] = $language->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Language could not be updated. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Language could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateLanguage'));
/**
 * DELETE LanguageLanguageIdDelete
 * Summary: DELETE language by its id
 * Notes: DELETE language.
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/languages/{languageId}', function ($request, $response, $args) {

    $result = array();
    $language = Models\Language::find($request->getAttribute('languageId'));
    try {
        if (!empty($language)) {
            $language->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Language not found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Language could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteLanguage'));
/**
 * GET StatsGet
 * Summary: Get site stats lists
 * Notes: Get site stats lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/stats', function ($request, $response, $args) {

    global $authUser;
    $result = array();
    //Customers
    $result['ads'] = Models\Ad::where('is_active', 1)->count();
    $result['categories'] = Models\Category::where('is_active', 1)->count();
    $result['locations'] = Models\City::where('is_active', 1)->count();
    $result['sellers'] = Models\User::where('is_active', 1)->where('is_email_confirmed', 1)->where('ad_count', '>', 0)->count();
    if (isset($authUser['role_id']) && $authUser['role_id'] == \Constants\ConstUserTypes::Admin) {
        $result['ad_report'] = Models\AdReport::count();
        $result['customers'] = Models\User::where('is_active', 1)->where('is_email_confirmed', 1)->count();
    }
    return renderWithJson($result);
});
/**
 * POST AttachmentPost
 * Summary: Add attachment
 * Notes: Add attachment.
 * Output-Formats: [application/json]
 */
/*$app->POST('/api/v1/attachments', function ($request, $response, $args) {

    $args = $request->getQueryParams();
    $file = $request->getUploadedFiles();
    if (isset($file['file']) && !is_array($_FILES['file']['name'])) {
        $newfile = $file['file'];
        $type = substr(strrchr(rtrim($newfile->getClientMediaType(), '/'), '/'), 1);
        $name = md5(time()) . rand();
        if (!file_exists(APP_PATH . '/media/tmp/')) {
            mkdir(APP_PATH . '/media/tmp/', 0777, true);
        }
        if (move_uploaded_file($newfile->file, APP_PATH . '/media/tmp/' . $name . '.' . $type) === true) {
            $filename = $name . '.' . $type;
            $response = array(
                'attachment' => $filename,
                'error' => array(
                    'code' => 0,
                    'message' => ''
                )
            );
        } else {
            $response = array(
                'error' => array(
                    'code' => 1,
                    'message' => 'Photos could not be added.',
                    'fields' => ''
                )
            );
        }
        return renderWithJson($response);
    } else {
        $alloted_types = array(
            'gif',
            'jpg',
            'jpeg',
            'png',
            'swf',
            'psd',
            'wbmp'
        );
        foreach ($_FILES['file']['name'] as $f => $name) {
            $filename = md5($name);
            $max_file_size = MAX_UPLOAD_SIZE * 1000;
            if (!file_exists(APP_PATH . '/media/tmp/')) {
                mkdir(APP_PATH . '/media/tmp/');
            }
            $type = pathinfo($name, PATHINFO_EXTENSION);
            if ($_FILES['file']['error'][$f] == 4) {
                continue; // Skip file if any error found
                
            }
            if ($_FILES['file']['error'][$f] == 0) {
                if ($_FILES['file']['size'][$f] > $max_file_size) {
                    $message[] = "$name is too large!.";
                    continue; // Skip large files
                    
                } elseif (!in_array(pathinfo($name, PATHINFO_EXTENSION), $alloted_types)) {
                    $message[] = "$name is not a valid format";
                    continue; // Skip invalid file formats
                    
                } else { // No error found! Move uploaded files
                    $tmp_path = APP_PATH . '/media/tmp/' . $filename . '.' . $type;
                    if (move_uploaded_file($_FILES["file"]["tmp_name"][$f], $tmp_path)) {
                        $upload_files[] = $filename . '.' . $type;
                    }
                }
            }
        }
        $response['files'] = $upload_files;
        return renderWithJson($response);
    }
});*/
$app->POST('/api/v1/attachments', function ($request, $response, $args)
{
    $args = $request->getQueryParams();
    $response = array();
    $file = $request->getUploadedFiles();
    if (isset($file['file']) && !is_array($_FILES['file']['name'])) {
        $response = uploadFile($file);
    } else {
        return renderWithJson($response, $message = 'Attachment could not be added.', $fields = '', $isError = 1);
    }
    return renderWithJson($response);
});
/**
 * GET userNotificationsGet
 * Summary: Fetch all user notifications
 * Notes: Returns all user notifications from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_notifications', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $userNotifications = Models\UserNotification::Filter($queryParams)->paginate($count)->toArray();
        $data = $userNotifications['data'];
        unset($userNotifications['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $userNotifications
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListUserNotification'));
/**
 * GET userNotificationsUserNotificationIdGet
 * Summary: Fetch user notification
 * Notes: Returns a user notification based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_notifications/{userNotificationId}', function ($request, $response, $args) {

    global $authUser;
    $userNotification = Models\UserNotification::find($request->getAttribute('userNotificationId'));
    if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $userNotification->user_id)) {
        $result['data'] = $userNotification->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson(array(), 'Invalid user. Please, try again.', '', 1);
    }
})->add(new ACL('canViewUserNotification'));
/**
 * PUT userNotificationsUserNotificationIdPut
 * Summary: Update user notification by its id
 * Notes: Update user notification by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/user_notifications/{userNotificationId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $userNotification = Models\UserNotification::find($request->getAttribute('userNotificationId'));
    $userNotification->fill($args);
    $result = array();
    try {
        $validationErrorFields = $userNotification->validate($args);
        if (empty($validationErrorFields)) {
            $userNotification->save();
            $result = $userNotification->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'User notification could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'User notification could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateUserNotification'));
/**
 * POST userAdPackagesPost
 * Summary: Creates a new user ad package
 * Notes: Creates a new user ad package
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/order/{userAdPackageId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $payment = new Models\Payment;
    $result = array();
    $user_ad_package_id = $request->getAttribute('userAdPackageId');
    $payment->processPayment($user_ad_package_id, $args);
})->add(new ACL('canOrderUserAdPackage'));
/**
 * GET adSearchesGet
 * Summary: Fetch all ad searches
 * Notes: Returns all ad searches from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/search_keywords', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $searches = Models\SearchKeyword::Filter($queryParams)->paginate($count)->toArray();
        $data = $searches['data'];
        unset($searches['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $searches
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * GET inputTypesGet
 * Summary: Fetch all input types
 * Notes: Returns all input types from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/input_types', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $inputTypes = Models\InputType::Filter($queryParams)->paginate($count)->toArray();
        $data = $inputTypes['data'];
        unset($inputTypes['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $inputTypes
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
});
/**
 * DELETE attachment
 * Summary: Delete attachment
 * Notes: Delete attachment
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/attachments/{attachmentId}', function ($request, $response, $args) {

    $result = array();
    $attachment = Models\Attachment::where('id', $request->getAttribute('attachmentId'))->first();
    if (empty($attachment)) {
        return renderWithJson($result, 'Invalid Attachment details.', '', 1);
    } else {
        try {
            $attachment->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Attachment could not be delete. Please, try again', '', 1);
        }
    }
})->add(new ACL('canDeleteAttachment'));
/**
 * GET withdrawalStatusesGET
 * Summary: Fetch all withdrawal statuses
 * Notes: Returns all withdrawal statuses from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/withdrawal_statuses', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $withdrawalStatuses = Models\WithdrawalStatus::Filter($queryParams)->paginate(PAGE_LIMIT)->toArray();
        $data = $withdrawalStatuses['data'];
        unset($withdrawalStatuses['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $withdrawalStatuses
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListWithdrawalStatus'));
$app->GET('/api/v1/admin-config', function ($request, $response, $args) {
    $plugins = explode(',', SITE_ENABLED_PLUGINS);
    $resultSet = array();
    require_once __DIR__ . '/admin-config.php';
    if (!empty($menus)) {
        $resultSet['menus'] = $menus;
    }
    if (!empty($dashboard)) {
            if (!empty($resultSet['dashboard'])) {
                $resultSet['dashboard'] = array_merge($resultSet['dashboard'], $dashboard);
            } else {
                $resultSet['dashboard'] = $dashboard;
            }
        }
    if (!empty($tables)) {
        $resultSet['tables'] = $tables;
    }
    if (!empty($plugins)) {
        foreach ($plugins as $plugin) {
            $file = __DIR__ . '/../plugins/' . $plugin . '/admin-config.php';
            if (file_exists($file)) {
                require_once $file;
                if (!empty($resultSet['menus'])) {
                    foreach ($menus as $key => $menu) {
                        if (isset($resultSet['menus'][$key])) {
                            $resultSet['menus'][$key]['child_sub_menu'] = array_merge($resultSet['menus'][$key]['child_sub_menu'], $menu['child_sub_menu']);
                        } else {
                            $resultSet['menus'] = array_merge($resultSet['menus'], $menus);
                        }
                    }
                } else if (!empty($menus)) {
                    $resultSet['menus'] = $menus;
                }
                if (!empty($dashboard)) {
                    if (!empty($resultSet['dashboard'])) {
                        $resultSet['dashboard'] = array_merge($resultSet['dashboard'], $dashboard);
                    } else {
                        $resultSet['dashboard'] = $dashboard;
                    }
                 } 
                if (!empty($tables)) {
                    if (!empty($resultSet['tables'])) {
                        $resultSet['tables'] = array_merge($resultSet['tables'], $tables);
                    } else {
                        $resultSet['tables'] = $tables;
                    }
                }
            }
        }
    }
    uasort($resultSet['menus'], function($a, $b) { 
            return $a['order'] - $b['order'];
    });
    echo json_encode($resultSet);
    exit;
});
/**
 * GET pluginsGet
 * Summary: Fetch all plugins
 * Notes: Returns all plugins from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/plugins', function ($request, $response, $args) {

    global $authUser;
    $result = array();
    $path = SCRIPT_PATH . DIRECTORY_SEPARATOR . 'plugins';
    $directories = array();
    $directories = glob($path . '/*', GLOB_ONLYDIR);
    $available_plugin = array();
    $available_plugin_details = array();
    $pluginArray = array();
    $pluginArray['Ad'] = array();
    $pluginArray['Common'] = array();
    $adRelatedPlugins = array();
    $plugin_name = array();
    $otherlugins = array();
    $hide_plugins = array(
    );
    foreach ($directories as $key => $val) {
        $name = explode('/', $val);
        $sub_directories = glob($val . '/*', GLOB_ONLYDIR);
        if (!empty($sub_directories)) {
            foreach ($sub_directories as $sub_directory) {
                $json = file_get_contents($sub_directory . DIRECTORY_SEPARATOR . 'plugin.json');
                $data = json_decode($json, true);
                if (!in_array($data['name'], $hide_plugins)) {
                    if (!empty($data['dependencies'])) {
                        $pluginArray[$data['dependencies']][$data['name']] = $data;
                    } elseif (!in_array($data['name'], $pluginArray)) {
                        if (empty($pluginArray[$data['name']])) {
                            $pluginArray[] = $data;
                        }
                    }
                }
            }
        }
    }
    if (empty($pluginArray['Ad'])) {
        unset($pluginArray['Ad']);
    } else {
        $adsPlugins = $pluginArray['Ad'];
        unset($pluginArray['Ad']);
        foreach ($adsPlugins as $adPlugins) {
            if ($adPlugins['name'] != 'Ad') {
                $adRelatedPlugins['sub_plugins'][] = $adPlugins;
            } else {
                $adRelatedPlugins['main_plugins'][] = $adPlugins;
            }
        }
    }
    foreach ($pluginArray['Common'] as $plugin) {
        $otherlugins[] = $plugin;
    }
    $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
    $enabled_plugins = array_map('trim', $enabled_plugins);
    $result['data']['ad_plugin'] = $adRelatedPlugins;
    $result['data']['other_plugin'] = $otherlugins;
    $result['data']['enabled_plugin'] = $enabled_plugins;
    return renderWithJson($result);
})->add(new ACL('canListPlugins'));
/**
 * PUT pluginPut
 * Summary: Update plugins ny plugin name
 * Notes: Update plugins ny plugin name
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/plugins', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $site_enable_plugin = Models\Setting::where('name', 'SITE_ENABLED_PLUGINS')->first();
    $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
    if ($args['is_enabled'] === 1) {
        if (!in_array($args['plugin'], $enabled_plugins)) {
            $enabled_plugins[] = $args['plugin'];
        }
        $pluginStr = implode(',', $enabled_plugins);
        Models\Setting::where('name', 'SITE_ENABLED_PLUGINS')->update(array(
            'value' => $pluginStr
        ));
        return renderWithJson($result, 'Plugin enabled', '', 0);
    } elseif ($args['is_enabled'] === 0) {
        $key = array_search($args['plugin'], $enabled_plugins);
        if ($key !== false) {
            unset($enabled_plugins[$key]);
        }
        $pluginStr = implode(',', $enabled_plugins);
        Models\Setting::where('name', 'SITE_ENABLED_PLUGINS')->update(array(
            'value' => $pluginStr
        ));
        $scripts_path = APP_PATH . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'scripts';
        if(!is_dir($scripts_path)) {
            $scripts_path = APP_PATH . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . 'scripts';
        } 
        $list = glob($scripts_path . '/plugins*.js');
        if ($list) { 
            unlink($list[0]);
        }
        return renderWithJson($result, 'Plugin disabled', '', 0);
    } else {
        return renderWithJson($result, 'Invalid request.', '', 1);
    }
})->add(new ACL('canUpdatePlugin'));
$app->run();
