<?php

namespace App\Controllers;

class Home extends BaseController
{
<<<<<<< HEAD
    /**
     * Loads the homepage
     */
    public function index()
    {
        $session = session();
        $data = [
            'title' => 'Home - LMS System',
            'page' => 'home',
            'isLoggedIn' => $session->get('isLoggedIn')
        ];
        
        return view('index', $data);
    }

    /**
     * Loads the about page
     */
    public function about()
    {
        $session = session();
        $data = [
            'title' => 'About Us - LMS System',
            'page' => 'about',
            'isLoggedIn' => $session->get('isLoggedIn')
        ];
        
        return view('about', $data);
    }

    /**
     * Loads the contact page
     */
    public function contact()
    {
        $session = session();
        $data = [
            'title' => 'Contact Us - LMS System',
            'page' => 'contact',
            'isLoggedIn' => $session->get('isLoggedIn')
        ];
        
        return view('contact', $data);
=======
    public function index()
    {
        return view('index');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
    }
}
