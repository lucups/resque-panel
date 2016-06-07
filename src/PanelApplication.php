<?php
/**
 * PanelApplication.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/6/7 11:32
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel;

use Wrench\Application\Application;

/**
 * Class PanelApplication
 * @package ResquePanel
 */
class PanelApplication extends Application
{
    use AppContext;

    public function onData($payload, $connection)
    {
        // TODO: Implement onData() method.
    }
}