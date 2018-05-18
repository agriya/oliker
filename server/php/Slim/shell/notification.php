<?php
/**
 * Send price reduce notification mail to ad favorite users
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
sendNotificationMailToAdFavourite();
function sendNotificationMailToAdFavourite()
{
    global $_server_domain_url;
    $ads = Models\Ad::with('ad_favorite')->where('is_price_reduced', 1)->get()->toArray();
    if (!empty($ads)) {
        foreach ($ads as $ad) {
            if (!empty($ad['ad_favorite'])) {
                foreach ($ad['ad_favorite'] as $ad_favorite) {
                    $user = Models\User::with('user_notification')->where('id', $ad['ad_favorite']['user_id'])->first()->toArray();
                    if ($user['user_notification']['is_price_reduced_on_favorite_ads_to_email'] == 1) {
                        $username = $user['username'];
                        $email = $user['email'];
                        $emailFindReplace = array(
                            '##USERNAME##' => $username,
                            '##AD_NAME##' => $ad['title'],
                            '##AD_URL##' => $_server_domain_url . '/#/ads/' . $ad['id']
                        );
                        sendMail('adFavorite', $emailFindReplace, $email);
                    }
                }
                Models\Ad::where('id', $ad['id'])->update(['is_price_reduced' => 0]);
            }
        }
    }
}
