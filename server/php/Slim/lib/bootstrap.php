<?php
/**
 * API Endpoints
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
require_once __DIR__ . '/../../config.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../lib/vendors/Inflector.php';
require_once '../lib/vendors/OAuth2/Autoloader.php';
require_once '../lib/vendors/Zazpay/zazpay.php';
require_once '../lib/database.php';
require_once '../lib/core.php';
require_once '../lib/constants.php';
require_once '../lib/settings.php';
require_once '../lib/acl.php';
require_once '../lib/auth.php';
require_once '../lib/vendors/geo_hash.php';
use Illuminate\Pagination\Paginator;

Paginator::currentPageResolver(function ($pageName) {

    return empty($_GET[$pageName]) ? 1 : $_GET[$pageName];
});
$config = ['settings' => ['displayErrorDetails' => R_DEBUG]];
$app = new Slim\App($config);
$app->add(new \pavlakis\cli\CliRequest());
$app->add(new Auth());
$plugins = explode(',', SITE_ENABLED_PLUGINS);
foreach ($plugins as $plugin) {
    require_once __DIR__ . '/../plugins/' . $plugin . '/index.php';
}
function isPluginEnabled($pluginName)
{
    $plugins = explode(',', SITE_ENABLED_PLUGINS);
    $plugins = array_map('trim', $plugins);
    if (in_array($pluginName, $plugins)) {
        return true;
    }
    return false;
}