<?php
/**
 * Dispatcher.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/26 17:45
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel;

use ResquePanel\Service\HelloService;

/**
 * Class Dispatcher
 * @package ResquePanel
 */
class Dispatcher
{
    private $server = null;
    private $frame  = null;

    /**
     * @param $server
     * @return $this
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @param $frame
     * @return $this
     */
    public function setFrame($frame)
    {
        $this->frame = $frame;
        return $this;
    }

    /**
     * handle
     */
    public function handle()
    {
        $hello = new HelloService();
        $this->server->push($this->frame->fd, $hello->say('Tony'));
        $data   = json_decode($this->frame->data, true);
        $action = explode('.', $data['action']);
        

//        $data = json_decode($frame->data, true);
//        if (empty($data['action'])) {
//            $server->push($frame->fd, json_encode(['code' => 1, 'message' => 'Hello Client! Action name needed!']));
//        } elseif (method_exists($dispatcher, $data['action'])) {
//            $result = $dispatcher->$data['action']($data);
//            $server->push($frame->fd, json_encode($result));
//        } else {
//            $server->push($frame->fd, json_encode(['code' => 1, 'message' => "action {$data['action']} is not exists"]));
//        }
        // return $this;
    }
}