<?php
/**
 * server.php
 *
 */
define('APP_PATH', __DIR__ . '/../');

$autoload = require __DIR__ . '/../vendor/autoload.php';
$autoload->add('', APP_PATH . 'src/');

$server     = new \swoole_websocket_server('0.0.0.0', 11011);
$dispatcher = new \ResquePanel\Dispatcher();

$server->on('Open', function ($server, $req) {
    echo "connection open: " . $req->fd;
});

$server->on('Message', function ($server, $frame) use ($dispatcher) {
    $dispatcher->setServer($server)->setFrame($frame)->handle();
});

$server->on('Close', function ($server, $fd) {
    echo "connection close: " . $fd;
});

$server->start();