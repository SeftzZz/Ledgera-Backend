<?php

namespace App\Controllers\Admin;

use App\Models\HotelModel;

class Hotels extends BaseAdminController
{
    protected $hotelModel;
    protected $job;
    protected $apply;
    protected $attendance;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->hotelModel = new HotelModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Hotels'
        ];

        return view('admin/hotels/index', $data);
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
        $orderColumns = [null, null, 'hotel_name', 'location', 'latitude', 'longitude', 'website', null];

        // =============================
        // QUERY FILTERED (COUNT)
        // =============================
        $countQuery = $this->hotelModel->where('deleted_at', null);

        if ($searchValue) {
            $countQuery->groupStart()
                ->like('hotel_name', $searchValue)
                ->orLike('location', $searchValue)
                ->orLike('website', $searchValue)
                ->orLike('latitude', $searchValue)
                ->orLike('longitude', $searchValue)
            ->groupEnd();
        }

        $recordsFiltered = $countQuery->countAllResults();

        // =============================
        // QUERY TOTAL
        // =============================
        $recordsTotal = $this->hotelModel
            ->where('deleted_at', null)
            ->countAllResults();

        // =============================
        // QUERY DATA (NEW BUILDER!)
        // =============================
        $dataQuery = $this->hotelModel->where('deleted_at', null);

        if ($searchValue) {
            $dataQuery->groupStart()
                ->like('hotel_name', $searchValue)
                ->orLike('location', $searchValue)
                ->orLike('website', $searchValue)
                ->orLike('latitude', $searchValue)
                ->orLike('longitude', $searchValue)
            ->groupEnd();
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
            $result[] = [
                'no_urut'    => $no++.'.',
                'hotel_name' => esc($row['hotel_name']),
                'location'   => esc($row['location']),
                'latitude'   => $row['latitude'],
                'longitude'  => $row['longitude'],
                'website'    => esc($row['website']),
                'logo'       => esc($row['logo']),
                'action' => '
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-icon btn-primary btn-edit" data-id="'.$row['id'].'" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-danger btn-delete" data-id="'.$row['id'].'" title="Delete">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                '

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

        $data = [
            'hotel_name' => $this->request->getPost('hotel_name'),
            'location'   => $this->request->getPost('location'),
            'latitude'   => $this->request->getPost('latitude'),
            'longitude'  => $this->request->getPost('longitude'),
            'website'    => $this->request->getPost('website'),
            'description'=> $this->request->getPost('desc'),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id')
        ];

        $file = $this->request->getFile('logo');
        if ($file && $file->isValid()) {
            $name = $file->getRandomName();
            $file->move(FCPATH . 'images', $name);
            $data['logo'] = 'images/' . $name;
        }

        $this->hotelModel->insert($data);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Hotel successfully added'
        ]);
    }


    public function getById()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $id = $this->request->getPost('id');

        $hotel = $this->hotelModel->find($id);

        if (!$hotel) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $hotel
        ]);
    }

    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $id = $this->request->getPost('id');
        $hotel = $this->hotelModel->find($id);

        if (!$hotel) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $data = [
            'hotel_name' => $this->request->getPost('hotel_name'),
            'location'   => $this->request->getPost('location'),
            'latitude'   => $this->request->getPost('latitude'),
            'longitude'  => $this->request->getPost('longitude'),
            'website'    => $this->request->getPost('website'),
            'description'=> $this->request->getPost('desc'),
            'updated_by' => session()->get('user_id')
        ];

        // UPLOAD LOGO
        $file = $this->request->getFile('logo');
        if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            // VALIDASI FILE
            if (! $file->isValid() || ! in_array($file->getMimeType(), [
                'image/png',
                'image/jpeg',
                'image/webp'
            ])) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Logo format must be PNG, JPG or WEBP'
                ]);
            }
            // UPLOAD FILE
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'images', $newName);

            // HAPUS LOGO LAMA
            if (!empty($hotel['logo']) && file_exists(FCPATH . $hotel['logo'])) {
                unlink(FCPATH . $hotel['logo']);
            }

            // SIMPAN PATH
            $data['logo'] = 'images/' . $newName;
        }

        if ($this->hotelModel->update($id, $data)) {
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

        $deleted = $this->hotelModel->update($id, $data); // SOFT DELETE

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
