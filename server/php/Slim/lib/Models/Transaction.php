<?php
/**
 * Transaction
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

use Illuminate\Database\Eloquent\Relations\Relation;

class Transaction extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function to_user()
    {
        return $this->belongsTo('Models\User', 'to_user_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function foreign_transaction()
    {
        return $this->morphTo(null, 'class', 'foreign_id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where('transaction_type', 'ilike', '%' . $params['q'] . '%');
            $query->orWhereHas('user', function ($q) use ($params) {
            
                $q->where('username', 'like', '%' . $params['q'] . '%');
            });
        }
    }
    protected static function boot()
    {
        Relation::morphMap(['Ad' => Ad::class , 'UserAdPackage' => UserAdPackage::class , 'Wallet' => Wallet::class , 'UserAdExtra' => UserAdExtra::class , ]);
    }
    public function insertTransaction($user_id, $to_user_id, $foreign_id, $class, $payment_gateway_id, $amount, $transaction_type)
    { 
        $transaction =new Transaction;
        $transaction->user_id = $user_id;
        $transaction->to_user_id = $to_user_id;
        $transaction->foreign_id = $foreign_id;
        $transaction->class = $class;
        $transaction->type = $transaction_type;
        if(!empty($payment_gateway_id)) {
            $transaction->payment_gateway_id = $payment_gateway_id;
        }
        $transaction->amount = $amount;
        $transaction->save();
    }    
}
