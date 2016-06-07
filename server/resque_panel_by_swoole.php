<?php
/**
 * resque_panel_by_swoole.php
 *
 */
define('APP_PATH', __DIR__ . '/../');

$autoload = require APP_PATH . 'vendor/autoload.php';

$config = require APP_PATH . 'config/config.php';
\ResquePanel\Util\Config::setConfig($config);

try {
    // fork a child process to record queue status data
    $child_logger     = new \swoole_process(function () {
        $logger = new \Monolog\Logger('Queue Monitor');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler('/tmp/queue_monitor.log', \Monolog\Logger::INFO));

        $collector = new \ResquePanel\Collector();
        while (true) {
            $collector->persistCurrentMinuteStatus();
            $logger->info('Hello ' . strtotime('now')); // todo remove
            sleep(60);
        }
    });
    $child_logger_pid = $child_logger->start();

    // init server
    $server     = new \swoole_websocket_server($config['ws']['host'], $config['ws']['port']);
    $dispatcher = new \ResquePanel\SwooleDispatcher();

    $server->on('Open', function ($server, $req) use ($dispatcher, $config) {
        echo "connection open: " . $req->fd;
    });

    $server->on('Message', function ($server, $frame) use ($dispatcher, $config) {
        try {
            $dispatcher->setServer($server)->setFrame($frame)->setConfig($config)->handle();
        } catch (\Exception $e) {
            print_r($e);
        }
    });

    $server->on('Close', function ($server, $fd) {
        echo "connection close: " . $fd;
    });

    $server->start();
} catch (\Exception $e) {
    print_r($e);
}