<?php
/**
 * Config.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/27 11:34
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Util;

class Config
{
    private static $config = null;

    /**
     * @param array $config
     */
    public static function setConfig(array $config)
    {
        self::$config = $config;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public static function get($key)
    {
        if (empty(self::$config)) {
            throw new \Exception('Config is null!');
        }
        return self::$config[$key];
    }
}