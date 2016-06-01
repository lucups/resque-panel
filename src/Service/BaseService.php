<?php
/**
 * BaseService.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/27 16:12
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Service;

use ResquePanel\Util\Config;

/**
 * Class BaseService
 * @package ResquePanel\Service
 */
class BaseService
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
}