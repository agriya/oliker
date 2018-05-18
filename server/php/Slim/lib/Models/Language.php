<?php
/**
 * Language
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

class Language extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';
    protected $fillable = array(
        'name',
        'iso2',
        'iso3',
        'is_active'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'iso2' => 'sometimes|required|max:2',
        'iso3' => 'sometimes|required|max:3'
    );
    public $qSearchFields = array(
        'name',
        'iso2',
        'iso3'
    );
}
