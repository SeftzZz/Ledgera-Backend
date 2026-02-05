<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\HotelModel;

class Users extends BaseAdminController
{
    protected $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->userModel = new UserModel();
        $this->hotelModel = new HotelModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Users',
            'hotels' => $this->hotelModel
                ->where('deleted_at', null)
                ->orderBy('hotel_name', 'ASC')
                ->findAll()
        ];

        return view('admin/users/index', $data);
    }

    // ===============================
    // DATATABLE SERVER SIDE
    // ===============================
    public function datatable()
    {
        $request = service('request');

        $searchValue = $request->getPost('search')['value'] ?? null;
        $length = (int) $request->getPost('length');
        $start  = (int) $request->getPost('start');

        $order = $request->getPost('order');
        $orderColumns = [null, null, 'name', 'hotel_id', 'role', 'email', 'phone', 'is_active', null];

        // mapping SEARCH LABEL → ROLE
        $roleSearchMap = [
            'admin hw'   => 'admin',
            'mitra'      => 'worker',
            'user hr'    => 'hotel_hr',
            'user fo'    => 'hotel_fo',
            'user hk'    => 'hotel_hk',
            'user fnbs'  => 'hotel_fnb_service',
            'user fnbp'  => 'hotel_fnb_production',
        ];

        // ROLE FILTER
        $userRole = session()->get('user_role');
        $hotelId = session()->get('hotel_id');

        // QUERY FILTERED (COUNT)
        $countQuery = $this->userModel
            ->join('hotels', 'hotels.id = users.hotel_id', 'left')
            ->where('users.deleted_at', null);

        if ($userRole === 'hotel_hr') {
            $countQuery
                ->where('users.hotel_id', $hotelId)
                ->where('users.role !=', 'worker');
        }

        if ($searchValue) {
            $keyword = strtolower(trim($searchValue));

            $countQuery->groupStart()
                ->like('users.name', $searchValue)
                ->orLike('users.email', $searchValue)
                ->orLike('users.phone', $searchValue);

            // CARI ROLE DARI LABEL
            if (isset($roleSearchMap[$keyword])) {
                $countQuery->orWhere('users.role', $roleSearchMap[$keyword]);
            }

            $countQuery->groupEnd();
        }

        $recordsFiltered = $countQuery->countAllResults();

        // QUERY TOTAL
        $totalQuery = $this->userModel
            ->join('hotels', 'hotels.id = users.hotel_id', 'left')
            ->where('users.deleted_at', null);

        if ($userRole === 'hotel_hr') {
            $totalQuery
                ->where('users.hotel_id', $hotelId)
                ->where('users.role !=', 'worker');
        }

        $recordsTotal = $totalQuery->countAllResults();

        // QUERY DATA (NEW BUILDER!)
        $dataQuery = $this->userModel
            ->select('users.*, hotels.hotel_name')
            ->join('hotels', 'hotels.id = users.hotel_id', 'left')
            ->where('users.deleted_at', null);

        if ($userRole === 'hotel_hr') {
            $dataQuery
                ->where('users.hotel_id', $hotelId)
                ->where('users.role !=', 'worker');
        }

        if ($searchValue) {
            $keyword = strtolower(trim($searchValue));

            $dataQuery->groupStart()
                ->like('users.name', $searchValue)
                ->orLike('users.email', $searchValue)
                ->orLike('users.phone', $searchValue);

            if (isset($roleSearchMap[$keyword])) {
                $dataQuery->orWhere('users.role', $roleSearchMap[$keyword]);
            }

            $dataQuery->groupEnd();
        }

        if ($order) {
            $idx = (int) $order[0]['column'];
            if (!empty($orderColumns[$idx])) {
                $dataQuery->orderBy($orderColumns[$idx], $order[0]['dir']);
            }
        }

        $data = $dataQuery
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        // =============================
        // FORMAT DATA
        // =============================
        $result = [];
        $no = $start + 1;
        
        foreach ($data as $row) {
            // Ganti nama kolom role
            $roleMap = [
                'admin'                  => 'Admin HW',
                'worker'                 => 'Mitra',
                'hotel_hr'               => 'User HR',
                'hotel_fo'               => 'User FO',
                'hotel_hk'               => 'User HK',
                'hotel_fnb_service'      => 'User FnBS',
                'hotel_fnb_production'   => 'User FnBP',
            ];
            $role = $row['role'];

            // bikin badge di kolom status
            $status = strtolower($row['is_active']);
            $badgeStatus = match ($status) {
                'active'   => '<span class="badge bg-label-success">Active</span>',
                'inactive' => '<span class="badge bg-label-danger">Inactive</span>',
                default    => '<span class="badge bg-label-secondary">' . ucfirst(esc($status)) . '</span>',
            };

            // kondisi tombol delete jika session sama dengan id
            $loginUserId = session()->get('user_id');
            $actionBtn = '
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-icon btn-primary btn-edit" data-id="'.$row['id'].'" title="Edit">
                        <i class="ti ti-pencil"></i>
                    </button>
            ';
            if ($loginUserId != $row['id']) {
                $actionBtn .= '
                    <button class="btn btn-sm btn-icon btn-danger btn-delete" data-id="'.$row['id'].'" title="Delete">
                        <i class="ti ti-trash"></i>
                    </button>
                ';
            }
            $actionBtn .= '</div>';

            $result[] = [
                'no_urut'       => $no++.'.',
                'name_user'     => esc($row['name']),
                'hotel_name'    => esc($row['hotel_name'] ?? '-'),
                'role_user'     => $roleMap[$role] ?? ucfirst(esc($role)),
                'email_user'    => esc($row['email']),
                'hp_user'       => esc($row['phone']),
                'status_user'   => $badgeStatus,
                'photo_user'    => esc($row['photo']),
                'action'        => $actionBtn
            ];
        }

        return $this->response->setJSON([
            'draw'            => (int) $request->getPost('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $result
        ]);
    }

    public function store()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $sessionRole = session()->get('user_role');
        $hotelId    = $this->request->getPost('hotel_id');

        if ($sessionRole === 'hotel_hr') {
            $hotelId = session()->get('hotel_id'); // paksa dari session
        }

        $data = [
            'name'       => $this->request->getPost('name_user'),
            'hotel_id'   => $this->request->getPost('hotel_user'),
            'email'      => $this->request->getPost('email_user'),
            'phone'      => $this->request->getPost('hp_user'),
            'role'       => $this->request->getPost('role_user'),
            'is_active'  => $this->request->getPost('status_user'),
            'password'   => password_hash($this->request->getPost('pass_user'), PASSWORD_DEFAULT),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id')
        ];

        $file = $this->request->getFile('foto_user');
        if ($file && $file->isValid()) {
            $name = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profiles', $name);
            $data['photo'] = 'uploads/profiles/' . $name;
        }

        $this->userModel->insert($data);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'User successfully added'
        ]);
    }

    public function getById()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $id = $this->request->getPost('id');

        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $user
        ]);
    }

    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $id = $this->request->getPost('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $data = [
            'name'       => $this->request->getPost('name_user'),
            'hotel_id'   => $this->request->getPost('hotel_user'),
            'role'       => $this->request->getPost('role_user'),
            'phone'      => $this->request->getPost('hp_user'),
            'is_active'  => $this->request->getPost('status_user'),
            'updated_by' => session()->get('user_id')
        ];

        // GANTI PASS
        if ($this->request->getPost('pass_user')) {
            $data['password'] = password_hash(
                $this->request->getPost('pass_user'),
                PASSWORD_DEFAULT
            );
        }

        // UPLOAD LOGO
        $file = $this->request->getFile('foto');
        if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            // VALIDASI FILE
            if (! $file->isValid() || ! in_array($file->getMimeType(), [
                'image/png',
                'image/jpeg',
                'image/webp'
            ])) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Photo format must be PNG, JPG or WEBP'
                ]);
            }
            // UPLOAD FILE
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profiles/', $newName);

            // HAPUS FOTO LAMA
            if (!empty($user['photo']) && file_exists(FCPATH . $user['photo'])) {
                unlink(FCPATH . $user['photo']);
            }

            // SIMPAN DENGAN PATH
            $data['photo'] = 'uploads/profiles/' . $newName;
        }

        if ($this->userModel->update($id, $data)) {
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Data updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Gagal memperbarui data'
        ]);
    }

    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID not valid'
            ]);
        }

        $data = [
            'updated_by'  => session()->get('user_id'),
            'deleted_at'  => date('Y-m-d H:i:s'),
            'deleted_by'  => session()->get('user_id')
        ];

        $deleted = $this->userModel->update($id, $data); // SOFT DELETE

        if ($deleted) {
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Data deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Failed to delete data'
        ]);
    }
}
