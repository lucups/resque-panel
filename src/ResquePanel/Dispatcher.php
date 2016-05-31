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
    private $config = null;

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
     * @param $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * TODO: A container maybe needed.
     * @param $srv_name
     * @return mixed
     * @throws \Exception
     */
    public function getService($srv_name)
    {
        $srv_name = '\\ResquePanel\\Service\\' . ucfirst($srv_name) . 'Service';
        if (class_exists($srv_name)) {
            return new $srv_name($this->server, $this->frame);
        } else {
            throw new \Exception('Service not exists!');
        }
    }

    /**
     * handle
     */
    public function handle()
    {
        $hello = new HelloService();
        $this->server->push($this->frame->fd, $hello->say('Tony'));
        $data = json_decode($this->frame->data, true);

        $srv = $this->getService($data['srv']);
        if (method_exists($srv, $data['mtd'])) {
            $srv->$data['mtd']();
        }
    }
}