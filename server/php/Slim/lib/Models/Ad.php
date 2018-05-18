<?php
/**
 * Ad
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
 * Ad
*/
class Ad extends AppModel
{
    protected $table = 'ads';
    protected $fillable = array(
        'title',
        'category_id',
        'advertiser_type_id',
        'is_an_exchange_item',
        'price',
        'is_negotiable',
        'description',
        'city_name',
        'state_name',
        'country_iso2',
        'location',
        'is_send_email_when_user_contact',
        'latitude',
        'longitude',
        'advertiser_name',
        'phone_number',
        'is_active',
        'payment_gateway_id'
    );
    public $rules = array(
        'title' => 'sometimes|required',
        'categoryId' => 'sometimes|required',
        'advertiserTypeId' => 'sometimes|required',
        'is_an_exchange_item' => 'sometimes|required|boolean',
        'price' => 'sometimes|required',
        'is_negotiable' => 'sometimes|required|boolean',
        'description' => 'sometimes|required',
        'cityName' => 'sometimes|required',
        'countryIso2' => 'sometimes|required',
        'location' => 'sometimes|required',
        'latitude' => 'sometimes|required',
        'longitude' => 'sometimes|required',
        'advertiser_name' => 'sometimes|required',
        'phoneNumber' => 'sometimes|required',
    );
    protected $hidden = array(
        'phone_number'
    );
    public function category()
    {
        return $this->belongsTo('Models\Category', 'category_id', 'id');
    }
    public function advertiser_type()
    {
        return $this->belongsTo('Models\AdvertiserType', 'advertiser_type_id', 'id');
    }
    public function attachment()
    {
        return $this->hasMany('Models\Attachment', 'foreign_id', 'id')->where('class', 'Ad');
    }
    public function ad_owner()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id')->with('attachment')->select('id', 'created_at', 'username');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id')->with('attachment')->select('id', 'created_at', 'username');
    }
    public function ad_view()
    {
        return $this->hasMany('Models\AdView', 'ad_id', 'id');
    }
    public function ad_report()
    {
        return $this->hasMany('Models\AdReport', 'ad_id', 'id');
    }
    public function ad_form_field()
    {
        return $this->hasMany('Models\AdFormField', 'ad_id', 'id')->with('form_field');
    }
    public function ad_favorite()
    {
        return $this->hasMany('Models\AdFavorite', 'ad_id', 'id');
    }
    public function city()
    {
        return $this->belongsTo('Models\City', 'city_id', 'id')->select('id', 'name');
    }
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id')->select('id', 'name');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id')->select('id', 'iso_alpha2', 'name');
    }
    public function foreign_transactions()
    {
        return $this->morphMany('Models\Transaction', 'foreign_transaction');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['advertiser_type_id'])) {
            $query->whereIn('advertiser_type_id', explode(',', $params['advertiser_type_id']));
        }
        if (!empty($params['filter']) && ($params['filter'] == 'related')) {
            if (!empty(isset($params['user_id']))) {
                $query->where('ads.user_id', $params['user_id']);
                isset($params['ad_id']) ? $query->where('ads.id', '!=', $params['ad_id']) : $query;
            } elseif (!empty($params['ad_id'])) {
                $ad = self::find($params['ad_id']);
                $category_id = isset($ad->category_id) ? $ad->category_id : 0;
                $query->where('ads.category_id', $category_id);
            }
        }
        if (!empty($params['category_id'])) {
            $query->whereIn('ads.category_id', explode(',', $params['category_id']));
        }
        if (isset($params['city_id'])) {
            $query->where('ads.city_id', $params['city_id']);
        }
        if (isset($params['is_urgent'])) {
            $query->where('ads.is_urgent', $params['is_urgent']);
        }
        if (isset($params['is_highlighted'])) {
            $query->where('ads.is_highlighted', $params['is_highlighted']);
        }
        if (isset($params['is_show_as_top_ads'])) {
            $query->where('ads.is_show_as_top_ads', $params['is_show_as_top_ads']);
        }
        if (isset($params['is_show_ad_in_top'])) {
            $query->where('ads.is_show_ad_in_top', $params['is_show_ad_in_top']);
        }
        if (isset($params['min_price'])) {
            $query->where('ads.price', '>=', $params['min_price']);
        }
        if (isset($params['max_price'])) {
            $query->where('ads.price', '<=', $params['max_price']);
        }
        if (isset($params['is_only_ads_with_images'])) {
            $query->whereHas('attachment');
        }
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
            
                $q1->where('ads.title', 'ilike', '%' . $params['q'] . '%');
                if (isset($params['is_search_in_description'])) {
                    $q1->orWhere('ads.description', 'ilike', '%' . $params['q'] . '%');
                }
                $q1->orWhere('ads.advertiser_name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('ad_owner', function ($q) use ($params) {
                
                    $q->where('users.username', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('advertiser_type', function ($q) use ($params) {
                
                    $q->where('advertiser_types.name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('category', function ($q) use ($params) {
                
                    $q->where('categories.name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
    public function processCaptured($payment_response, $id)
    {
        $ad = Ad::with('attachment', 'ad_owner', 'category', 'advertiser_type', 'ad_form_field')->find($id);
        if (!empty($ad->pending_payment_log)) {
            $ad_extra_ids = unserialize($ad->pending_payment_log);
            if (!empty($ad_extra_ids)) {
                foreach ($ad_extra_ids as $ad_extra_id) {            
                    $ad_extra_days = AdExtraDay::where('ad_extra_id', $ad_extra_id['id'])->where('category_id', $ad->category_id)->first();                    
                    if (!empty($ad_extra_days)) {
                        $ad_extra_day_id = $ad_extra_days->id;
                        $user_ad_extra = new UserAdExtra;
                        $user_ad_extra->ad_extra_day_id = $ad_extra_day_id;
                        $user_ad_extra->ad_id = $ad->id;
                        $user_ad_extra->ad_extra_id = $ad_extra_id['id'];
                        $user_ad_extra->amount = $ad_extra_days->amount;
                        $user_ad_extra->payment_gateway_id = $ad->payment_gateway_id;
                        $user_ad_extra->is_payment_completed = 1;
                        $user_ad_extra->user_id = $ad->user_id;                        
                        $user_ad_extra->save();
                        $validity_days = $ad_extra_days->days;
                        if ($ad_extra_id['id'] == \Constants\ConstAdExtra::TopAd) {
                            $ad->is_show_as_top_ads = 1;
                            $ad->top_ads_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                        } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::Highlight) {
                            $ad->is_highlighted = 1;
                            $ad->highlighted_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                        } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::Urgent) {
                            $ad->is_urgent = 1;
                            $ad->urgent_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                        } elseif ($ad_extra_id['id'] == \Constants\ConstAdExtra::InTop) {
                            $ad->is_show_ad_in_top = 1;
                            $ad->ad_in_top_end_date = date('Y-m-d', strtotime($validity_days . ' days'));
                        }
                        $ad->is_active = 1;
                        $ad->update();
                    }                    
                }
            }
        }
        $userAdPackage = UserAdPackage::getUserAdPackage($ad->user_id, $ad->category_id);
        if (!empty($userAdPackage)) {
            $userAdPackage->used_ad_count = $userAdPackage->used_ad_count + 1;
            $userAdPackage->update();
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
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        self::created(function ($ad) use ($authUser) {
        
            if (!empty($authUser['id'])) {
                if ($ad->is_active != 0) {
                    User::find($ad->user_id)->increment('ad_active_count', 1);
                }
                if ($ad->id) {
                    Category::find($ad->category_id)->increment('ad_count', 1);
                }
            }
        });
        self::deleted(function ($ad) use ($authUser) {
        
            if (!empty($authUser['id'])) {
                if ($ad->is_active != 0) {
                    User::find($ad->user_id)->decrement('ad_active_count', 1);
                }
                if ($ad->id) {
                    Category::find($ad->category_id)->decrement('ad_count', 1);
                }
            }
        });
    }
}
