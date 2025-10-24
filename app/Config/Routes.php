<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 * Route Configuration for LMS System
 * 
 * Route Structure:
 * - Public routes: Home, About, Contact, Auth
 * - Dashboard routes: Role-specific dashboards
 * - Student routes: Enrollment, Materials access
 * - Admin routes: Course management, Material upload
 * - Teacher routes: Material management
 * - Materials routes: Upload, Download, Delete operations
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication Routes
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Auth::dashboard');

// Role-specific dashboards
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('teacher/dashboard', 'Teacher::dashboard');
$routes->get('student/dashboard', 'Student::dashboard');

// Student Routes
$routes->post('student/enroll', 'Student::enroll');
$routes->get('student/materials', 'Student::materials');
$routes->get('student/materials/(:num)', 'Student::courseMaterials/$1');
$routes->get('student/announcement', 'Announcement::index');
$routes->get('student/create-test-notifications', 'Student::createTestNotifications');

// Admin Course Management Routes
$routes->get('admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('admin/course/(:num)/upload', 'Materials::upload/$1');

// Materials Management Routes
$routes->get('materials/upload/(:num)', 'Materials::upload/$1');
$routes->post('materials/upload/(:num)', 'Materials::upload/$1');
$routes->post('materials/uploadFile', 'Materials::uploadFile');
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');

// Teacher Materials Routes (alternative access for teachers)
$routes->get('teacher/course/(:num)/materials', 'Materials::upload/$1');
$routes->post('teacher/course/(:num)/materials', 'Materials::upload/$1');

// Announcements Routes
$routes->get('announcements', 'Announcement::index');
$routes->get('student/announcement', 'Announcement::index');

// Notifications Routes
$routes->get('notifications', 'Notifications::index');
$routes->get('notifications/api', 'Notifications::get');
$routes->post('notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');

// Course Routes
$routes->get('courses', 'Course::index');
$routes->get('courses/(:num)', 'Course::view/$1');
$routes->post('course/enroll', 'Course::enroll');
$routes->post('course/unenroll', 'Course::unenroll');
$routes->get('courses/my-enrollments', 'Course::myEnrollments');
