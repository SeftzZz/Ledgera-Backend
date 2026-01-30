<?php

namespace App\Models;

use CodeIgniter\Model;

class JobAttendanceModel extends Model
{
    protected $table            = 'job_attendances';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'job_id',
        'application_id',
        'user_id',
        'type',
        'latitude',
        'longitude',
        'photo_path',
        'device_info',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $useTimestamps = false;

    // ============================
    // VALIDATION (OPTIONAL)
    // ============================
    protected $validationRules = [
        'job_id'         => 'required|integer',
        'application_id' => 'required|integer',
        'user_id'        => 'required|integer',
        'type'           => 'required|in_list[checkin,checkout]',
        'latitude'       => 'required|decimal',
        'longitude'      => 'required|decimal',
    ];

    protected $validationMessages = [
        'type' => [
            'in_list' => 'Type must be checkin or checkout'
        ]
    ];

    // ============================
    // HELPERS
    // ============================

    /**
     * Check apakah user sudah check-in hari ini
     */
    public function hasCheckinToday($userId, $jobId, $applicationId)
    {
        return $this->where('user_id', $userId)
            ->where('job_id', $jobId)
            ->where('application_id', $applicationId)
            ->where('type', 'checkin')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->first();
    }

    /**
     * Check apakah user sudah check-out hari ini
     */
    public function hasCheckoutToday($userId, $jobId, $applicationId)
    {
        return $this->where('user_id', $userId)
            ->where('job_id', $jobId)
            ->where('application_id', $applicationId)
            ->where('type', 'checkout')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->first();
    }

    /**
     * Ambil attendance harian user (untuk calendar)
     */
    public function dailyAttendance($userId, $date)
    {
        return $this->select('
                job_attendances.*,
                jobs.position,
                jobs.job_date_start,
                jobs.job_date_end,
                hotels.hotel_name
            ')
            ->join('jobs', 'jobs.id = job_attendances.job_id')
            ->join('hotels', 'hotels.id = jobs.hotel_id', 'left')
            ->where('job_attendances.user_id', $userId)
            ->where('DATE(job_attendances.created_at)', $date)
            ->orderBy('job_attendances.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Attendance per job
     */
    public function attendanceByJob($userId, $jobId)
    {
        return $this->where('user_id', $userId)
            ->where('job_id', $jobId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }
}
