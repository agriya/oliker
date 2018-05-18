<?php
/**
 * Constants configurations
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
namespace Constants;

class ConstUserTypes
{
    const Admin = 1;
    const User = 2;
}
class UserCashWithdrawStatus
{
    const Pending = 1;
    const Approved = 2;
    const Rejected = 3;
}
class ConstSocialLogins
{
    const Facebook = 1;
    const Twitter = 2;
    const GooglePlus = 3;
}
class PaymentGateways
{
    const ZazPay = 1;
    const Wallet = 2;
    const Credits = 3;
    const Paypal = 4;
}
class TransactionKeys
{
    const AdPackage = 'AdPackage';
    const Wallet = 'Wallet';
}
class ConstTransactionTypes
{
    const AddedToWallet = 1;
    const AddedPackage = 2;
}
class ConstAdExtra
{
    const TopAd = 1;
    const Highlight = 2;
    const Urgent = 3;
    const InTop = 4;
}
class TransactionType
{
    const AmountAddedToWallet = 'AmountAddedToWallet';
    const AmountAddedToAd = 'AmountAddedToAd';
    const AmountAddedToUserAdExtra = 'AmountAddedToUserAdExtra';
    const AmountAddedToUserAdPackage = 'AmountAddedToUserAdPackage';
}