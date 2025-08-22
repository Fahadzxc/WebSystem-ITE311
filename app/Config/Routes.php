<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home route
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication routes
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('reset-password', 'Auth::resetPassword');
});

// Dashboard routes (protected)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
});

$routes->group('instructor', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Instructor::dashboard');
});

$routes->group('student', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});

// Default dashboard route
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);



