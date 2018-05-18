<?php
/**
 * Category
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
 * Category
*/
class Category extends AppModel
{
    protected $table = 'categories';
    protected $fillable = array(
        'name',
        'description',
        'parent_id',
        'allowed_free_ads_count',
        'post_ad_fee',
        'is_active',
        'is_popular'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'allowedFreeAdsCount' => 'sometimes|required',
        'postAdFee' => 'sometimes|required',
        'isActive' => 'sometimes|required',
    );
    public function parent()
    {
        return $this->belongsTo('Models\Category', 'parent_id', 'id');
    }
    public function form_field()
    {
        return $this->hasMany('Models\FormField', 'category_id', 'id')->with('input_types');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('class', 'Category');
    }
    public function subcategory()
    {
        return $this->hasMany('Models\Category', 'parent_id', 'id')->with('attachment', 'form_field');
    }
    public function subcategories()
    {
        return $this->subcategory()->with('subcategories');
    }
    public function scopeFilter($query, $params = array())
    {
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
            
                $q1->Where('categories.name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('subcategory', function ($q) use ($params) {
                
                    $q->where('categories.name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('parent', function ($q) use ($params) {
                
                    $q->where('parent.name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
        if (isset($params['is_popular'])) {
            $query->where('categories.is_popular', $params['is_popular']);
        }
        parent::scopeFilter($query, $params);
    }
    public function checkPayment($userId, $categoryId, $category)
    {
        $checkPayment = array();
        $adCategoryCount = Ad::where('category_id', $categoryId)->where('user_id', $userId)->count();
        $paidStatus = 0;
        $amount = 0;        
        if ($category->allowed_free_ads_count <= $adCategoryCount) {
            $paidStatus = 1; 
        }
        if (!empty($paidStatus)) {
            $userAdPackage = UserAdPackage::getUserAdPackage($userId, $categoryId);
            if (empty($userAdPackage)) {
                $paidStatus = 1;
                $amount = $category->post_ad_fee;
            } else {
                $paidStatus = 0;
            }
        } 
        $checkPayment['payment_status'] = $paidStatus;
        $checkPayment['amount'] = $amount;
        return $checkPayment;
    }
}
