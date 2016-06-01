<?php
/**
 * QueueService.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/27 10:41
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Service;

/**
 * Class ResponseService
 * @package ResquePanel\Service
 */
class ResponseService extends BaseService
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

    /**
     * @param null $params
     * @return array
     */
    public function getNames($params = null)
    {
        return $this->getRedis()->sMembers('resque:queues');
    }

    /**
     * Return the data by timestamp (default is now).
     * @param null $params
     */
    public function status($params = null)
    {
        $redis = $this->getRedis();
        $data  = [
            'time' => date('Y-m-d H:i:s'),
            'val'  => $redis->lLen('resque:queue:v3') + rand(1, 10),
        ];
        $this->push(0, 'queues_status', $data);
    }

    public function history()
    {

    }
}