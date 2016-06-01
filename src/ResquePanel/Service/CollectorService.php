<?php
/**
 * DemoService.php
 *
 * @author      Tony Lu <dev@tony.engineer>
 * @create      16/5/26 17:45
 * @license     http://www.opensource.org/licenses/mit-license.php
 */

namespace ResquePanel\Service;

/**
 * Class CollectorService
 * @package ResquePanel\Service
 */
class CollectorService extends BaseService
{
    const REDIS_KEY_RESQUE_PANEL = 'resque-panel:';

    /**
     * Persist current minute resque status.
     */
    public function persistCurrentMinuteStatus()
    {
        $redis = $this->getRedis();
        $now   = date('Y-m-d H:i');

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
        $queues      = [];
        $queue_names = $redis->sMembers('resque:queues');
        foreach ($queue_names as $queue_name) {
            $queues[] = [
                'name' => $queue_name,
            ];
        }

        $data = [
            'processed_jobs_amount' => $redis->get('resque:stat:processed'),
            'failed_jobs_amount'    => $redis->get('resque:stat:failed'),
            'pending_jobs_amount'   => 3,
            'queues'                => $queues,
            'workers'               => $workers,
        ];
        $data = json_encode($data);
        $this->getRedis()->hSet(self::REDIS_KEY_RESQUE_PANEL . 'history', $now, $data);
    }
}