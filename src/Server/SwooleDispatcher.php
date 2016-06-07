<?php
/**
 * SwooleDispatcher.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/26 17:45
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Server;

use ResquePanel\AppContext;
use ResquePanel\ResqueService;

/**
 * Class SwooleDispatcher
 * @package ResquePanel
 */
class SwooleDispatcher
{
    use AppContext;

    const SORT_BY_TIME_ASC  = 1; // 按时间正序排列
    const SORT_BY_TIME_DESC = 2; // 按时间倒序排列

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
     * @return null
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return null
     */
    public function getFrame()
    {
        return $this->frame;
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
     * @param $code
     * @param $action
     * @param $data
     */
    public function push($code, $action, $data)
    {
        $resp = [
            'code'   => $code,
            'action' => $action,
            'data'   => $data,
        ];
        $this->getServer()->push($this->getFrame()->fd, json_encode($resp));
    }

    /**
     * handle
     *      The default service is ResponseService.
     */
    public function handle()
    {
        $data       = json_decode($this->frame->data, true);
        $resque_srv = new ResqueService();
        if (method_exists($resque_srv, $data['action'])) {
            if (empty($data['params'])) {
                $data['params'] = null;
            }
            $result = $resque_srv->$data['action']($data['params']);
            if (!empty($result)) {
                $this->push(0, $data['action'], $result);
            }
        } else {
            throw new \Exception('Method is not exists!');
        }
    }
}