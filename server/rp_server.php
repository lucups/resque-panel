<?php
/**
 * rp_server.php
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('APP_PATH', __DIR__ . '/../');

$autoload = require APP_PATH . 'vendor/autoload.php';

$config = require APP_PATH . 'config/config.php';
\ResquePanel\Util\Config::setConfig($config);

try {
    $ws_url = 'ws://' . $config['ws']['host'] . ':' . $config['ws']['port'];
    $server = new \Wrench\BasicServer($ws_url, [
        'check_origin' => false,
    ]);
    $server->registerApplication('panel', new \ResquePanel\Server\PanelApplication());
    $server->run();
} catch (\Exception $e) {
    print_r($e);
}