<?php
/**
 * User
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

class User extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $fillable = array(
        'username',
        'email',
        'password',
        'is_agree_terms_conditions',
        'first_name',
        'last_name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'mobile',
        'zip_code',
        'address1',
        'about_me',
        'is_active'
    );
    public $qSearchFields = array(
        'first_name',
        'last_name',
        'mobile',
        'username',
        'email',
    );
    public $rules = array(
        'username' => 'sometimes|required|alpha_num',
        'email' => 'sometimes|required|email',
        'password' => 'sometimes|required'
    );
    // Admin scope
    protected $scopes_1 = array();
    // User scope
    protected $scopes_2 = array(
        'canUpdateUser',
        'canViewUser',
        'canListUserTransactions',
        'canUserCreateUserCashWithdrawals',
        'canUserViewUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canUserCreateMoneyTransferAccount',
        'canUserUpdateMoneyTransferAccount',
        'canUserViewMoneyTransferAccount',
        'canUserListMoneyTransferAccount',
        'canUserDeleteMoneyTransferAccount',
        'canListWallet',
        'canCreateWallet',
        'canListUserNotification',
        'canViewUserNotification',
        'canUpdateUserNotification',
        'canListUserAdExtra',
        'canCreateUserAdExtra',
        'canListUserAdPackage',
        'canListMessage',
        'canDeleteMessage',
        'canViewMessage',
        'canUpdateMessage',
        'canCreateMessage',
        'canDeleteAd',
        'canUpdateAd',
        'canCreateAd',
        'canDeleteAdFavorite',
        'canViewAdFavorite',
        'canListAdFavorite',
        'canCreateAdFavorite',
        'canDeleteAdSearch',
        'canViewAdSearch',
        'canUpdateAdSearch',
        'canListAdSearch',
        'canCreateAdSearch',
        'canCreateAdReport',
        'canViewMyAd',
        'canViewMyProfile',
        'canListAdFormField',
        'canCreateUserAdPackage',
        'canCreateMoneyTransferAccount',
        'canViewMoneyTransferAccount',
        'canUpdateMoneyTransferAccount',
        'canDeleteMoneyTransferAccount',
        'canListAdReportType',
        'canDeleteAttachment',
        'canCheckCategoryPayment',
        'canCreateValut',
        'canUpdateValut',
        'canDeleteValut',
        'canGetValut'
    );
    /**
     * To check if username already exist in user table, if so generate new username with append number
     *
     * @param string $username User name which want to check if already exsist
     *
     * @return mixed
     */
    public function checkUserName($username)
    {
        $userExist = User::where('username', $username)->first();
        if (count($userExist) > 0) {
            $org_username = $username;
            $i = 1;
            do {
                $username = $org_username . $i;
                $userExist = User::where('username', $username)->first();
                if (count($userExist) < 0) {
                    break;
                }
                $i++;
            } while ($i < 1000);
        }
        return $username;
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
        return $this->belongsTo('Models\Country', 'country_id', 'id')->select('id', 'iso_alpha2');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('class', 'UserAvatar');
    }
    public function role()
    {
        return $this->belongsTo('Models\Role', 'role_id', 'id');
    }
    public function user_notification()
    {
        return $this->hasOne('Models\UserNotification', 'user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['role_id'])) {
            $query->where('role_id', $params['role_id']);
        }
        if (!empty($params['q'])) {
            $query->orWhereHas('city', function ($q) use ($params) {
            
                $q->where('name', 'like', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('state', function ($q) use ($params) {
            
                $q->where('name', 'like', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('country', function ($q) use ($params) {
            
                $q->where('name', 'like', '%' . $params['q'] . '%');
            });
            $query->orWhereHas('country', function ($q) use ($params) {
            
                $q->where('iso_alpha2', 'like', '%' . $params['q'] . '%');
            });
        }
    }
}
