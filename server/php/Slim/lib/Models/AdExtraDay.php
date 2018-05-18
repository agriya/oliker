<?php
/**
 * AdExtraDay
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
 * AdExtraDay
*/
class AdExtraDay extends AppModel
{
    protected $table = 'ad_extra_days';
    protected $fillable = array(
        'ad_extra_id',
        'category_id',
        'days',
        'amount',
        'is_active'
    );
    public $rules = array(
        'adExtraId' => 'sometimes|required',
        'categoryId' => 'sometimes|required',
        'days' => 'sometimes|required|integer|min:0',
        'amount' => 'sometimes|required|numeric|min:0',
        'isActive' => 'sometimes|required',
    );
    public function ad_extra()
    {
        return $this->belongsTo('Models\AdExtra', 'ad_extra_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo('Models\Category', 'category_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhereHas('ad_extra', function ($q) use ($params) {
            
                $q->where('name', 'ilike', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('category', function ($q) use ($params) {
            
                $q->where('name', 'ilike', '%' . $params['q'] . '%');
            });
        }
        if (!empty($params['category_id'])) {
            $query->where('category_id', $params['category_id']);
        }
    }
    protected static function boot()
    {
        parent::boot();
        self::created(function ($adExtraDay) {
        
            Category::find($adExtraDay->category_id)->increment('ad_extra_day_count', 1);
        });
    }
}
