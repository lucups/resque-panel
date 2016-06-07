<?php
/**
 * AppContext.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/6/7 16:12
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel;

use ResquePanel\Util\Config;

/**
 * Trait AppContext
 * @package ResquePanel
 */
trait AppContext
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

    public function catchRedisException(\Exception $e)
    {
        // TODO log the exception, and do something for notif
        $this->redis = null;
    }
}