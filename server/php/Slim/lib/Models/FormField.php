<?php
/**
 * FormField
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
class FormField extends AppModel
{
    protected $table = 'form_fields';
    protected $fillable = array(
        'name',
        'display_name',
        'label',
        'input_type_id',
        'info',
        'is_required',
        'depends_on',
        'depend_value',
        'display_order',
        'is_active',
        'options'
    );
    public function input_types()
    {
        return $this->belongsTo('Models\InputType', 'input_type_id', 'id');
    }
}
