<?php
/**
 * City
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

class City extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';
    protected $fillable = array(
        'country_id',
        'state_id',
        'name',
        'city_code',
        'is_active'
    );
    public $rules = array(
        'name' => 'sometimes|required',
    );
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id')->select('id', 'name');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id')->select('id', 'iso_alpha2', 'name');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
            
                $q1->orWhereHas('country', function ($q) use ($params) {
                
                    $q->where('countries.name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('state', function ($q) use ($params) {
                
                    $q->Where('states.name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhere('cities.name', 'ilike', '%' . $params['q'] . '%');
            });
        }
    }
}
