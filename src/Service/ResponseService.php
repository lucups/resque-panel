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
 * Class ResponseService
 * @package ResquePanel\Service
 */
class ResponseService extends BaseService
{
    const SORT_BY_TIME_ASC  = 1; // 按时间正序排列
    const SORT_BY_TIME_DESC = 2; // 按时间倒序排列

    private $server = null;
    private $frame  = null;

    public function __construct($server, $frame)
    {
        $this->server = $server;
        $this->frame  = $frame;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * @param $code
     * @param $action
     * @param $data
     */
    public function push($code, $action, $data)
    {
        $resp = [
            'code'   => $code,
            'action' => $action,
            'data'   => $data,
        ];
        $this->getServer()->push($this->getFrame()->fd, json_encode($resp));
    }

    /**
     * @param null $params
     * @return array
     */
    public function getNames($params = null)
    {
        return $this->getRedis()->sMembers('resque:queues');
    }

    /**
     * @param null $params
     */
    public function failedJobs($params = null)
    {
        $offset = 0;
        $limit  = 10;
        $sort   = self::SORT_BY_TIME_ASC;
        if (!empty($params['offset'])) {
            $offset = (int)$params['offset'];
        }
        if (!empty($params['limit'])) {
            $limit = (int)$params['limit'];
        }
        if (!empty($params['sort'])) {
            $sort = (int)$params['sort'];
        }

        $redis            = $this->getRedis();
        $failed_jobs_size = $redis->lLen('resque:failed');
        if ($sort == self::SORT_BY_TIME_DESC) {
            $failed_jobs = $redis->lRange('resque:failed', -$offset - $limit, -$offset - 1);
        } else {
            $failed_jobs = $redis->lRange('resque:failed', $offset, $offset + $limit - 1);
        }

        $decoded_failed_jobs = [];
        foreach ($failed_jobs as $failed_job) {
            $decoded_failed_job             = json_decode($failed_job, true);
            $decoded_failed_job['raw_data'] = $failed_job;
            $decoded_failed_jobs[]          = $decoded_failed_job;
        }
        $this->push(0, __FUNCTION__, ['failed_jobs' => $decoded_failed_jobs, 'failed_jobs_size' => $failed_jobs_size]);
    }

    /**
     * Return the data by timestamp (default is now).
     * @param null $params
     */
    public function queuesStatus($params = null)
    {
        $queue_name = $params['queue_name'];
        $redis      = $this->getRedis();
        $data       = [
            'time' => date('Y-m-d H:i:s'),
            'val'  => $redis->lLen('resque:queue:' . $queue_name),
        ];
        $this->push(0, __FUNCTION__, $data);
    }

    /**
     * @param null $params
     */
    public function workersStatistics($params = null)
    {
        $redis = $this->getRedis();

        $queues      = [];
        $queue_names = $redis->sMembers('resque:queues');
        foreach ($queue_names as $queue_name) {
            $queues[] = [
                'name'   => $queue_name,
                'length' => $redis->lLen('resque:queue:' . $queue_name),
            ];
        }

        $workers      = [];
        $worker_names = $redis->sMembers('resque:workers');
        foreach ($worker_names as $worker_name) {
            $workers[] = [
                'name'      => $worker_name,
                'stared'    => $redis->get('resque:worker:' . $worker_name),
                'failed'    => $redis->get('resque:stat:failed:' . $worker_name),
                'processed' => $redis->get('resque:stat:processed:' . $worker_name),
            ];
        }
        $data = [
            'queues'  => $queues,
            'workers' => $workers,
        ];
        $this->push(0, __FUNCTION__, $data);
    }

    /**
     * @param null $params
     */
    public function jobsStatistics($params = null)
    {
        $redis = $this->getRedis();
        $data  = [
            'processed_jobs' => $redis->get('resque:stat:processed'),
            'failed_jobs'    => $redis->get('resque:stat:failed'),
        ];
        $this->push(0, __FUNCTION__, $data);
    }

    public function history()
    {

    }
}