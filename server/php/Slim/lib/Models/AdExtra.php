<?php
/**
 * AdExtra
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
 * AdExtra
*/
class AdExtra extends AppModel
{
    protected $table = 'ad_extras';
    protected $fillable = array(
        'name',
        'is_active'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'isActive' => 'sometimes|required',
    );
    public $qSearchFields = array(
        'name'
    );
}
