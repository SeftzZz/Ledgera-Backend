<?php

namespace App\Controllers\Admin;

use App\Models\JobApplicationModel;
use CodeIgniter\Controller;

class Application extends BaseAdminController
{
    protected $application;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->application = new JobApplicationModel();
    }

    public function index()
    {
        return view('admin/application/index', [
            'title' => 'Application'
        ]);
    }

    /**
     * ============================
     * DATATABLE JOB APPLICATION
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
            'users.name',
            'users.email',
            'jobs.position',
            'job_applications.status',
            'job_applications.applied_at'
        ];

        $db = \Config\Database::connect();

        $baseBuilder = $db->table('job_applications')
            ->select("
                job_applications.id,
                job_applications.user_id,
                job_applications.status,
                job_applications.applied_at,
                users.name   AS worker_name,
                users.email AS worker_email,
                jobs.position,
                jobs.fee
            ")
            ->join('users', 'users.id = job_applications.user_id AND users.deleted_at IS NULL', 'left')
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->where('job_applications.deleted_at IS NULL');

        // SEARCH
        if ($search) {
            $baseBuilder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('jobs.position', $search)
                ->orLike('job_applications.status', $search)
            ->groupEnd();
        }

        // COUNT
        $countBuilder    = clone $baseBuilder;
        $recordsFiltered = count($countBuilder->get()->getResultArray());
        $recordsTotal    = $recordsFiltered;

        // ORDER
        if ($order) {
            $idx = (int) $order[0]['column'];
            if (!empty($columns[$idx])) {
                $baseBuilder->orderBy($columns[$idx], $order[0]['dir']);
            }
        } else {
            $baseBuilder->orderBy('job_applications.applied_at', 'DESC');
        }

        // LIMIT
        if ($length > 0) {
            $baseBuilder->limit($length, $start);
        }

        $rows = $baseBuilder->get()->getResultArray();

        $data = [];
        $no   = $start + 1;

        foreach ($rows as $row) {

            $status = strtolower($row['status']);

            $badgeStatus = match ($status) {
                'accepted' => '<span class="badge bg-label-success">Accepted</span>',
                'pending'  => '<span class="badge bg-label-warning">Pending</span>',
                'rejected' => '<span class="badge bg-label-danger">Rejected</span>',
                default    => '<span class="badge bg-label-secondary">' . ucfirst(esc($status)) . '</span>',
            };

            $data[] = [
                'no'      => $no++ . '.',
                'worker'  => esc($row['worker_name']),
                'email'   => esc($row['worker_email']),
                'job'     => esc($row['position']),
                'fee'     => number_format($row['fee'], 0, ',', '.'),
                'status'  => $badgeStatus, // ✅ HTML badge
                'applied' => date('d-m-Y H:i', strtotime($row['applied_at'])),
                'action'  => '
                    <button 
                        class="btn btn-sm btn-info btn-worker-detail"
                        data-user="' . (int)$row['user_id'] . '"
                        data-application="' . $row['id'] . '">
                        <i class="ti ti-user"></i>
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

    public function workerDetail($applicationId)
    {
        $db = \Config\Database::connect();

        // ambil application + user
        $application = $db->table('job_applications ja')
            ->select('
                ja.id AS application_id,
                ja.status,
                u.id AS user_id,
                u.name,
                u.email,
                u.phone,
                wp.gender,
                wp.birth_date,
                wp.address,
                wp.bio,
                u.photo,
            ')
            ->join('users u', 'u.id = ja.user_id')
            ->join('worker_profiles wp', 'wp.user_id = u.id', 'left')
            ->where('ja.id', $applicationId)
            ->get()
            ->getRowArray();

        if (!$application) {
            return $this->response->setJSON(['status' => false]);
        }

        $userId = $application['user_id'];

        return $this->response->setJSON([
            'status' => true,

            // 🔑 INI KUNCI UTAMA
            'application' => [
                'id'     => $application['application_id'],
                'status' => $application['status']
            ],

            'user' => $application,

            'documents'   => $db->table('worker_documents')->where('user_id', $userId)->get()->getResultArray(),
            'educations'  => $db->table('worker_educations')->where('user_id', $userId)->where('deleted_at', null)->orderBy('is_current', 'DESC')->get()->getResultArray(),
            'experiences' => $db->table('worker_experiences')->where('user_id', $userId)->where('deleted_at', null)->orderBy('is_current', 'DESC')->get()->getResultArray(),
            'skills'      => $db->table('worker_skills ws')
                                ->select('s.name')
                                ->join('skills s', 's.id = ws.skill_id')
                                ->where('ws.user_id', $userId)
                                ->get()->getResultArray(),
            'links'       => $db->table('worker_links')->where('user_id', $userId)->get()->getResultArray(),
            'rating'      => $db->table('worker_ratings')->where('user_id', $userId)->get()->getRowArray()
        ]);
    }

    public function updateStatus()
    {
        $db = \Config\Database::connect();

        $id     = (int) $this->request->getPost('application_id');
        $status = strtolower($this->request->getPost('status'));

        // user login (admin / HR)
        $user = session()->get('user_id');

        if (!in_array($status, ['accepted', 'rejected'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid status'
            ]);
        }

        // ambil data lama
        $current = $db->table('job_applications')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$current) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Application not found'
            ]);
        }

        // ❌ tidak boleh update jika sudah completed
        if ($current['status'] === 'completed') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Application already completed'
            ]);
        }

        // DATA UPDATE
        $update = [
            'status' => $status
        ];

        if ($status === 'accepted') {
            $update['accepted_at'] = date('Y-m-d H:i:s');
            $update['accepted_by'] = session()->get('user_id');
        }

        if ($status === 'rejected') {
            $update['accepted_at'] = null;
            $update['accepted_by'] = null;
        }

        $db->table('job_applications')
            ->where('id', $id)
            ->update($update);

        return $this->response->setJSON([
            'status' => true
        ]);
    }

}
