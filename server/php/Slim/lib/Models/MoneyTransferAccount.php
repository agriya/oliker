<?php
/**
 * MoneyTransferAccount
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

class MoneyTransferAccount extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'money_transfer_accounts';
    protected $fillable = array(
        'user_id',
        'account',
        'is_active',
        'is_primary'
    );
    public $rules = array(
        'account' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id')->select('id', 'username', 'email');
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        static ::addGlobalScope('user', function (\Illuminate\Database\Eloquent\Builder $builder) use ($authUser) {
        
            if ($authUser['role_id'] != \Constants\ConstUserTypes::Admin) {
                $builder->where('user_id', $authUser['id']);
            }
        });
        self::saving(function ($data) use ($authUser) {
        
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
        self::deleting(function ($data) use ($authUser) {
        
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
    }
    public function scopeFilter($query, $params = array())
    {
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
            
                $q1->where('account', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('user', function ($q) use ($params) {
                
                    $q->where('username', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
        if (!empty($params['is_primary'])) {
            $query->where('is_primary', $params['is_primary']);
        }
        parent::scopeFilter($query, $params);
    }
}
