<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    protected $announcementModel;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
    }

    public function index()
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please Login first.');
            return redirect()->to(base_url('login'));
        }

        // Fetch all announcements using the model, ordered by created_at descending (newest first)
        $announcements = $this->announcementModel->getAllAnnouncements();

        // Pass announcements to view
        return view('announcements', [
            'announcements' => $announcements,
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ]
        ]);
    }
}
