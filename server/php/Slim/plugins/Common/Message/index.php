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
 * GET messagesGet
 * Summary: Fetch all messages
 * Notes: Returns all messages from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/messages', function ($request, $response, $args) {

    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $messages = Models\Message::leftJoin('users', 'messages.user_id', '=', 'users.id');
        $messages = $messages->leftJoin('ads', 'messages.ad_id', '=', 'ads.id');
        $messages = $messages->leftJoin('message_contents', 'messages.message_content_id', '=', 'message_contents.id');
        $messages = $messages->select('messages.*', 'users.username as user_username', 'users.username as other_user_username', 'message_contents.message as message_content_message', 'ads.title as ad_title');
        if ($authUser['role_id'] == \Constants\ConstUserTypes::Admin && empty($params['type'])) {
            $messages = $messages->where('messages.is_sender', 1);
        }
        $messages = $messages->with('attachment', 'message_content', 'user', 'other_user', 'ad')->Filter($queryParams)->paginate($count)->toArray();
        $data = $messages['data'];
        unset($messages['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $messages
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListMessage'));
/**
 * DELETE messagesMessageIdDelete
 * Summary: Delete message
 * Notes: Deletes a single message based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/messages/{messageId}', function ($request, $response, $args) {

    $message = Models\Message::find($request->getAttribute('messageId'));
    try {
        $message->delete($message->other_user_id);
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Message could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteMessage'));
/**
 * GET messagesMessageIdGet
 * Summary: Fetch message
 * Notes: Returns a message based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/messages/{messageId}', function ($request, $response, $args) {

    $result = array();
    $message = Models\Message::with('attachment', 'message_content', 'user', 'other_user', 'ad')->find($request->getAttribute('messageId'));
    if (!empty($message)) {
        $result['data'] = $message->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
})->add(new ACL('canViewMessage'));
/**
 * PUT messagesMessageIdPut
 * Summary: Update message by its id
 * Notes: Update message by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/messages/{messageId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $message = Models\Message::find($request->getAttribute('messageId'));
    if (!empty($args['ad_id']) && $args['ad_id'] == 0) {
        $args['ad_id'] = $message->ad_id;
    }
    foreach ($args as $key => $arg) {
        if (!is_array($arg) && $key != 'image'  && $key !='from_ad_post_view') {
            $message->{$key} = $arg;
        }
    }
    $result = array();
    try {
        unset($message->message);
        unset($message->subject);
        $validationErrorFields = $message->validate($args);
        if (empty($validationErrorFields)) {
            $message->save();
            $messageContent = Models\MessageContent::where('id', $message->message_content_id)->first();
            if (!empty($args['message'])) {
                $messageContent->message = $args['message'];
            } elseif (!empty($args['subject'])) {
                $messageContent->subject = $args['subject'];
            }
            $messageContent->save();
            $result = $message->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Message could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Message could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateMessage'));
/**
 * POST messagesPost
 * Summary: Creates a new message
 * Notes: Creates a new message
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/messages', function ($request, $response, $args) {

    global $authUser, $_server_domain_url;
    $args = $request->getParsedBody();
    $messageContent = new Models\MessageContent;
    $messageContent->message = $args['message'];
    $messageContent->subject = $args['subject'];
    $messageContent->save();
    unset($args['subject']);
    $senderMessage = new Models\Message;
    foreach ($args as $key => $arg) {
        if ($key != 'image' && $key != 'message' && $key !='from_ad_post_view') {
            if (!is_array($arg)) {
                $senderMessage->{$key} = $arg;
                $senderMessage->message_content_id = $messageContent->id;
                $senderMessage->user_id = $authUser['id'];
                $senderMessage->is_sender = 1;
                $senderMessage->is_read = 1;
            }
        }
    }
    $receiverMessage = new Models\Message;
    foreach ($args as $key => $arg) {
        if ($key != 'image' && $key != 'message' && $key !='from_ad_post_view') {
            if (!is_array($arg)) {
                $receiverMessage->{$key} = $arg;
                $receiverMessage->message_content_id = $messageContent->id;
                $receiverMessage->user_id = $authUser['id'];
                $receiverMessage->is_sender = 0;
                $receiverMessage->is_read = 0;
            }
        }
    }
    $result = array();
    try {
        $validationErrorFields = $senderMessage->validate($args);
        if (empty($validationErrorFields)) {
            $senderMessage->save();
            $receiverMessage->save();
            if (!empty($args['image']) && is_array($args['image'])) {
                $is_multi = 1;
                foreach ($args['image'] as $image) {
                    if ((!empty($image)) && (file_exists(APP_PATH . '/media/tmp/' . $image))) {
                        saveImage('Message', $image, $messageContent->id, $is_multi);
                    }
                }
            } elseif (!empty($args['image'])) {
                saveImage('Message', $args['image'], $messageContent->id);
            }
            if (!empty($authUser['id'] && $senderMessage->is_read == 1)) {
                Models\User::find($senderMessage->user_id)->increment('message_count', 1);
            }
            if (!empty($senderMessage->ad_id || $receiverMessage->ad_id)) {
                $ad = Models\Ad::find($receiverMessage->ad_id);
                if (!empty($ad)) {
                    $ad->increment('message_count', 1);
                }
            }
            if (!empty($args['other_user_id'])) {
                $user = Models\User::with('user_notification')->find($args['other_user_id'])->toArray();
                $flag = ($user['user_notification']['is_new_messages_received_notification_to_email'] == 1) ? true : false;
                if(!empty($args['from_ad_post_view']) && $args['from_ad_post_view'] == 'ad_view' && $flag){
                    $flag = ($ad['is_send_email_when_user_contact']) ? true : false;
                }
                 if ($flag) {
                    $to_mail = $user['email'];
                    $toUserDetails = Models\User::select('username')->find($authUser['id'])->toArray();
                    $emailFindReplace = array(
                        '##OTHER_USER##' => $toUserDetails['username'],
                        '##USERNAME##' => $user['username'],
                        '##MESSAGE_URL##' => $_server_domain_url . '/#!/message/' . $senderMessage->id,
                        '##SUPPORT_EMAIL##' => SUPPORT_EMAIL
                    );
                    sendMail('messagereceived', $emailFindReplace, $to_mail);
                }
            }
            $message = new Models\Message;
            $message = Models\Message::with('attachment')->find($messageContent->id);
            $message = Models\Message::with('attachment', 'message_content', 'user', 'other_user')->find($senderMessage->id);
            $result = $message->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Message could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Message could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateMessage'));
