<?php
/**
 * AdFavorite
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
 * AdFavorite
*/
class AdFavorite extends AppModel
{
    protected $table = 'ad_favorites';
    protected $fillable = array(
        'ad_id'
    );
    public $rules = array(
        'adId' => 'sometimes|required',
    );
    public function ad()
    {
        return $this->belongsTo('Models\Ad', 'ad_id', 'id')->with('attachment', 'ad_owner');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function ip()
    {
        return $this->belongsTo('Models\Ip', 'ip_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhereHas('user', function ($q) use ($params) {
            
                $q->where('username', 'like', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('ad', function ($q) use ($params) {
            
                $q->where('title', 'like', '%' . $params['q'] . '%');
            });
        }
        if ($authUser['role_id'] != \Constants\ConstUserTypes::Admin) {
            $query->where('ad_favorites.user_id', $authUser['id']);
        }
        if (!empty($params['user_id'])) {
            $query->where('ad_favorites.user_id', $params['user_id']);
        }
        if (!empty($params['ad_id'])) {
            $query->where('ad_favorites.ad_id', $params['ad_id']);
        }
    }
    protected static function boot()
    {
        parent::boot();
        self::created(function ($adFavorite) {
        
            Ad::find($adFavorite->ad_id)->increment('ad_favorite_count', 1);
            if (!empty($adFavorite->user_id)) {
                User::find($adFavorite->user_id)->increment('ad_favorite_count', 1);
            }
        });
        self::deleted(function ($adFavorite) {
        
            if (!empty($adFavorite->user_id)) {
                User::find($adFavorite->user_id)->decrement('ad_favorite_count', 1);
            }
            Ad::find($adFavorite->ad_id)->decrement('ad_favorite_count', 1);
        });
    }
}
