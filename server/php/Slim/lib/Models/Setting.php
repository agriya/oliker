<?php
/**
 * Setting
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

class Setting extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';
    protected $fillable = array(
        'name',
        'value'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['setting_category_id'])) {
            $query->where('setting_category_id', $params['setting_category_id']);
        }
    }
    public function setting_category()
    {
        return $this->belongsTo('Models\SettingCategory', 'setting_category_id', 'id');
    }
}
