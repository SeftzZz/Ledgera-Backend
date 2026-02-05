<?php

namespace App\Controllers\Admin;

use App\Models\JobModel;
use CodeIgniter\Controller;

class JobVacancies extends BaseAdminController
{
    protected $job;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->job = new JobModel();
    }

    public function index()
    {
        return view('admin/job-vacancies/index', [
            'title' => 'Job Vacancies'
        ]);
    }

    /**
     * ============================
     * DATATABLE JOB VACANCIES
     * ============================
     */
    public function datatable()
    {
        $request = service('request');

        $search = $request->getPost('search')['value'] ?? null;
        $length = (int) $request->getPost('length');
        $start  = (int) $request->getPost('start');
        $draw   = (int) $request->getPost('draw');
        $order  = $request->getPost('order');

        $columns = [
            null,
            'jobs.position',
            'jobs.category',
            'jobs.job_date_start',
            'jobs.start_time',
            'jobs.location',
            'jobs.fee',
            'jobs.status'
        ];

        $db = \Config\Database::connect();

        $builder = $db->table('jobs')
            ->select("
                jobs.id,
                jobs.position,
                jobs.category,
                jobs.job_date_start,
                jobs.job_date_end,
                jobs.start_time,
                jobs.end_time,
                jobs.location,
                jobs.fee,
                jobs.status
            ")
            ->where('jobs.deleted_at IS NULL');

        // 🔍 SEARCH
        if ($search) {
            $builder->groupStart()
                ->like('jobs.position', $search)
                ->orLike('jobs.category', $search)
                ->orLike('jobs.location', $search)
                ->orLike('jobs.status', $search)
            ->groupEnd();
        }

        // 📊 COUNT
        $countBuilder    = clone $builder;
        $recordsFiltered = $countBuilder->countAllResults(false);
        $recordsTotal    = $recordsFiltered;

        // ↕️ ORDER
        if ($order) {
            $idx = (int) $order[0]['column'];
            if (!empty($columns[$idx])) {
                $builder->orderBy($columns[$idx], $order[0]['dir']);
            }
        } else {
            $builder->orderBy('jobs.job_date_start', 'DESC');
        }

        // 📄 LIMIT
        if ($length > 0) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $data = [];
        $no   = $start + 1;

        foreach ($rows as $row) {

            $badgeStatus = $row['status'] === 'open'
                ? '<span class="badge bg-label-success">Open</span>'
                : '<span class="badge bg-label-secondary">Closed</span>';

            $data[] = [
                'no'       => $no++ . '.',
                'position' => esc($row['position']),
                'category' => ucfirst(str_replace('_', ' ', $row['category'])),
                'date'     => date('d-m-Y', strtotime($row['job_date_start']))
                              . ' s/d ' .
                              date('d-m-Y', strtotime($row['job_date_end'])),
                'time'     => substr($row['start_time'], 0, 5)
                              . ' - ' .
                              substr($row['end_time'], 0, 5),
                'location' => esc($row['location']),
                'fee'      => number_format($row['fee'], 0, ',', '.'),
                'status'   => $badgeStatus,
                'action'   => '
                    <button 
                        class="btn btn-sm btn-info btn-detail-job"
                        data-id="' . (int)$row['id'] . '">
                        <i class="ti ti-eye"></i>
                    </button>
                '
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        // VALIDASI TIME
        if (strtotime($data['end_time']) <= strtotime($data['start_time'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'End time must be greater than start time'
            ]);
        }

        // VALIDASI POSITION (MULTI SELECT)
        if (empty($data['position']) || !is_array($data['position'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Please select at least one job position'
            ]);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('jobs');

        $now = date('Y-m-d H:i:s');
        $userId = session()->get('user_id');
        $hotelId = session()->get('hotel_id');

        foreach ($data['position'] as $position) {

            if (!$position) continue; // skip kosong

            $insert = [
                'hotel_id'          => $hotelId,
                'position'          => $position, // 🔥 1 posisi = 1 row
                'category'          => $data['category'],
                'job_date_start'    => $data['job_date_start'],
                'job_date_end'      => $data['job_date_end'],
                'start_time'        => $data['start_time'],
                'end_time'          => $data['end_time'],
                'location'          => $data['location'],
                'fee'               => $data['fee'],
                'description'       => $data['description'] ?? null,
                'requirement_skill' => $data['requirement_skill'] ?? null,
                'status'            => 'open',
                'created_at'        => $now,
                'created_by'        => $userId
            ];

            $builder->insert($insert);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Job(s) have been successfully created'
        ]);
    }

    public function skills()
    {
        $search = $this->request->getGet('q');

        $db = \Config\Database::connect();

        $builder = $db->table('skills')
            ->select('id, name')
            ->where('deleted_at', null);

        if ($search) {
            $builder->like('name', $search);
        }

        $skills = $builder
            ->orderBy('name', 'ASC')
            ->limit(20)
            ->get()
            ->getResultArray();

        $results = [];

        foreach ($skills as $skill) {
            $results[] = [
                'id'   => $skill['name'], // ⬅️ value yang dikirim ke form
                'text' => $skill['name']
            ];
        }

        return $this->response->setJSON([
            'results' => $results
        ]);
    }

}
