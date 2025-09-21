<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
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

// Role-based Dashboard Routes
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('teacher/dashboard', 'Teacher::dashboard');
$routes->get('student/dashboard', 'Student::dashboard');

// Admin Routes
$routes->get('admin/users', 'Admin::users');
$routes->get('admin/courses', 'Admin::courses');

// Teacher Routes
$routes->get('teacher/courses', 'Teacher::myCourses');
$routes->get('teacher/students', 'Teacher::myStudents');

// Student Routes
$routes->get('student/courses', 'Student::myCourses');
$routes->get('student/available-courses', 'Student::availableCourses');
$routes->get('student/enroll/(:num)', 'Student::enroll/$1');