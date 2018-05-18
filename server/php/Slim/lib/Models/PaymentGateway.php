<?php
/**
 * PaymentGateway
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

class PaymentGateway extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateways';
    protected $fillable = array(
        'name',
        'display_name',
        'description',
        'gateway_fees',
        'is_test_mode',
        'is_active',
        'is_enable_for_wallet'
    );
    public function payment_settings()
    {
        return $this->hasMany('Models\PaymentGatewaySetting');
    }
}
