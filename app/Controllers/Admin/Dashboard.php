<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\JobModel;
use App\Models\JobApplicationModel;
use App\Models\JobAttendanceModel;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        
        $this->job   = new JobModel();
        $this->apply = new JobApplicationModel();
        $this->attendance  = new JobAttendanceModel();
    }

    public function index()
    {
        return view('admin/dashboard', [
            'title' => 'Dashboard'
        ]);
    }

    /**
     * ============================
     * JOB CALENDAR (FULLCALENDAR)
     * ============================
     * GET /api/jobs/calendar
     */
    public function calendar()
    {
        $jobs = $this->job
            ->where('deleted_at', null)
            ->where('status', 'open')
            ->orderBy('job_date_start', 'ASC')
            ->findAll();

        $events = [];

        foreach ($jobs as $job) {

            $events[] = [
                'id'    => $job['id'],
                'title' => $job['position'],
                'start' => $job['job_date_start'] . 'T' . $job['start_time'],
                'end'   => $job['job_date_end'] . 'T' . $job['end_time'],
                'allDay'=> false,
                'extendedProps' => [
                    'calendar' => $job['position'], // ⬅️ INI PENTING
                    'fee'      => $job['fee'],
                    'location' => $job['location'],
                    'status'   => $job['status'],
                    'job_id'   => $job['id']
                ]

            ];
        }

        return $this->response->setJSON($events);
    }

    public function attendance($jobId)
    {
        $rows = $this->db->table('job_attendances')
            ->where('job_id', $jobId)
            ->where('deleted_at', '0000-00-00 00:00:00')
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($rows);
    }

    public function attendanceByJob($jobId)
    {
        $data = $this->attendance
            ->select([
                'job_attendances.id',
                'job_attendances.job_id',
                'job_attendances.user_id',
                'users.name as user_name',
                'job_attendances.type',
                'job_attendances.latitude',
                'job_attendances.longitude',
                'job_attendances.photo_path',
                'job_attendances.device_info',
                'job_attendances.created_at'
            ])
            ->join('users', 'users.id = job_attendances.user_id', 'left')
            ->where('job_attendances.job_id', $jobId)
            ->where('job_attendances.deleted_at', '0000-00-00 00:00:00')
            ->orderBy('job_attendances.created_at', 'ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }
}