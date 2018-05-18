<?php
/**
 * AdView
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
class AdView extends AppModel
{
    protected $table = 'ad_views';
    public function ad()
    {
        return $this->hasOne('Models\Ad', 'id', 'ad_id');
    }
    public function user()
    {
        return $this->hasOne('Models\User', 'id', 'user_id');
    }
    public function ip()
    {
        return $this->hasOne('Models\Ip', 'id', 'ip_id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhereHas('user', function ($q) use ($params) {
            
                $q->where('username', 'like', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('ad', function ($q) use ($params) {
            
                $q->where('title', 'like', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('ip', function ($q) use ($params) {
            
                $q->where('ip', 'ilike', '%' . $params['q'] . '%');
            });            
        }
        if (!empty($params['ad_id'])) {
            $query->where('ad_views.ad_id', $params['ad_id']);
        }
        if (!empty($params['user_id'])) {
            $query->where('ad_views.user_id', $params['user_id']);
        }
    }
}
