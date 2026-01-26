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

$routes->group('api', function($routes) {
    $routes->post('auth/login', 'Api\AuthController::login');
    $routes->post('auth/google', 'Api\AuthController::google');
    $routes->post('auth/facebook', 'Api\AuthController::facebook');
    $routes->post('auth/register', 'Api\AuthController::register');
    $routes->post('auth/refresh', 'Api\AuthController::refresh');


});

$routes->group('api', ['filter' => 'jwt'], function($routes) {
    $routes->get('worker/profile', 'Api\WorkerController::profile');
    $routes->put('worker/profile', 'Api\WorkerController::updateProfile');
    $routes->get('worker/jobs', 'Api\WorkerController::jobs');
    $routes->get('worker/me', 'Api\WorkerController::me');
    $routes->post('worker/experience', 'Api\WorkerController::addExperience');
    $routes->get('worker/experience', 'Api\WorkerController::experiences');
    $routes->post('worker/skills', 'Api\WorkerController::setSkills');
    $routes->post('worker/upload/photo', 'Api\WorkerController::uploadPhoto');
    $routes->post('worker/upload/document', 'Api\WorkerController::uploadDocument');
    $routes->get('worker/documents', 'Api\WorkerController::documents');
    $routes->get('worker/applications', 'Api\WorkerController::applications');
    $routes->get('worker/applications/(:num)','Api\WorkerController::applicationDetail/$1');
    $routes->post('worker/rating', 'Api\RatingController::submit');
    $routes->get('worker/ratings', 'Api\RatingController::myRatings');

    $routes->get('skills', 'Api\WorkerController::skills');

    $routes->get('jobs', 'Api\JobController::index');
    $routes->get('jobs/(:num)', 'Api\JobController::show/$1');
    $routes->post('jobs/(:num)/apply', 'Api\JobController::apply/$1');
    $routes->post('jobs', 'Api\JobController::create');
});


