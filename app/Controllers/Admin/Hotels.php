<?php

namespace App\Controllers\Admin;

use App\Models\HotelModel;

class Hotels extends BaseAdminController
{
    protected $hotelModel;

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
                        <button class="btn btn-sm btn-primary btn-edit" data-id="'.$row['id'].'">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row['id'].'">
                            Delete
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
