<?php
/**
 * Provider
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

class Provider extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'providers';
    protected $fillable = array(
        'name',
        'secret_key',
        'api_key',
        'is_active'
    );
    public $rules = array(
        'name' => 'sometimes|required'
    );
    public $qSearchFields = array(
        'name'
    );
}
