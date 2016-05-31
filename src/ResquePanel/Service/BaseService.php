<?php
/**
 * BaseService.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/27 16:12
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Service;

/**
 * Class BaseService
 * @package ResquePanel\Service
 */
class BaseService
{
    private $server = null;
    private $frame  = null;

    public function __construct($server, $frame)
    {
        $this->server = $server;
        $this->frame  = $frame;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getFrame()
    {
        return $this->frame;
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
}