<?php
/**
 * resque_panel_recorder.php
 */

define('APP_PATH', __DIR__ . '/../');

$autoload = require APP_PATH . 'vendor/autoload.php';

$config = require APP_PATH . 'config/config.php';
\ResquePanel\Util\Config::setConfig($config);

$collector = new \ResquePanel\Collector();
while (true) {
    $collector->persistCurrentMinuteStatus();
    sleep(60);
}