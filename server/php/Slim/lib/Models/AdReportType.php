<?php
/**
 * AdReportType
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
 * AdReportType
*/
class AdReportType extends AppModel
{
    protected $table = 'ad_report_types';
    protected $fillable = array(
        'name'
    );
    public $rules = array(
        'name' => 'sometimes|required',
    );
    public $qSearchFields = array(
        'name'
    );
}
