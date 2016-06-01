<?php
/**
 * QueueService.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/27 10:41
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Service;

use ResquePanel\Util\Config;

/**
 * Class QueueService
 * @package ResquePanel\Service
 */
class QueueService extends BaseService
{
    private $redis = null;

    /**
     * @return null|\Redis
     * @throws \Exception
     */
    public function getRedis()
    {
        if (empty($this->redis)) {
            $redis_conf  = Config::get('redis');
            $this->redis = new \Redis();
            $this->redis->connect($redis_conf['host'], $redis_conf['port']);
        }
        return $this->redis;
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->getRedis()->sMembers('resque:queues');
    }

    public function status()
    {
        while (true) {
            $data = [
                'time' => strtotime('now'),
                'val'  => rand(50, 100),
            ];
            $this->push(0, 'queues_status', $data);
            sleep(2);
        }
    }
}