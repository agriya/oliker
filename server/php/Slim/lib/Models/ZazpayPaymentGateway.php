<?php
/**
 * ZazPaymentGateway
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

class ZazpayPaymentGateway extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zazpay_payment_gateways';
    public function zazpay_group()
    {
        return $this->belongsTo('Models\ZazpayPaymentGroup', 'zazpay_payment_group_id', 'id');
    }
}
