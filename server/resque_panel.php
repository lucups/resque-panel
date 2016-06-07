<?php
/**
 * resque_panel.php
 */

define('APP_PATH', __DIR__ . '/../');

$autoload = require APP_PATH . 'vendor/autoload.php';

$config = require APP_PATH . 'config/config.php';
\ResquePanel\Util\Config::setConfig($config);

try {
    $server = new \Wrench\BasicServer('ws://' . $config['ws']['host'] . ':' . $config['ws']['port']);
    // $server->registerApplication('echo', new \Wrench\Examples\EchoApplication());
    // $server->registerApplication('chat', new \My\ChatApplication());

    $server->run();
} catch (\Exception $e) {
    print_r($e);
}