<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WorkerProfileModel;
use App\Models\SkillModel;
use App\Models\WorkerSkillModel;
use App\Models\WorkerExperienceModel;
use App\Models\WorkerDocumentModel;
use App\Models\JobModel;
use App\Models\JobApplicationModel;
use App\Models\JobAttendanceModel;
use App\Models\EducationModel;

class WorkerController extends BaseController
{
    protected $user;
    protected $profile;
    protected $skill;
    protected $workerSkill;
    protected $experience;
    protected $job;
    protected $apply;
    protected $attendance;
    protected $education;

    public function __construct()
    {
        $this->user        = new UserModel();
        $this->profile     = new WorkerProfileModel();
        $this->skill       = new SkillModel();
        $this->workerSkill = new WorkerSkillModel();
        $this->experience  = new WorkerExperienceModel();
        $this->job         = new JobModel();
        $this->apply       = new JobApplicationModel();
        $this->attendance  = new JobAttendanceModel();
        $this->education   = new EducationModel();
    }

    /**
     * ============================
     * GET PROFILE WORKER
     * ============================
     * GET /api/worker/profile
     */
    public function profile()
    {
        // user dari JWT
        $jwtUser = $this->request->user;

        // Optional: validasi role
        if ($jwtUser->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        // Ambil data user dari DB
        $user = $this->user->find($jwtUser->id);

        if (!$user) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'User not found']);
        }

        unset($user['password']); // keamanan

        return $this->response->setJSON([
            'user' => $user
        ]);
    }

    /**
     * ============================
     * UPDATE PROFILE WORKER
     * ============================
     * PUT /api/worker/profile
     */
    public function updateProfile()
    {
        $jwtUser = $this->request->user;

        if ($jwtUser->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        $data = $this->request->getJSON(true);

        // Field yang boleh diupdate
        $allowed = [
            'name',
            'phone',
            'photo'
        ];

        $updateData = array_intersect_key(
            $data,
            array_flip($allowed)
        );

        if (empty($updateData)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'No data to update']);
        }

        $this->user->update($jwtUser->id, $updateData);

        return $this->response->setJSON([
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * ============================
     * CONTOH ENDPOINT TEST JWT
     * ============================
     * GET /api/worker/me
     */
    public function me()
    {
        return $this->response->setJSON([
            'id'    => $this->request->user->id,
            'email' => $this->request->user->email,
            'role'  => $this->request->user->role
        ]);
    }

    /**
     * ============================
     * LIST MASTER SKILL
     * ============================
     */
    public function skills()
    {
        return $this->response->setJSON([
            'data' => $this->skill
                ->orderBy('name', 'ASC')
                ->findAll()
        ]);
    }

    /**
     * ============================
     * LIST WORKER SKILL
     * ============================
     */
    public function mySkills()
    {
        $user = $this->request->user;

        if ($user->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        $skills = $this->workerSkill
            ->select('skills.id, skills.name')
            ->join('skills', 'skills.id = worker_skills.skill_id')
            ->where('worker_skills.user_id', $user->id)
            ->findAll();

        return $this->response->setJSON([
            'data' => $skills
        ]);
    }

    /**
     * ============================
     * SET WORKER SKILLS
     * ============================
     */
    public function setSkills()
    {
        $user = $this->request->user;
        $data = $this->request->getJSON(true);

        if ($user->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        if (!isset($data['skill_ids']) || !is_array($data['skill_ids'])) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'skill_ids must be an array']);
        }

        // hapus skill lama
        $this->workerSkill
            ->where('user_id', $user->id)
            ->delete();

        // insert skill baru
        foreach ($data['skill_ids'] as $skillId) {
            $this->workerSkill->insert([
                'user_id'  => $user->id,
                'skill_id' => $skillId,
                'created_by' => $user->id
            ]);
        }

        return $this->response->setJSON([
            'message' => 'Skills updated successfully'
        ]);
    }

    /**
     * ============================
     * ADD EXPERIENCE
     * ============================
     */
    public function addExperience()
    {
        $user = $this->request->user;
        $data = $this->request->getJSON(true);

        // =========================
        // VALIDASI MINIMAL
        // =========================
        if (empty($data['company_name'])) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Company name is required'
            ]);
        }

        if (empty($data['start_date'])) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Start date is required'
            ]);
        }

        // =========================
        // NORMALISASI DATA
        // =========================
        $data['user_id']    = $user->id;
        $data['created_by'] = $user->id;
        $data['is_current'] = !empty($data['is_current']) ? 1 : 0;

        if ($data['is_current'] == 1) {
            $data['end_date'] = null;
        }

        // =========================
        // INSERT
        // =========================
        $id = $this->experience->insert($data);

        if (!$id) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Failed to add experience'
            ]);
        }

        return $this->response->setJSON([
            'message' => 'Experience added',
            'id'      => $id
        ]);
    }

    /**
     * ============================
     * LIST EXPERIENCE
     * ============================
     */
    public function experiences()
    {
        $user = $this->request->user;

        // 1️⃣ CARI EXPERIENCE YANG SEDANG AKTIF
        $current = $this->experience
            ->where('user_id', $user->id)
            ->where('deleted_at', null)
            ->where('is_current', 1)
            ->orderBy('start_date', 'DESC')
            ->first();

        if ($current) {
            return $this->response->setJSON([$current]);
        }

        // 2️⃣ JIKA TIDAK ADA, AMBIL end_date TERDEKAT
        $ended = $this->experience
            ->where('user_id', $user->id)
            ->where('deleted_at', null)
            ->where('end_date IS NOT NULL', null, false)
            ->orderBy('end_date', 'DESC')
            ->first();

        if ($ended) {
            return $this->response->setJSON([$ended]);
        }

        // 3️⃣ FALLBACK: start_date TERDEKAT
        $fallback = $this->experience
            ->where('user_id', $user->id)
            ->where('deleted_at', null)
            ->orderBy('start_date', 'DESC')
            ->first();

        return $this->response->setJSON(
            $fallback ? [$fallback] : []
        );
    }

    /**
     * ============================
     * ADD EDUCATION
     * ============================
     */
    public function addEducation()
    {
        $user = $this->request->user;
        $data = $this->request->getJSON(true);

        // =========================
        // VALIDASI MINIMAL
        // =========================
        if (empty($data['instituted_name'])) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Instituted name is required'
            ]);
        }

        if (empty($data['start_date'])) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Start date is required'
            ]);
        }

        // =========================
        // NORMALISASI DATA
        // =========================
        $data['user_id']    = $user->id;
        $data['created_by'] = $user->id;
        $data['is_current'] = !empty($data['is_current']) ? 1 : 0;

        if ($data['is_current'] == 1) {
            $data['end_date'] = null;
        }

        // =========================
        // INSERT
        // =========================
        $id = $this->education->insert($data);

        if (!$id) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Failed to add education'
            ]);
        }

        return $this->response->setJSON([
            'message' => 'education added',
            'id'      => $id
        ]);
    }

    /**
     * ============================
     * LIST EDUCATION
     * ============================
     */
    public function educations()
    {
        $user = $this->request->user;

        // 1️⃣ PRIORITAS: pendidikan yang sedang berjalan
        $current = $this->education
            ->where('user_id', $user->id)
            ->where('deleted_at', null)
            ->where('is_current', 1)
            ->orderBy('start_date', 'DESC')
            ->first();

        if ($current) {
            return $this->response->setJSON([$current]);
        }

        // 2️⃣ JIKA TIDAK ADA, AMBIL end_date TERDEKAT
        $ended = $this->education
            ->where('user_id', $user->id)
            ->where('deleted_at', null)
            ->where('end_date IS NOT NULL', null, false)
            ->orderBy('end_date', 'DESC')
            ->first();

        if ($ended) {
            return $this->response->setJSON([$ended]);
        }

        // 3️⃣ FALLBACK: start_date TERDEKAT
        $fallback = $this->education
            ->where('user_id', $user->id)
            ->where('deleted_at', null)
            ->orderBy('start_date', 'DESC')
            ->first();

        return $this->response->setJSON(
            $fallback ? [$fallback] : []
        );
    }

    public function uploadPhoto()
    {
        $user = $this->request->user;
        $file = $this->request->getFile('photo');

        if (!$file || !$file->isValid()) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Invalid file']);
        }

        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Only JPG or PNG allowed']);
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Max file size 2MB']);
        }

        $uploadPath = FCPATH . 'uploads/profiles';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = 'profile_' . $user->id . '_' . time() . '.' . $file->getExtension();
        $file->move($uploadPath, $newName);

        $publicPath = 'uploads/profiles/' . $newName;

        $this->user->update($user->id, [
            'photo' => $publicPath
        ]);

        return $this->response->setJSON([
            'message' => 'Photo uploaded',
            'photo'   => $publicPath
        ]);
    }

    public function uploadDocument()
    {
        $user = $this->request->user;
        $file = $this->request->getFile('file');
        $type = $this->request->getPost('type'); // ktp / certificate / other

        if (!$file || !$file->isValid()) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Invalid file']);
        }

        $allowed = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($file->getMimeType(), $allowed)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Invalid file type']);
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Max file size 5MB']);
        }

        // =========================
        // PATH SESUAI uploadPhoto
        // =========================
        $uploadPath = FCPATH . 'uploads/documents';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = 'doc_' . $user->id . '_' . time() . '.' . $file->getExtension();
        $file->move($uploadPath, $newName);

        $publicPath = 'uploads/documents/' . $newName;

        $docModel = new WorkerDocumentModel();
        $docModel->insert([
            'user_id'   => $user->id,
            'type'      => $type ?? 'other',
            'file_path' => $publicPath
        ]);

        return $this->response->setJSON([
            'message'   => 'Document uploaded',
            'file_path' => $publicPath
        ]);
    }

    public function documents()
    {
        $user = $this->request->user;

        $docModel = new WorkerDocumentModel();

        return $this->response->setJSON(
            $docModel->where('user_id', $user->id)->findAll()
        );
    }

    public function applicationList()
    {
        $user = $this->request->user;

        if (!$user || $user->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        $model = new JobApplicationModel();

        $data = $model
            ->select(
                'job_applications.id as application_id,
                 job_applications.status,
                 job_applications.applied_at,
                 jobs.id as job_id,
                 jobs.position,
                 jobs.fee,
                 jobs.location,
                 jobs.hotel_id'
            )
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->where('job_applications.user_id', $user->id)
            ->orderBy('job_applications.applied_at', 'DESC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function applications()
    {
        $user = $this->request->user;

        if (!$user || $user->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        $data = $this->apply->workerHistory($user->id) ?? [];

        $pending = $accepted = $completed = 0;

        foreach ($data as $row) {
            $status = $row['status'] ?? $row['application_status'] ?? null;

            if (!$status) continue;

            switch ($status) {
                case 'pending':
                    $pending++;
                    break;
                case 'accepted':
                    $accepted++;
                    break;
                case 'completed':
                    $completed++;
                    break;
            }
        }

        return $this->response->setJSON([
            'pending'   => $pending,
            'accepted'  => $accepted,
            'completed' => $completed,
            'total'     => count($data)
        ]);
    }

    public function applicationDetail($applicationId)
    {
        $user = $this->request->user;

        $model = new JobApplicationModel();

        $data = $model
            ->select(
                'job_applications.id as application_id,
                 job_applications.status as application_status,
                 job_applications.applied_at,
                 jobs.*'
            )
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->where('job_applications.id', $applicationId)
            ->where('job_applications.user_id', $user->id)
            ->first();

        if (!$data) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Application not found']);
        }

        return $this->response->setJSON($data);
    }

    /**
     * ============================
     * LIST JOBS FOR WORKER
     * ============================
     * GET /api/worker/jobs
     */
    public function jobs()
    {
        $user = $this->request->user;

        if (!$user || $user->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        // =========================
        // AMBIL JOB + HOTEL
        // =========================
        $jobs = $this->job
            ->select('
                jobs.*,
                hotels.hotel_name,
                hotels.logo as hotel_logo,
            ')
            ->join('hotels', 'hotels.id = jobs.hotel_id', 'left')
            ->where('jobs.status', 'open')
            ->orderBy('jobs.job_date_start', 'DESC')
            ->findAll();

        $jobs = $jobs ?? [];

        // =========================
        // JOB YANG SUDAH DIAPPLY
        // =========================
        $appliedJobs = (array) $this->apply
            ->where('user_id', $user->id)
            ->findColumn('job_id');

        // =========================
        // FLAG is_applied
        // =========================
        foreach ($jobs as &$job) {
            $job['is_applied'] = in_array($job['id'], $appliedJobs);
        }

        return $this->response->setJSON($jobs);
    }

    /**
     * ============================
     * MOST POPULAR JOBS
     * ============================
     * GET /api/jobs/most-popular
     */
    public function mostPopular()
    {
        $user = $this->request->user;

        if (!$user || $user->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        $db = \Config\Database::connect();

        $jobs = $db->table('job_applications ja')
            ->select('
                j.*,
                h.hotel_name,
                h.logo AS hotel_logo,
                COUNT(ja.id) AS total_apply
            ')
            ->join('jobs j', 'j.id = ja.job_id')
            ->join('hotels h', 'h.id = j.hotel_id', 'left')
            ->groupBy('j.id')
            ->orderBy('total_apply', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return $this->response->setJSON($jobs);
    }

    /**
     * ============================
     * LIST ATTENDANCE (SCHEDULE)
     * ============================
     * GET /api/worker/attendance
     * optional: ?date=YYYY-MM-DD
     */
    public function attendance()
    {
        $user = $this->request->user;

        if ($user->role !== 'worker') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Access denied']);
        }

        $date = $this->request->getGet('date');

        $builder = $this->attendance
            ->select('
                job_attendances.*,
                jobs.position,
                jobs.job_date_start,
                jobs.job_date_end,
                hotels.hotel_name
            ')
            ->join('jobs', 'jobs.id = job_attendances.job_id')
            ->join('hotels', 'hotels.id = jobs.hotel_id', 'left')
            ->where('job_attendances.user_id', $user->id);

        if ($date) {
            $builder->where('DATE(job_attendances.created_at)', $date);
        }

        $data = $builder
            ->orderBy('job_attendances.created_at', 'ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    /**
     * ============================
     * ATTENDANCE BY JOB
     * ============================
     * GET /api/worker/attendance/job/{job_id}
     */
    public function attendanceByJob($jobId)
    {
        $user = $this->request->user;

        $data = $this->attendance
            ->where('job_id', $jobId)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    /**
     * ============================
     * CHECK-IN
     * ============================
     * POST /api/worker/attendance/checkin
     */
    public function checkin()
    {
        $user = $this->request->user;
        $data = $this->request->getPost();

        // validasi minimal
        foreach (['job_id','application_id','latitude','longitude'] as $f) {
            if (empty($data[$f])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['message' => "$f is required"]);
            }
        }

        // ❌ cegah double check-in
        $exists = $this->attendance
            ->where('job_id', $data['job_id'])
            ->where('application_id', $data['application_id'])
            ->where('user_id', $user->id)
            ->where('type', 'checkin')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->first();

        if ($exists) {
            return $this->response
                ->setStatusCode(409)
                ->setJSON(['message' => 'Already checked-in today']);
        }

        $selfieBase64 = $data['selfie'] ?? null;
        $photoPath = null;

        if ($selfieBase64) {
            $imageData = base64_decode($selfieBase64);

            if ($imageData === false) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['message' => 'Invalid selfie data']);
            }

            $name = 'checkin_' . $data['job_id'] . '_' . $user->id . '_' . time() . '.jpg';

            // 🔥 PATH FISIK (SERVER)
            $dir = FCPATH . 'uploads/attendance/';

            // 🔥 PATH UNTUK DB (RELATIVE URL)
            $photoPath = 'uploads/attendance/' . $name;

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents($dir . $name, $imageData);
        }

        $this->attendance->insert([
            'job_id'        => $data['job_id'],
            'application_id'=> $data['application_id'],
            'user_id'       => $user->id,
            'type'          => 'checkin',
            'latitude'      => $data['latitude'],
            'longitude'     => $data['longitude'],
            'photo_path'    => $photoPath,
            'device_info'   => $this->request->getUserAgent()->getAgentString(),
            'created_by'    => $user->id
        ]);

        return $this->response->setJSON([
            'message' => 'Check-in success'
        ]);
    }

    /**
     * ============================
     * CHECK-OUT
     * ============================
     * POST /api/worker/attendance/checkout
     */
    public function checkout()
    {
        $user = $this->request->user;
        $data = $this->request->getPost();

        foreach (['job_id','application_id','latitude','longitude'] as $f) {
            if (empty($data[$f])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['message' => "$f is required"]);
            }
        }

        // wajib sudah check-in
        $checkin = $this->attendance
            ->where('job_id', $data['job_id'])
            ->where('application_id', $data['application_id'])
            ->where('user_id', $user->id)
            ->where('type', 'checkin')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->first();

        if (!$checkin) {
            return $this->response
                ->setStatusCode(409)
                ->setJSON(['message' => 'You must check-in first']);
        }

        $selfieBase64 = $data['selfie'] ?? null;
        $photoPath = null;

        if ($selfieBase64) {
            $imageData = base64_decode($selfieBase64);

            if ($imageData === false) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['message' => 'Invalid selfie data']);
            }

            $name = 'checkout_' . $data['job_id'] . '_' . $user->id . '_' . time() . '.jpg';

            $dir = FCPATH . 'uploads/attendance/';
            $photoPath = 'uploads/attendance/' . $name;

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents($dir . $name, $imageData);
        }

        $this->attendance->insert([
            'job_id'        => $data['job_id'],
            'application_id'=> $data['application_id'],
            'user_id'       => $user->id,
            'type'          => 'checkout',
            'latitude'      => $data['latitude'],
            'longitude'     => $data['longitude'],
            'photo_path'    => $photoPath,
            'device_info'   => $this->request->getUserAgent()->getAgentString(),
            'created_by'    => $user->id
        ]);

        return $this->response->setJSON([
            'message' => 'Check-out success'
        ]);
    }

}
