<?php
/**
 * EmailTemplate
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

class EmailTemplate extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates';
    protected $fillable = array(
        'from',
        'reply_to',
        'subject',
        'html_email_content',
        'text_email_content'
    );
    public $rules = array();
    public $qSearchFields = array(
        'name',
        'display_name',
        'description',
        'subject',
        'from',
        'html_email_content'
    );
}
