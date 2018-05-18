<?php
/**
 * UserAdPackage
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
 * UserAdPackage
*/
class UserAdPackage extends AppModel
{
    protected $table = 'user_ad_packages';
    protected $fillable = array(
        'ad_package_id',
        'payment_gateway_id'
    );
    public $rules = array(
        'adPackageId' => 'sometimes|required',
        'allowedAdCount' => 'sometimes|required',
        'points' => 'sometimes|required',
        'amount' => 'sometimes|required',
        'paymentGatewayId' => 'sometimes|required',
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function ad_package()
    {
        return $this->belongsTo('Models\AdPackage', 'ad_package_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function foreign_transactions()
    {
        return $this->morphMany('Models\Transaction', 'foreign_transaction');
    }
    public function processCaptured($payment_response, $id)
    {
        $userAdPackage = UserAdPackage::where('id', $id)->where('is_payment_completed', false)->first();
        if (!empty($userAdPackage)) {
            $userAdPackage->payment_gateway_id = $payment_response['payment_gateway_id'];
            $userAdPackage->is_payment_completed = 1;
            $userAdPackage->update();
            //Transaction 
            $adminId = User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
            Transaction::insertTransaction($userAdPackage->user_id, $adminId['id'], $userAdPackage->id, 'UserAdPackage', $payment_response['payment_gateway_id'], $userAdPackage->amount,'AdPackageFee');            
            $user = User::where('id', $userAdPackage->user_id)->first();
            // ToDO: ad_count is correct ?
            $user->ad_count = $user->ad_count + $userAdPackage->allowed_ad_count;
            // update available points in users table
            if (!empty($userAdPackage->points)) {
                $user->available_points = $user->available_points + $userAdPackage->points;
            }
            $user->save();
        }
        $response = array(
            'data' => $userAdPackage,
            'error' => array(
                'code' => 0,
                'message' => 'Payment successfully completed'
            )
        );
        return $response;
    }
    public function processInitiated($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment initiated',
                'fields' => ''
            )
        );
        return $response;
    }
    public function processPending($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment is in pending state.'
            )
        );
        return $response;
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
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['category_id'])) {
            $query->whereHas('ad_package', function ($q) use ($params) {
            
                $q->where('category_id', $params['category_id']);
            });
        }
        if (!empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }
        if (!empty($params['ad_package_id'])) {
            $query->where('ad_package_id', $params['ad_package_id']);
        }
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
            
                $q1->orWhereHas('user', function ($q) use ($params) {
                
                    $q->where('users.username', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('ad_package', function ($q) use ($params) {
                
                    $q->Where('ad_packages.name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
    public function getUserAdPackage($userId, $categoryId) 
    {
        $userAdPackage = UserAdPackage::select('user_ad_packages.*')
                            ->where('user_id', $userId)
                            ->whereDate('expiry_date', '>=', date('Y-m-d'))
                            ->where('allowed_ad_count', '>', 0)
                            ->whereHas('ad_package', function($query) use ($categoryId) {
                                $query->where('category_id', $categoryId);
                            })
                            ->whereHas('user', function($query) {
                                $query->where('ad_count', '>', 0);
                            })                            
                            ->first();
        if (empty($userAdPackage)) {
            $userAdPackage = array();
        }
        return $userAdPackage;
    }
}
