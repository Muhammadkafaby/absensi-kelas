<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ActivityLogController extends BaseController
{
    /**
     * Display activity logs (admin only)
     */
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $logModel = new ActivityLogModel();

        // Get filter parameters
        $action = $this->request->getGet('action');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Build query
        $builder = $logModel->select('activity_logs.*, users.username, users.name as user_name, users.role')
                            ->join('users', 'users.id = activity_logs.user_id', 'left')
                            ->orderBy('activity_logs.created_at', 'DESC');

        if ($action) {
            $builder->where('activity_logs.action', $action);
        }

        if ($dateFrom) {
            $builder->where('activity_logs.created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('activity_logs.created_at <=', $dateTo . ' 23:59:59');
        }

        $logs = $builder->findAll(200);

        // Get unique actions for filter
        $actions = $logModel->select('action')
                            ->distinct()
                            ->orderBy('action', 'ASC')
                            ->findAll();

        $data = [
            'title'   => 'Log Aktivitas',
            'logs'    => $logs,
            'actions' => $actions,
            'filters' => [
                'action'    => $action,
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
            ],
        ];

        return view('activity_logs/index', $data);
    }

    /**
     * Clear old logs
     */
    public function clearOld()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $days = $this->request->getPost('days') ?: 90;

        $logModel = new ActivityLogModel();
        $deleted = $logModel->deleteOldLogs($days);

        session()->setFlashdata('success', "Berhasil menghapus log lebih dari $days hari");
        return redirect()->to('/activity-logs');
    }
}
