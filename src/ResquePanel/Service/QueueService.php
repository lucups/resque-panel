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
 * Class QueueService
 * @package ResquePanel\Service
 */
class QueueService
{
    private $redis = null;

    public function getRedis()
    {
        if (empty($this->redis)) {
            $this->redis = new \Redis();
            $this->redis->connect('127.0.0.1', 6379);
        }
        return $this->redis;
    }

    public function queueNames()
    {
        return $this->getRedis()->sMembers('resque:queues');
    }
}