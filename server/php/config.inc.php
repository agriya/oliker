<?php
/**
 * Core configurations
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
define('R_DEBUG', false);
ini_set('display_errors', R_DEBUG);
define('R_API_VERSION', 1);
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(dirname(dirname(__FILE__))));
define('MEDIA_PATH', APP_PATH . DS . 'media');
define('TMP_PATH', APP_PATH . DS . 'tmp');
define('CACHE_PATH', TMP_PATH . DS . 'cache');
if (file_exists(APP_PATH . DS . 'client' . DS . 'scripts')) {
    define('SCRIPT_PATH', APP_PATH . DS . 'client' . DS . 'scripts');
    define('IMAGES_PATH', APP_PATH . DS . 'client' . DS . 'images');
} else {
    define('SCRIPT_PATH', APP_PATH . DS . 'client' . DS . 'app' . DS . 'scripts');
    define('IMAGES_PATH', APP_PATH . DS . 'client' . DS . 'app' . DS . 'images');
}
$default_timezone = 'Europe/Berlin';
if (ini_get('date.timezone')) {
    $default_timezone = ini_get('date.timezone');
}
date_default_timezone_set($default_timezone);
define('R_DB_DRIVER', 'pgsql');
define('R_DB_HOST', 'localhost');
define('R_DB_NAME', 'oliker');
define('R_DB_USER', 'postgres');
define('R_DB_PASSWORD', 'ahsan123');
define('R_DB_PORT', 5432);
define('SECURITY_SALT', 'e9a556134534545ab47c6c81c14f06c0b8sdfsdf');
define('OAUTH_CLIENT_ID', '2212711849319225');
define('OAUTH_CLIENT_SECRET', '14uumnygq6xyorsry8l382o3myr852hb');
define('PAGE_LIMIT', 20);
define('MAX_UPLOAD_SIZE', 8000);
$_server_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
if (!defined('STDIN')) {
    $_server_domain_url = $_server_protocol . '://' . $_SERVER['HTTP_HOST']; // http://localhost
    
}
if (!defined('STDIN') && !file_exists(APP_PATH . '/tmp/cache/site_url_for_shell.php') && !empty($_server_domain_url)) {
    $fh = fopen(APP_PATH . '/tmp/cache/site_url_for_shell.php', 'a');
    fwrite($fh, '<?php' . "\n");
    fwrite($fh, '$_server_domain_url = \'' . $_server_domain_url . '\';');
    fclose($fh);
}
const THUMB_SIZES = array(
    'small_thumb' => '32x32',
    'normal_thumb' => '70x68',
    'big_normal_thumb' => '125x126',
    'micro_thumb' => '60x45',
    'medium_thumb' => '218x210',
    'normal_thumb' => '184x176',
    'big_thumb' => '450x439',
    'small_normal_thumb' => '90x60',
    'medium_thumb' => '178x170',
    'small_thumb' => '47x37',
    'normal_thumb' => '184x176',
    'big_small_thumb' => '185x147',
    'big_thumb' => '950x350'
);