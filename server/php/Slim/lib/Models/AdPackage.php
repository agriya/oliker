<?php
/**
 * AdPackage
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
 * AdPackage
*/
class AdPackage extends AppModel
{
    protected $table = 'ad_packages';
    protected $fillable = array(
        'category_id',
        'name',
        'validity_days',
        'amount',
        'additional_ads_allowed',
        'is_unlimited_ads',
        'credit_points',
        'points_valid_days',
        'is_active'
    );
    public $rules = array(
        'categoryId' => 'sometimes|required',
        'name' => 'sometimes|required',
        'validityDays' => 'sometimes|required',
        'amount' => 'sometimes|required',
        'additionalAdsAllowed' => 'sometimes|required',
        'isUnlimitedAds' => 'sometimes|required',
        'creditPoints' => 'sometimes|required',
        'pointsValidDays' => 'sometimes|required',
        'isActive' => 'sometimes|required',
    );
    public function category()
    {
        return $this->belongsTo('Models\Category', 'category_id', 'id');
    }
    public function user_ad_package()
    {
        global $authUser;
        $now = date('Y-m-d h:i:s');
        return $this->belongsTo('Models\UserAdPackage', 'id', 'ad_package_id')->where('expiry_date', '>', $now)->where('user_id', $authUser['id']);
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where('ad_packages.name', 'ilike', '%' . $params['q'] . '%');
            $query->orWhereHas('category', function ($q) use ($params) {
            
                $q->where('name', 'ilike', '%' . $params['q'] . '%');
            });
        }
        if (!empty($params['category_id'])) {
            $query->orWhereHas('category', function ($q) use ($params) {
            
                $q->where('id', $params['category_id']);
            });
        }
    }
}
