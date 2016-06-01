<?php
/**
 * Dispatcher.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/26 17:45
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel;

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
            throw new \Exception('Service is not exists!' . "[Service Name: $srv_name]");
        }
    }

    /**
     * handle
     *      The default service is ResponseService.
     */
    public function handle()
    {
        $data = json_decode($this->frame->data, true);
        if (empty($data['srv'])) {
            $data['srv'] = 'Response';
        }
        $srv = $this->getService($data['srv']);
        if (method_exists($srv, $data['mtd'])) {
            if (empty($data['params'])) {
                $params = null;
            }
            $srv->$data['mtd']($params);
        } else {
            throw new \Exception('Method is not exists!');
        }
    }
}