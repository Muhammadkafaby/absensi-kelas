<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    /**
     * Log an activity
     */
    public function log($action, $description = null, $userId = null)
    {
        $request = service('request');

        $data = [
            'user_id'     => $userId ?? session()->get('user_id'),
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $request->getIPAddress(),
            'user_agent'  => $request->getUserAgent()->getAgentString(),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data);
    }

    /**
     * Get logs with user info
     */
    public function getLogsWithUser($limit = 100, $offset = 0)
    {
        return $this->select('activity_logs.*, users.username, users.name as user_name, users.role')
                    ->join('users', 'users.id = activity_logs.user_id', 'left')
                    ->orderBy('activity_logs.created_at', 'DESC')
                    ->findAll($limit, $offset);
    }

    /**
     * Get logs by user
     */
    public function getLogsByUser($userId, $limit = 50)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    /**
     * Get recent logs
     */
    public function getRecentLogs($limit = 50)
    {
        return $this->getLogsWithUser($limit);
    }

    /**
     * Delete old logs (older than specified days)
     */
    public function deleteOldLogs($days = 90)
    {
        $date = date('Y-m-d H:i:s', strtotime("-$days days"));
        return $this->where('created_at <', $date)->delete();
    }
}
