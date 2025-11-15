<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==================== AUTH ROUTES ====================
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login/do', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');

// ==================== PROTECTED ROUTES ====================
// Semua route di bawah ini memerlukan login
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Dashboard (accessible by both admin & guru)
    $routes->get('/dashboard', 'DashboardController::index');

    // ==================== ATTENDANCE ROUTES (Guru only) ====================
    $routes->group('attendance', ['filter' => 'role:guru'], function ($routes) {
        $routes->get('/', 'AttendanceController::index');
        $routes->get('students/(:num)', 'AttendanceController::getStudentsByClass/$1');
        $routes->post('store', 'AttendanceController::store');
    });

    // ==================== MASTER DATA ROUTES (Admin only) ====================
    $routes->group('master', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/', 'MasterDataController::index');

        // Classes
        $routes->get('classes', 'MasterDataController::classes');
        $routes->get('classes/create', 'MasterDataController::createClass');
        $routes->post('classes/store', 'MasterDataController::storeClass');
        $routes->get('classes/edit/(:num)', 'MasterDataController::editClass/$1');
        $routes->post('classes/update/(:num)', 'MasterDataController::updateClass/$1');
        $routes->get('classes/delete/(:num)', 'MasterDataController::deleteClass/$1');

        // Students
        $routes->get('students', 'MasterDataController::students');
        $routes->get('students/create', 'MasterDataController::createStudent');
        $routes->post('students/store', 'MasterDataController::storeStudent');
        $routes->get('students/edit/(:num)', 'MasterDataController::editStudent/$1');
        $routes->post('students/update/(:num)', 'MasterDataController::updateStudent/$1');
        $routes->get('students/delete/(:num)', 'MasterDataController::deleteStudent/$1');

        // Teachers
        $routes->get('teachers', 'MasterDataController::teachers');
        $routes->get('teachers/create', 'MasterDataController::createTeacher');
        $routes->post('teachers/store', 'MasterDataController::storeTeacher');
        $routes->get('teachers/edit/(:num)', 'MasterDataController::editTeacher/$1');
        $routes->post('teachers/update/(:num)', 'MasterDataController::updateTeacher/$1');
        $routes->get('teachers/delete/(:num)', 'MasterDataController::deleteTeacher/$1');

        // Subjects
        $routes->get('subjects', 'MasterDataController::subjects');
        $routes->get('subjects/create', 'MasterDataController::createSubject');
        $routes->post('subjects/store', 'MasterDataController::storeSubject');
        $routes->get('subjects/edit/(:num)', 'MasterDataController::editSubject/$1');
        $routes->post('subjects/update/(:num)', 'MasterDataController::updateSubject/$1');
        $routes->get('subjects/delete/(:num)', 'MasterDataController::deleteSubject/$1');
    });

    // ==================== RECAP ROUTES ====================
    $routes->group('recap', function ($routes) {
        // Admin recap (Admin only)
        $routes->get('admin', 'RecapController::adminRecap', ['filter' => 'role:admin']);

        // Teacher recap (Guru only)
        $routes->get('teacher', 'RecapController::teacherRecap', ['filter' => 'role:guru']);
    });
});
