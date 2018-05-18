<?php
/**
 * AdReport
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
 * AdReport
*/
class AdReport extends AppModel
{
    protected $table = 'ad_reports';
    protected $fillable = array(
        'ad_id',
        'ad_report_type_id',
        'message'
    );
    public $rules = array(
        'ad_id' => 'required',
        'ad_report_type_id' => 'required',
        'message' => 'required',
    );
    public $qSearchFields = array(
        'message'
    );
    public function ad()
    {
        return $this->belongsTo('Models\Ad', 'ad_id', 'id');
    }
    public function ad_report_type()
    {
        return $this->belongsTo('Models\AdReportType', 'ad_report_type_id', 'id');
    }
    public function ip()
    {
        return $this->belongsTo('Models\Ip', 'ip_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhereHas('ad', function ($q) use ($params) {
            
                $q->where('title', 'ilike', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('ad_report_type', function ($q) use ($params) {
            
                $q->where('name', 'ilike', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('user', function ($q) use ($params) {
            
                $q->where('username', 'ilike', '%' . $params['q'] . '%');
            });
        }
        if (!empty($params['user_id'])) {
            $query->where('ad_reports.user_id', $params['user_id']);
        }
        if (!empty($params['ad_id'])) {
            $query->where('ad_reports.ad_id', $params['ad_id']);
        }
    }
}
