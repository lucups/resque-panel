<?php
/**
 * HelloService.php
 *      Just for demo.
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/26 17:45
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Service;

/**
 * Class HelloService
 * @package ResquePanel\Service
 */
class HelloService
{
    public function say($name)
    {
        return 'Hello ' . $name . '!';
    }
}