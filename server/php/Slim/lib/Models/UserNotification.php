<?php
/**
 * UserNotification
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
 * UserNotification
*/
class UserNotification extends AppModel
{
    protected $table = 'user_notifications';
    protected $fillable = array(
        'is_new_tips_notification_to_sms',
        'is_new_tips_notification_to_email',
        'is_new_messages_received_notification_to_sms',
        'is_new_messages_received_notification_to_email',
        'is_new_ads_on_saved_searches_to_sms',
        'is_new_ads_on_saved_searches_to_email',
        'is_price_reduced_on_favorite_ads_to_sms',
        'is_price_reduced_on_favorite_ads_to_email'
    );
    public $rules = array(
        'isNewTipsNotificationToSms' => 'sometimes|required',
        'isNewTipsNotificationToEmail' => 'sometimes|required',
        'isNewMessagesReceivedNotificationToSms' => 'sometimes|required',
        'isNewMessagesReceivedNotificationToEmail' => 'sometimes|required',
        'isNewAdsOnSavedSearchesToSms' => 'sometimes|required',
        'isNewAdsOnSavedSearchesToEmail' => 'sometimes|required',
        'isPriceReducedOnFavoriteAdsToSms' => 'sometimes|required',
        'isPriceReducedOnFavoriteAdsToEmail' => 'sometimes|required',
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if ($authUser['role_id'] != \Constants\ConstUserTypes::Admin) {
            $query->where('user_id', $authUser['id']);
        }
    }
}
