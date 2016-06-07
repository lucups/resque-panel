<?php
/**
 * AppService.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/6/7 11:59
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel;

/**
 * Class ResqueService
 * @package ResquePanel
 */
class ResqueService
{
    use AppContext;

    const SORT_BY_TIME_ASC  = 1; // 按时间正序排列
    const SORT_BY_TIME_DESC = 2; // 按时间倒序排列

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
     * @return array
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
            krsort($failed_jobs);
            $failed_jobs = array_values($failed_jobs);
        } else {
            $failed_jobs = $redis->lRange('resque:failed', $offset, $offset + $limit - 1);
        }

        $decoded_failed_jobs = [];
        foreach ($failed_jobs as $failed_job) {
            $decoded_failed_job             = json_decode($failed_job, true);
            $decoded_failed_job['raw_data'] = $failed_job;
            $decoded_failed_jobs[]          = $decoded_failed_job;
        }

        return ['failed_jobs' => $decoded_failed_jobs, 'failed_jobs_size' => $failed_jobs_size];
    }

    /**
     * @param null $params
     * @return array
     */
    public function queuesStatus($params = null)
    {
        if (!empty($params['queue_name'])) {
            $queue_name = $params['queue_name'];
            $redis      = $this->getRedis();
            return [
                'time' => date('H:i:s'),
                'val'  => $redis->lLen('resque:queue:' . $queue_name),
            ];
        } else {
            return [
                'time' => date('H:i:s'),
                'val'  => 0,
            ];
        }
    }

    /**
     * @param null $params
     * @return array
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
        return [
            'queues'  => $queues,
            'workers' => $workers,
        ];
    }

    public function jobsStatistics($params = null)
    {
        $redis = $this->getRedis();
        return [
            'processed_jobs' => $redis->get('resque:stat:processed'),
            'failed_jobs'    => $redis->get('resque:stat:failed'),
        ];
    }

    public function history()
    {
        return [];
    }
}