<?php
/**
 * Country
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

class Country extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';
    protected $fillable = array(
        'iso_alpha2',
        'iso_alpha3',
        'iso_numeric',
        'fips_code',
        'name',
        'capital',
        'areainsqkm',
        'population',
        'continent',
        'tld',
        'currency',
        'currencyname',
        'phone',
        'postalcodeformat',
        'postalcoderegex',
        'languages',
        'geonameid',
        'neighbours',
        'equivalentfipscode'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'fips104' => 'sometimes|max:2',
        'iso2' => 'sometimes|max:2',
        'iso3' => 'sometimes|max:3',
        'ison' => 'sometimes|max:4',
        'internet' => 'sometimes|max:2',
        'capital' => 'sometimes|alpha',
        'currency_code' => 'sometimes|max:3',
        'continent' => 'sometimes|max:2',
        'tld' => 'sometimes|max:3',
        'currency' => 'sometimes|max:3'
    );
    public $qSearchFields = array(
        'name',
        'iso_alpha2',
        'iso_alpha3'
    );
}
