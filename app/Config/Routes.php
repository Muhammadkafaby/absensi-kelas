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

    // ==================== USER PROFILE & PASSWORD ====================
    $routes->group('user', function ($routes) {
        $routes->get('profile', 'UserController::profile');
        $routes->post('profile/update', 'UserController::updateProfile');
        $routes->get('password/change', 'UserController::changePassword');
        $routes->post('password/update', 'UserController::updatePassword');
    });

    // ==================== EXPORT ROUTES ====================
    $routes->group('export', function ($routes) {
        $routes->get('recap/excel', 'ExportController::exportRecapExcel');
        $routes->get('students/excel', 'ExportController::exportStudentsExcel');
    });

    // ==================== ACTIVITY LOGS (Admin only) ====================
    $routes->group('activity-logs', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/', 'ActivityLogController::index');
        $routes->post('clear', 'ActivityLogController::clearOld');
    });

    // ==================== ACADEMIC MANAGEMENT (Admin only) ====================
    $routes->group('academic', ['filter' => 'role:admin'], function ($routes) {
        // Academic Years
        $routes->get('years', 'AcademicYearController::index');
        $routes->get('years/create', 'AcademicYearController::create');
        $routes->post('years/store', 'AcademicYearController::store');
        $routes->get('years/edit/(:num)', 'AcademicYearController::edit/$1');
        $routes->post('years/update/(:num)', 'AcademicYearController::update/$1');
        $routes->get('years/delete/(:num)', 'AcademicYearController::delete/$1');
        $routes->get('years/set-active/(:num)', 'AcademicYearController::setActive/$1');

        // Semesters
        $routes->get('semesters', 'SemesterController::index');
        $routes->get('semesters/create', 'SemesterController::create');
        $routes->post('semesters/store', 'SemesterController::store');
        $routes->get('semesters/edit/(:num)', 'SemesterController::edit/$1');
        $routes->post('semesters/update/(:num)', 'SemesterController::update/$1');
        $routes->get('semesters/delete/(:num)', 'SemesterController::delete/$1');
        $routes->get('semesters/set-active/(:num)', 'SemesterController::setActive/$1');
    });

    // ==================== ATTENDANCE ROUTES ====================
    $routes->group('attendance', ['filter' => 'auth'], function ($routes) {
        // Guru routes
        $routes->get('/', 'AttendanceController::index', ['filter' => 'role:guru']);
        $routes->get('get-students/(:num)', 'AttendanceController::getStudentsByClass/$1');
        $routes->post('store', 'AttendanceController::store', ['filter' => 'role:guru']);
        $routes->get('edit/(:num)', 'AttendanceController::edit/$1');
        $routes->post('update/(:num)', 'AttendanceController::update/$1');

        // Admin routes
        $routes->get('admin', 'AttendanceController::adminIndex', ['filter' => 'role:admin']);
        $routes->post('admin/store', 'AttendanceController::adminStore', ['filter' => 'role:admin']);
        $routes->get('admin/edit/(:num)', 'AttendanceController::adminEdit/$1', ['filter' => 'role:admin']);
        $routes->post('admin/update/(:num)', 'AttendanceController::adminUpdate/$1', ['filter' => 'role:admin']);
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
        $routes->get('students/import', 'MasterDataController::importStudentsForm');
        $routes->post('students/import/process', 'MasterDataController::importStudentsProcess');
        $routes->get('students/template', 'MasterDataController::downloadStudentTemplate');
        $routes->get('students/export', 'MasterDataController::exportStudents');

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
        $routes->get('teacher/export-excel', 'ExportController::exportTeacherRecapExcel', ['filter' => 'role:guru']);

        // Export recap to Excel (Admin only)
        $routes->get('export-excel', 'ExportController::exportRecapExcel', ['filter' => 'role:admin']);
    });
});
