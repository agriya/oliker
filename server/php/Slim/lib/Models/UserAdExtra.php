<?php
/**
 * UserAdExtra
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
 * UserAdExtra
*/
class UserAdExtra extends AppModel
{
    protected $table = 'user_ad_extras';
    protected $fillable = array(
        'ad_id',
        'ad_extra_id',
        'ad_extra_day_id',
        'amount',
        'payment_gateway_id'
    );
    public $rules = array(
        'adId' => 'sometimes|required',
        'adExtraId' => 'sometimes|required',
        'adExtraDayId' => 'sometimes|required',
        'amount' => 'sometimes|required',
        'paymentGatewayId' => 'sometimes|required',
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function ad()
    {
        return $this->belongsTo('Models\Ad', 'ad_id', 'id');
    }
    public function ad_extra()
    {
        return $this->belongsTo('Models\AdExtra', 'ad_extra_id', 'id');
    }
    public function ad_extra_day()
    {
        return $this->belongsTo('Models\AdExtraDay', 'ad_extra_day_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function foreign_transactions()
    {
        return $this->morphMany('Models\Transaction', 'foreign_transaction');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }
        if (!empty($params['ad_id'])) {
            $query->where('ad_id', $params['ad_id']);
        }
        if (!empty($params['ad_extra_id'])) {
            $query->where('ad_extra_id', $params['ad_extra_id']);
        }
        if (!empty($params['ad_extra_day_id'])) {
            $query->where('ad_extra_day_id', $params['ad_extra_day_id']);
        }
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->orWhereHas('user', function ($q) use ($params) {
                
                    $q->where('users.username', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('ad', function ($q) use ($params) {
                
                    $q->Where('ads.title', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('ad_extra', function ($q) use ($params) {
                
                    $q->Where('ad_extras.name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('payment_gateway', function ($q) use ($params) {
                
                    $q->Where('payment_gateways.name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
    public function processCaptured($payment_response, $id)
    {
        $userAdExtra = UserAdExtra::where('id', $id)->where('is_payment_completed', false)->first();
        if (!empty($userAdExtra)) {
            $user_ad_extra = UserAdExtra::where('id', $userAdExtra['id'])->update(array(
                'is_payment_completed' => 1,
                'payment_gateway_id' => $payment_response['payment_gateway_id']
            ));
            $ad_extra_days = AdExtraDay::where('id', $userAdExtra['ad_extra_day_id'])->first();
            $validity_days = $ad_extra_days->days;
            $ad = Ad::find($userAdExtra['ad_id']);
            if ($userAdExtra['ad_extra_id'] == \Constants\ConstAdExtra::TopAd) {
                $ad->is_show_as_top_ads = 1;
                $ad->top_ads_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
            } elseif ($userAdExtra['ad_extra_id'] == \Constants\ConstAdExtra::Highlight) {
                $ad->is_highlighted = 1;
                $ad->highlighted_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
            } elseif ($userAdExtra['ad_extra_id'] == \Constants\ConstAdExtra::Urgent) {
                $ad->is_urgent = 1;
                $ad->urgent_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
            } elseif ($userAdExtra['ad_extra_id'] == \Constants\ConstAdExtra::InTop) {
                $ad->is_show_ad_in_top = 1;
                $ad->ad_in_top_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
            }
            $ad->update();
            //Transaction process
            $adminId = User::select('id')->where('role_id', \Constants\ConstUserTypes::Admin)->first();
            Transaction::insertTransaction($userAdExtra->user_id, $adminId['id'], $ad->id, 'UserAdExtra', $payment_response['payment_gateway_id'], $userAdExtra->amount,'AdFeaturesUpdatedFee');            
        }
        $response = array(
            'data' => $payment_response,
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
}
