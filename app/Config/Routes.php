<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->options('(:any)', function () {
    return response()
        ->setStatusCode(200);
});

$routes->get('/', 'Home::index');

/* =========================
 * AUTH
 * ========================= */
$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::auth');
$routes->get('logout', 'Auth\Login::logout');

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('dashboard/calendar', 'Admin\Dashboard::calendar', ['filter' => 'role:admin']);
    $routes->get('dashboard/calendar-attendance/(:num)', 'Admin\Dashboard::attendanceByJob/$1', ['filter' => 'role:admin']);

    $routes->get('hotels', 'Admin\Hotels::index', ['filter' => 'role:admin']);
    $routes->post('hotels/datatable', 'Admin\Hotels::datatable', ['filter' => 'role:admin']);
    $routes->post('hotels/store', 'Admin\Hotels::store', ['filter' => 'role:admin']);
    $routes->post('hotels/get', 'Admin\Hotels::getById', ['filter' => 'role:admin']);
    $routes->post('hotels/update', 'Admin\Hotels::update', ['filter' => 'role:admin']);
    $routes->post('hotels/delete', 'Admin\Hotels::delete', ['filter' => 'role:admin']);

    $routes->get('attendance', 'Admin\Attendance::index', ['filter' => 'role:admin']);
    $routes->post('attendance/datatable', 'Admin\Attendance::datatable', ['filter' => 'role:admin']);
    $routes->post('attendance/detail', 'Admin\Attendance::detail', ['filter' => 'role:admin']);
});

$routes->group('api', function($routes) {
    $routes->post('auth/login', 'Api\AuthController::login');
    $routes->post('auth/google', 'Api\AuthController::google');
    $routes->post('auth/facebook', 'Api\AuthController::facebook');
    $routes->post('auth/register', 'Api\AuthController::register');
    $routes->post('auth/refresh', 'Api\AuthController::refresh');
});

$routes->group('api', ['filter' => 'jwt'], function($routes) {

    // =========================
    // WORKER PROFILE
    // =========================
    $routes->get('worker/profile', 'Api\WorkerController::profile');
    $routes->put('worker/profile', 'Api\WorkerController::updateProfile');
    $routes->get('worker/me', 'Api\WorkerController::me');

    // =========================
    // WORKER SKILLS
    // =========================
    $routes->get('worker/skills', 'Api\WorkerController::skills');
    $routes->get('worker/my-skills', 'Api\WorkerController::mySkills');
    $routes->post('worker/skills', 'Api\WorkerController::setSkills');

    // =========================
    // WORKER DATA
    // =========================
    $routes->get('worker/jobs', 'Api\WorkerController::jobs');
    $routes->post('worker/experience', 'Api\WorkerController::addExperience');
    $routes->get('worker/experience', 'Api\WorkerController::experiences');
    $routes->post('worker/education', 'Api\WorkerController::addEducation');
    $routes->get('worker/education', 'Api\WorkerController::educations');

    $routes->post('worker/upload/photo', 'Api\WorkerController::uploadPhoto');
    $routes->post('worker/upload/document', 'Api\WorkerController::uploadDocument');
    $routes->get('worker/documents', 'Api\WorkerController::documents');

    // =========================
    // APPLICATION
    // =========================
    $routes->get('worker/application-list', 'Api\WorkerController::applicationList');
    $routes->get('worker/applications', 'Api\WorkerController::applications');
    $routes->get('worker/applications/(:num)', 'Api\WorkerController::applicationDetail/$1');

    // =========================
    // ATTENDANCE 🔥 (FIXED)
    // =========================

    // list attendance (schedule)
    // optional: ?date=YYYY-MM-DD
    $routes->get('worker/attendance', 'Api\WorkerController::attendance');

    // attendance by job
    $routes->get('worker/attendance/job/(:num)', 'Api\WorkerController::attendanceByJob/$1');

    // check-in / check-out
    $routes->post('worker/attendance/checkin', 'Api\WorkerController::checkin');
    $routes->post('worker/attendance/checkout', 'Api\WorkerController::checkout');

    // =========================
    // RATING
    // =========================
    $routes->post('worker/rating', 'Api\RatingController::submit');
    $routes->get('worker/ratings', 'Api\RatingController::myRatings');

    // =========================
    // JOB (PUBLIC DATA)
    // =========================
    $routes->get('worker/most-popular', 'Api\WorkerController::mostPopular');
    $routes->get('skills', 'Api\WorkerController::skills');

    $routes->get('jobs', 'Api\JobController::index');
    $routes->get('jobs/(:num)', 'Api\JobController::show/$1');
    $routes->post('jobs/(:num)/apply', 'Api\JobController::apply/$1');
    $routes->post('jobs', 'Api\JobController::create');

    // =========================
    // COMPANY
    // =========================
    $routes->get('company/hotels', 'Api\CompanyController::index');
});
