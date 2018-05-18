<?php
/**
 * AdFormField
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

class AdFormField extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ad_form_fields';
    public function form_field()
    {
        return $this->hasMany('Models\FormField', 'id', 'form_field_id')->with('input_types');
    }
    public function ad()
    {
        return $this->belongsTo('Models\Ad', 'ad_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhereHas('ad', function ($q) use ($params) {
            
                $q->where('title', 'like', '%' . $params['q'] . '%');
            });
        }
        if (!empty($params['category_id'])) {
            $query->orWhereHas('form_field', function ($q) use ($params) {
            
                $q->where('category_id', $params['category_id']);
            });
        }
    }
}
