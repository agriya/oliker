<?php
/**
 * AdSearch
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
 * AdSearch
*/
class SearchKeyword extends AppModel
{
    protected $table = 'search_keywords';
    public $rules = array(
        'keyword' => 'sometimes|required'
    );
    public $qSearchFields = array(
        'keyword'
    );
}
