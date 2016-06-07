<?php
/**
 * PanelApplication.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/6/7 11:32
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Server;

use ResquePanel\AppContext;
use ResquePanel\ResqueService;
use ResquePanel\Util\Debug;
use Wrench\Application\Application;

/**
 * Class PanelApplication
 * @package ResquePanel
 */
class PanelApplication extends Application
{
    use AppContext;

    /**
     * @param      $code
     * @param      $action
     * @param      $data
     * @param null $client
     */
    public function push($code, $action, $data, $client = null)
    {
        $resp = [
            'code'   => $code,
            'action' => $action,
            'data'   => $data,
        ];
        $client->send(json_encode($resp));
    }

    /**
     * @param \Wrench\Application\Payload    $payload
     * @param \Wrench\Application\Connection $connection
     * @throws \Exception
     */
    public function onData($payload, $connection)
    {
        $data       = json_decode($payload, true);
        $resque_srv = new ResqueService();
        if (method_exists($resque_srv, $data['action'])) {
            if (empty($data['params'])) {
                $data['params'] = null;
            }
            $result = $resque_srv->$data['action']($data['params']);
            if (!empty($result)) {
                $this->push(0, $data['action'], $result, $connection);
            }
        } else {
            throw new \Exception('Method is not exists!');
        }
    }
}