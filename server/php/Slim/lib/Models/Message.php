<?php
/**
 * Message
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
 * Message
*/
class Message extends AppModel
{
    protected $table = 'messages';
    protected $fillable = array(
        'other_user_id',
        'ad_id',
        'message',
        'subject',
        'is_read',
        'is_archived',
        'image'
    );
    public $rules = array(
        'otherUserId' => 'sometimes|required',
        'adId' => 'sometimes|required',
        'message' => 'sometimes|required',
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id')->with('attachment')->select('id', 'username', 'email');
    }
    public function other_user()
    {
        return $this->belongsTo('Models\User', 'other_user_id', 'id')->with('attachment')->select('id', 'username', 'email');
    }
    public function ad()
    {
        return $this->belongsTo('Models\Ad', 'ad_id', 'id')->with('attachment')->select('id', 'title', 'slug');
    }
    public function message_content()
    {
        return $this->belongsTo('Models\MessageContent', 'message_content_id', 'id');
    }
    public function attachment()
    {
        return $this->hasMany('Models\Attachment', 'foreign_id', 'message_content_id')->where('class', 'Message');
    }
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {

            $query->Where(function ($query) use ($params) {
                
                $query->orWhereHas('user', function ($q) use ($params) {
                
                    $q->where('users.username', 'like', '%' . $params['q'] . '%');
                });
                $query->orWhereHas('other_user', function ($q) use ($params) {
                
                    $q->where('users.username', 'like', '%' . $params['q'] . '%');
                });
                $query->orWhereHas('ad', function ($q) use ($params) {
                
                    $q->where('ads.title', 'like', '%' . $params['q'] . '%');
                });
                $query->orWhereHas('message_content', function ($q) use ($params) {
                
                    $q->where('message_contents.message', 'like', '%' . $params['q'] . '%');
                });
            });
        }
        if (!empty($params['type']) && ($params['type'] == 'inbox')) {
            $query->where('messages.other_user_id', $authUser['id']);
            $query->where('messages.is_sender', 0);
        } elseif (!empty($params['type']) && ($params['type'] == 'sent')) {
            $query->where('messages.user_id', $authUser['id']);
            $query->where('messages.is_sender', 1);
        }
        if (!empty($params['user_id'])) {
            $query->where('messages.user_id', $params['user_id']);
        }
        if (!empty($params['other_user_id'])) {
            $query->where('messages.other_user_id', $params['other_user_id']);
        }
        if (!empty($params['is_sender'])) {
            $query->where('messages.is_sender', $params['is_sender']);
        }
    }
}
