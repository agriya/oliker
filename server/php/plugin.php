<?php
/**
 * To create minify plugin cache
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
require_once 'config.inc.php';
require_once 'Slim/vendor/autoload.php';
require_once 'Slim/lib/database.php';
require_once 'Slim/lib/settings.php';
$enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
$concat = '';
foreach ($enabled_plugins as $plugin) {
    $pluginPath = str_replace('/', '.', $plugin);
    $plugin_name = explode('/', $plugin);
    if ($plugin_name[0] === $plugin_name[1]) {
        $pluginPath = $plugin_name[0];
    }
    if (file_exists(SCRIPT_PATH . DS . 'plugins' . DS . $plugin . DS . 'default.cache.js')) {
        $concat.= file_get_contents(SCRIPT_PATH . DS . 'plugins' . DS . $plugin . DS . 'default.cache.js');
    }
    $concat.= "angular.module('olikerApp').requires.push('olikerApp." . $pluginPath . "');";
}
file_put_contents(SCRIPT_PATH . DS . $_GET['file'], $concat);
header('Location:' . $_SERVER['REQUEST_URI'] . '?chrome-3xx-fix');
