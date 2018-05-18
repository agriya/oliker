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
 * GET user cash withdrawals GET.
 * Summary: Filter  user cash withdrawals.
 * Notes: Filter user cash withdrawals.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_cash_withdrawals', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }         
        $userCashWithdrawals = Models\UserCashWithdrawals::leftJoin('users', 'user_cash_withdrawals.user_id', '=', 'users.id');
        $userCashWithdrawals = $userCashWithdrawals->leftJoin('withdrawal_statuses', 'user_cash_withdrawals.withdrawal_status_id', '=', 'withdrawal_statuses.id');
        $userCashWithdrawals = $userCashWithdrawals->select('user_cash_withdrawals.*', 'users.username as user_username', 'withdrawal_statuses.name as withdrawal_status_name');
        $userCashWithdrawals = $userCashWithdrawals->with('user', 'money_transfer_account', 'withdrawal_status')->Filter($queryParams)->paginate($count)->toArray();        
        $data = $userCashWithdrawals['data'];
        unset($userCashWithdrawals['data']);         
        $result = array(
            'data' => $data,
            '_metadata' => $userCashWithdrawals
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canUserListUserCashWithdrawals'));
/**
 * GET useruserIdUserCashWithdrawalsGet
 * Summary: Get user cash withdrawals
 * Notes: Get ruser cash withdrawals
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}/user_cash_withdrawals', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $count = PAGE_LIMIT;
        if (!empty($queryParams['limit'])) {
            $count = $queryParams['limit'];
        }
        $userCashWithdrawals = Models\UserCashWithdrawals::with('user', 'money_transfer_account', 'withdrawal_status')->where('user_id', $request->getAttribute('userId'))->Filter($queryParams)->paginate($count)->toArray();
        $data = $userCashWithdrawals['data'];
        unset($userCashWithdrawals['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $userCashWithdrawals
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canUserListUserCashWithdrawals'));
/**
 * POST userUserIdUserCashWithdrawals.
 * Summary: Create user cash withdrawals.
 * Notes: Create user cash withdrawals.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/{userId}/user_cash_withdrawals', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $userCashWithdrawal = new Models\UserCashWithdrawals;
    $validationErrorFields = $userCashWithdrawal->validate($args);
    if (empty($validationErrorFields)) {
        foreach ($args as $key => $arg) {
            if (!is_array($arg)) {
                $userCashWithdrawal->{$key} = $arg;
            }
        }
        $userCashWithdrawal->user_id = $request->getAttribute('userId');
        $userCashWithdrawal->withdrawal_status_id = \Constants\UserCashWithdrawStatus::Pending;
       try {
            $userCashWithdrawal->save();
            //Transaction process
            $adminId = Models\User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
            Models\Transaction::insertTransaction( $adminId['id'], $userCashWithdrawal->user_id, $userCashWithdrawal->id, 'UserCashWithdrawals', '', $userCashWithdrawal->amount, 'WithdrawRequested');            
            $result = $userCashWithdrawal->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'User cash withdrawals could not be added. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'User cash withdrawals could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUserCreateUserCashWithdrawals'));
/**
 * GET useruserIdUserCashWithdrawalsUserCashWithdrawalsIdGet
 * Summary: Get paticular user cash withdrawals
 * Notes:  Get paticular user cash withdrawals
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}/user_cash_withdrawals/{userCashWithdrawalsId}', function ($request, $response, $args) {

    $userCashWithdrawal = Models\UserCashWithdrawals::find($request->getAttribute('userCashWithdrawalsId'));
    $result = array();
    if (!empty($userCashWithdrawal)) {
        $result['data'] = $userCashWithdrawal->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new ACL('canUserViewUserCashWithdrawals'));
/**
 * PUT usersUserIdUserCashWithdrawalsUserCashWithdrawalsIdPut
 * Summary: Update  user cash withdrawals.
 * Notes: Update user cash withdrawals.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}/user_cash_withdrawals/{userCashWithdrawalsId}', function ($request, $response, $args) {

    $body = $request->getParsedBody();
    $result = array();
    $userCashWithdrawal = Models\UserCashWithdrawals::with('user')->where('id', $request->getAttribute('userCashWithdrawalsId'))->first();
    $old_status = $userCashWithdrawal;
       if (empty($validationErrorFields)) {
        if (!empty($userCashWithdrawal)) {
            foreach ($body as $key => $arg) {
                if (!is_array($arg)) {
                    $userCashWithdrawal->{$key} = $arg;
                }
            }
            $userCashWithdrawal->save();   
            if($userCashWithdrawal->save()) {
                $adminId = Models\User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
                if (isset($body['withdrawal_status_id']) && ($body['withdrawal_status_id'] == \Constants\UserCashWithdrawStatus::Approved) && ($old_status['withdrawal_status_id'] == \Constants\UserCashWithdrawStatus::Pending)) {
                    Models\Transaction::insertTransaction($adminId['id'], $userCashWithdrawal->user_id, $userCashWithdrawal->id, 'UserCashWithdrawal', $userCashWithdrawal->amount, 'WithdrawRequestApproved');
                } elseif (isset($body['withdrawal_status_id']) && ($body['withdrawal_status_id'] == \Constants\UserCashWithdrawStatus::Rejected) && ($old_status['withdrawal_status_id'] == \Constants\UserCashWithdrawStatus::Pending)) {
                    Models\Transaction::insertTransaction($adminId['id'], $userCashWithdrawal->user_id, $userCashWithdrawal->id, 'UserCashWithdrawal', $userCashWithdrawal->amount, 'WithdrawRequestRejected');
                }   
            } 
            $emailFindReplace = array(
                '##USERNAME##' => $userCashWithdrawal['user']['username']
            );
            sendMail('adminpaidyourwithdrawalrequest', $emailFindReplace, $userCashWithdrawal['user']['email']);
            $result['data'] = $userCashWithdrawal->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'User cash withdrawals could not be updated. Please, try again.', '', 1);
        }
    } else {
        return renderWithJson($result, 'User cash withdrawals could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new ACL('canUpdateUserCashWithdrawals'));
/**
 * GET useruserIdUserCashWithdrawalsUserCashWithdrawalsIdGet
 * Summary: Get paticular user cash withdrawals
 * Notes:  Get paticular user cash withdrawals
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_cash_withdrawals/{userCashWithdrawalsId}', function ($request, $response, $args) {

    global $authUser;
    $result = array();
    $userCashWithdrawal = Models\UserCashWithdrawals::with('user', 'money_transfer_account', 'withdrawal_status')->find($request->getAttribute('userCashWithdrawalsId'));
    if (empty($userCashWithdrawal) || ($authUser->role_id != \Constants\ConstUserTypes::Admin)) {
        return renderWithJson($result, 'Invalid user. Please, try again.', '', 1);
    } else {
        $result['data'] = $userCashWithdrawal->toArray();
        return renderWithJson($result);
    }
})->add(new ACL('canViewUserCashWithdrawals'));
/**
 * PUT usersUserIdUserCashWithdrawalsUserCashWithdrawalsIdPut
 * Summary: Update  user cash withdrawals.
 * Notes: Update user cash withdrawals.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/user_cash_withdrawals/{userCashWithdrawalsId}', function ($request, $response, $args) {

    global $authUser;
    $body = $request->getParsedBody();
    $result = array();
    $userCashWithdrawal = Models\UserCashWithdrawals::with('user')->where('id', $request->getAttribute('userCashWithdrawalsId'))->first();
    if (empty($userCashWithdrawal) || ($authUser->role_id != \Constants\ConstUserTypes::Admin)) {
        return renderWithJson($result, 'Invalid user. Please, try again.', '', 1);
    } else {
        if (empty($validationErrorFields)) {
            if (!empty($userCashWithdrawal)) {
                foreach ($body as $key => $arg) {
                    if (!is_object($arg) && !is_array($arg)) {
                        $userCashWithdrawal->{$key} = $arg;
                    }
                }
                $userCashWithdrawal->save();
                $emailFindReplace = array(
                    '##USERNAME##' => $userCashWithdrawal['user']['username'],
                    '##RESTAURANT_NAME##' => $userCashWithdrawal['restaurant']['name']
                );
                sendMail('adminpaidyourwithdrawalrequest', $emailFindReplace, $userCashWithdrawal['user']['email']);
                $result['data'] = $userCashWithdrawal->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'User cash withdrawals could not be updated. Please, try again.', '', 1);
            }
        } else {
            return renderWithJson($result, 'User cash withdrawals could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    }
})->add(new ACL('canUpdateUserCashWithdrawals'));
/**
 * DELETE userCashWithdrawalsUserCashWithdrawalIdDelete
 * Summary: Delete user cash withdrawal
 * Notes: Deletes a single user cash withdrawal based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/user_cash_withdrawals/{userCashWithdrawalId}', function($request, $response, $args) {
	$userCashWithdrawal = Models\UserCashWithdrawals::find($request->getAttribute('userCashWithdrawalId'));
	$result = array();
	try {
		if (!empty($userCashWithdrawal)) {
			$userCashWithdrawal->delete();
			$result = array(
				'status' => 'success',
			);
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'No record found', '', 1);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'User cash withdrawal could not be deleted. Please, try again.', '', 1);
	}
})->add(new ACL('canDeleteUserCashWithdrawal'));
