<?php

namespace App\Controllers\Admin;

use App\Models\JobAttendanceModel;
use CodeIgniter\Controller;

class Attendance extends BaseAdminController
{
    protected $attendance;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->attendance = new JobAttendanceModel();
    }

    public function index()
    {
        return view('admin/attendance/index', [
            'title' => 'Attendance'
        ]);
    }

    // =========================================
    // DATATABLE ATTENDANCE (SUMMARY HARIAN)
    // =========================================
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
            null,
            'job_attendances.created_at',
            'users.name',
            'hotels.hotel_name',
            'jobs.position',
            null,
            null
        ];

        $db = \Config\Database::connect();

        $baseBuilder = $db->table('job_attendances')
            ->select("
                DATE(job_attendances.created_at) AS work_date,
                IFNULL(users.name, '-') AS worker_name,
                IFNULL(hotels.hotel_name, '-') AS hotel_name,
                IFNULL(jobs.position, '-') AS position,
                MIN(CASE WHEN job_attendances.type = 'checkin' THEN job_attendances.created_at END) AS checkin_time,
                MAX(CASE WHEN job_attendances.type = 'checkout' THEN job_attendances.created_at END) AS checkout_time,
                job_attendances.user_id,
                job_attendances.job_id
            ")
            ->join(
                'users',
                'users.id = job_attendances.user_id
                 AND users.deleted_at IS NULL
                 AND users.is_active = "active"',
                'left'
            )
            ->join('jobs', 'jobs.id = job_attendances.job_id', 'left')
            ->join('hotels', 'hotels.id = jobs.hotel_id', 'left')
            ->where('(job_attendances.deleted_at IS NULL OR job_attendances.deleted_at = "0000-00-00 00:00:00")', null, false)
            ->groupBy('job_attendances.user_id, job_attendances.job_id, DATE(job_attendances.created_at)');

        if ($search) {
            $baseBuilder->groupStart()
                ->like('users.name', $search)
                ->orLike('hotels.hotel_name', $search)
                ->orLike('jobs.position', $search)
            ->groupEnd();
        }

        $countBuilder = clone $baseBuilder;
        $recordsFiltered = count($countBuilder->get()->getResultArray());
        $recordsTotal    = $recordsFiltered;

        if ($order) {
            $idx = (int) $order[0]['column'];
            if (!empty($columns[$idx])) {
                $baseBuilder->orderBy($columns[$idx], $order[0]['dir']);
            }
        } else {
            $baseBuilder->orderBy('job_attendances.created_at', 'DESC');
        }

        if ($length > 0) {
            $baseBuilder->limit($length, $start);
        }

        $rows = $baseBuilder->get()->getResultArray();

        $data = [];
        $no = $start + 1;

        foreach ($rows as $row) {
            $checkin  = $row['checkin_time'];
            $checkout = $row['checkout_time'];

            $duration      = '-';
            $tenMinutesCnt = '-';
            $status        = 'Incomplete';

            if ($checkin && $checkout) {
                $seconds  = strtotime($checkout) - strtotime($checkin);
                $minutes  = floor($seconds / 60);

                $duration      = gmdate('H:i', $seconds);
                $tenMinutesCnt = floor($minutes / 10); // jumlah kelipatan 10 menit
                $status        = 'Complete';
            }

            $data[] = [
                'no'          => $no++.'.',
                'date'        => date('d-m-Y', strtotime($row['work_date'])),
                'worker'      => esc($row['worker_name']),
                'hotel'       => esc($row['hotel_name']),
                'job'         => esc($row['position']),
                'checkin'     => $checkin ? date('H:i', strtotime($checkin)) : '-',
                'checkout'    => $checkout ? date('H:i', strtotime($checkout)) : '-',
                'duration'    => $duration,
                'ten_minutes' => $tenMinutesCnt,
                'status'      => $status,
                'action'      => '
                    <button 
                        class="btn btn-sm btn-info btn-detail"
                        data-user="'.$row['user_id'].'"
                        data-job="'.$row['job_id'].'"
                        data-date="'.$row['work_date'].'">
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

    public function detail()
    {
        $req = $this->request;

        $userId = $req->getPost('user_id');
        $jobId  = $req->getPost('job_id');
        $date   = $req->getPost('date');

        $db = \Config\Database::connect();

        $rows = $db->table('job_attendances ja')
            ->select('
                ja.type,
                ja.created_at,
                ja.latitude,
                ja.longitude,
                ja.photo_path,
                u.name AS worker,
                h.hotel_name AS hotel,
                j.position AS job
            ')
            ->join('users u', 'u.id = ja.user_id', 'left')
            ->join('jobs j', 'j.id = ja.job_id', 'left')
            ->join('hotels h', 'h.id = j.hotel_id', 'left')
            ->where('ja.user_id', $userId)
            ->where('ja.job_id', $jobId)
            ->where('DATE(ja.created_at)', $date)
            ->where('(ja.deleted_at IS NULL OR ja.deleted_at = "0000-00-00 00:00:00")', null, false)
            ->orderBy('ja.created_at', 'ASC')
            ->get()
            ->getResultArray();

        if (!$rows) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data not found'
            ]);
        }

        $checkin  = null;
        $checkout = null;
        $lat = null;
        $lng = null;
        $photoIn  = null;
        $photoOut = null;

        foreach ($rows as $r) {
            if ($r['type'] === 'checkin') {
                $checkin  = $r['created_at'];
                $photoIn  = $r['photo_path'];
                $lat      = $r['latitude'];
                $lng      = $r['longitude'];
            }
            if ($r['type'] === 'checkout') {
                $checkout = $r['created_at'];
                $photoOut = $r['photo_path'];
            }
        }

        $duration = '-';
        $status   = 'Incomplete';

        if ($checkin && $checkout) {
            $seconds  = strtotime($checkout) - strtotime($checkin);
            $duration = gmdate('H:i', $seconds);
            $status   = 'Complete';
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => [
                'date'           => date('Y-m-d', strtotime($rows[0]['created_at'])),
                'worker'         => $rows[0]['worker'],
                'hotel'          => $rows[0]['hotel'],
                'job'            => $rows[0]['job'],
                'checkin_time'   => $checkin ? date('H:i', strtotime($checkin)) : null,
                'checkout_time'  => $checkout ? date('H:i', strtotime($checkout)) : null,
                'duration'       => $duration,
                'status'         => $status,
                'latitude'       => $lat,
                'longitude'      => $lng,
                'checkin_photo'  => $photoIn,
                'checkout_photo' => $photoOut
            ]
        ]);
    }
}
