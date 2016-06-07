<?php
/**
 * ServerInterface.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/6/7 12:07
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel;

/**
 * Interface ServerInterface
 * @package ResquePanel
 */
interface ServerInterface
{
    public function push($code, $action, $data);
}