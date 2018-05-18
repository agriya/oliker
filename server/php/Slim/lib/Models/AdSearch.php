<?php
/**
 * AdSearch
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
 * AdSearch
*/
class AdSearch extends AppModel
{
    protected $table = 'ad_searches';
    protected $fillable = array(
        'keyword',
        'category_id',
        'is_search_in_description',
        'is_only_ads_with_images',
        'is_notify_whenever_new_ads_posted'
    );
    public $rules = array(
        'keyword' => 'sometimes|required'
    );
    public $qSearchFields = array(
        'keyword'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhereHas('category', function ($q) use ($params) {
            
                $q->where('name', 'like', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('user', function ($q) use ($params) {
            
                $q->where('username', 'like', '%' . $params['q'] . '%');
            });
        }
        if (!empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }
        if (!empty($params['category_id'])) {
            $query->where('ad_searches.category_id', $params['category_id']);
        }
    }
    public function category()
    {
        return $this->belongsTo('Models\Category', 'category_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function ip()
    {
        return $this->belongsTo('Models\Ip', 'ip_id', 'id');
    }
    protected static function boot()
    {
        parent::boot();
        self::created(function ($adSearch) {
        
            if (!empty($adSearch->user_id)) {
                User::find($adSearch->user_id)->increment('ad_search_count', 1);
            }
        });
        self::deleted(function ($adSearch) {
        
            if (!empty($adSearch->user_id)) {
                User::find($adSearch->user_id)->decrement('ad_search_count', 1);
            }
        });
    }
}
