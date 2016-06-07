<?php
/**
 * Debug.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/6/7 15:30
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Util;

/**
 * Class Debug
 * @package ResquePanel\Util
 */
class Debug
{
    public static function printR($data)
    {
        print_r("------------------------------------------\n");
        print_r($data);
        print_r("------------------------------------------\n");
    }

    /**
     * @param $obj
     */
    public static function printClassMethods($obj, $debug = true)
    {
        if (Config::get('debug') && $debug) {
            $methods = get_class_methods($obj);
            print_r($methods);
        }
    }
}