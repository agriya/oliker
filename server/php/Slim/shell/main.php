<?php
/**
 * Update ad extra end date & package expired date
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
require_once __DIR__ . '/../../config.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../lib/constants.php';
$now = date('Y-m-d h:i:s');
Models\OauthAccessToken::where('expires', '<=', $now)->delete();
Models\OauthRefreshToken::where('expires', '<=', $now)->delete();
updateAdExtraEnddate();
updatePackageExpireCheck();
function updateAdExtraEndDate()
{
    $current_date = date('Y-m-d');
    // update top ads
    $ads = Models\Ad::where('top_ads_end_date', '<', $current_date)->update(array(
        'is_show_as_top_ads' => 0
    ));
    // update ad in top
    $ads = Models\Ad::where('ad_in_top_end_date', '<', $current_date)->update(array(
        'is_show_ad_in_top' => 0
    ));
    // Update urgent ad
    $ads = Models\Ad::where('urgent_end_date', '<', $current_date)->update(array(
        'is_urgent' => 0
    ));
    // Update highlight ad
    $ads = Models\Ad::where('highlighted_end_date', '<', $current_date)->update(array(
        'is_highlighted' => 0
    ));
}
function updatePackageExpireCheck()
{
    $current_date = date('Y-m-d');
    // update user package check
    $user_ad_packages = Models\UserAdPackage::withoutGlobalScope('user')->where('expiry_date', '<', $current_date)->get();
    foreach ($user_ad_packages as $user_ad_package) {
        $user = Models\User::find($user_ad_package->user_id);
        $user->is_subscribed = 0;
        $user->ad_count = $user->ad_count - $user_ad_package->allowed_ad_count;
        $user->save;
        $userAdPackage = Models\UserAdPackage::withoutGlobalScope('user')->find($user_ad_package->id);
        $userAdPackage->allowed_ad_count = 0;
        $userAdPackage->save();
    }
}
