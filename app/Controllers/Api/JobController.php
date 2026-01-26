<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\JobModel;
use App\Models\JobApplicationModel;

class JobController extends BaseController
{
    protected $job;
    protected $apply;

    public function __construct()
    {
        $this->job   = new JobModel();
        $this->apply = new JobApplicationModel();
    }

    /**
     * ============================
     * LIST JOB (OPEN)
     * ============================
     */
    public function index()
    {
        return $this->response->setJSON(
            $this->job
                ->where('status', 'open')
                ->orderBy('job_date', 'ASC')
                ->findAll()
        );
    }

    /**
     * ============================
     * JOB DETAIL
     * ============================
     */
    public function show($id)
    {
        $job = $this->job->find($id);

        if (!$job) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Job not found']);
        }

        return $this->response->setJSON($job);
    }

    /**
     * ============================
     * APPLY JOB
     * ============================
     */
    public function apply($jobId)
    {
        $user = $this->request->user;

        // cek job
        $job = $this->job->find($jobId);
        if (!$job || $job['status'] !== 'open') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Job not available']);
        }

        // cegah apply dobel
        $exists = $this->apply
            ->where('job_id', $jobId)
            ->where('user_id', $user->id)
            ->first();

        if ($exists) {
            return $this->response
                ->setStatusCode(409)
                ->setJSON(['message' => 'Already applied']);
        }

        $this->apply->insert([
            'job_id'    => $jobId,
            'user_id'   => $user->id,
            'status'    => 'pending',
            'applied_at'=> date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'message' => 'Apply success'
        ]);
    }

    /**
     * ============================
     * CREATE JOB (POSTMAN)
     * ============================
     * POST /api/jobs
     */
    public function create()
    {
        // Ambil data JSON / form-data
        $data = $this->request->getJSON(true) 
             ?? $this->request->getPost();

        // =========================
        // VALIDASI WAJIB
        // =========================
        $required = [
            'hotel_id',
            'position',
            'job_date_start',
            'job_date_end',
            'start_time',
            'end_time',
            'fee'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON([
                        'message' => "Field {$field} is required"
                    ]);
            }
        }

        // =========================
        // DATA INSERT
        // =========================
        $insert = [
            'hotel_id'         => $data['hotel_id'],
            'position'         => $data['position'],
            'job_date_start'   => $data['job_date_start'],
            'job_date_end'     => $data['job_date_end'],
            'start_time'       => $data['start_time'],
            'end_time'         => $data['end_time'],
            'category'         => $data['category'] ?? 'daily_worker',
            'fee'              => $data['fee'],
            'location'         => $data['location'] ?? null,
            'description'      => $data['description'] ?? null,
            'requirement_skill'=> $data['requirement_skill'] ?? null,
            'status'           => $data['status'] ?? 'open',
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $this->job->insert($insert);

        $jobs = $this->job
            ->select('
                jobs.*,
                hotel_name,
                hotels.logo  AS hotel_logo
            ')
            ->join('hotels', 'hotels.id = jobs.hotel_id', 'left')
            ->where('jobs.status', 'open')
            ->orderBy('jobs.job_date_start', 'ASC')
            ->findAll();

        service('wsEmitter')->jobsUpdated($jobs);

        return $this->response->setJSON([
            'message' => 'Job created successfully',
            'job_id'  => $this->job->getInsertID()
        ]);
    }

}
