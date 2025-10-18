<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    protected $announcementModel;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
    }

    /**
     * Display all announcements
     */
    public function index()
    {
        $data = [
            'title' => 'Announcements',
            'announcements' => $this->announcementModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('announcements', $data);
    }
}
