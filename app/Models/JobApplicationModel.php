<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationModel extends Model
{
    protected $table = 'job_applications';
    protected $allowedFields = [
        'job_id',
        'user_id',
        'status',
        'applied_at'
    ];
    protected $useTimestamps = false;

    public function workerHistory($userId)
	{
	    return $this->select(
	            'job_applications.id as application_id,
	             job_applications.status as application_status,
	             job_applications.applied_at,
	             jobs.id as job_id,
	             jobs.hotel_id,
	             jobs.position,
	             jobs.job_date_start,
	             jobs.job_date_end,
	             jobs.start_time,
	             jobs.end_time,
	             jobs.fee,
	             jobs.location'
	        )
	        ->join('jobs', 'jobs.id = job_applications.job_id')
	        ->where('job_applications.user_id', $userId)
	        ->orderBy('job_applications.applied_at', 'DESC')
	        ->findAll();
	}

}
