<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Materials extends BaseController
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    /**
     * Create upload directory if it doesn't exist
     *
     * @param string $path Directory path to create
     * @return bool True if directory exists or was created successfully
     */
    private function createUploadDirectory($path)
    {
        if (!is_dir($path)) {
            return mkdir($path, 0755, true);
        }
        return true;
    }

    /**
     * Get upload configuration
     *
     * @return array Upload configuration array
     */
    private function getUploadConfig()
    {
        return [
            'upload_path' => WRITEPATH . 'uploads/materials/',
            'allowed_types' => 'pdf|doc|docx|txt|jpg|jpeg|png|gif|zip|rar|ppt|pptx|xls|xlsx',
            'max_size' => 10240, // 10MB in KB
            'encrypt_name' => true,
            'overwrite' => false
        ];
    }

    /**
     * Check if a teacher teaches a specific course
     *
     * @param int $teacher_id Teacher ID
     * @param int $course_id Course ID
     * @return bool True if teacher teaches the course
     */
    private function teacherTeachesCourse($teacher_id, $course_id)
    {
        $course = $this->courseModel->find($course_id);
        return $course && $course['instructor_id'] == $teacher_id;
    }

    /**
     * Display file upload form and handle file upload process
     *
     * @param int $course_id Course ID for which to upload materials
     * @return mixed
     */
    public function upload($course_id)
    {
        // Get course info (same as working FileUpload)
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            $course = [
                'id' => $course_id, 
                'title' => 'Course ' . $course_id,
                'course_name' => 'Course ' . $course_id
            ];
        }

        // Handle file upload (POST request) - Using working FileUpload logic
        if ($this->request->getMethod() === 'post') {
            // Check if file was uploaded
            $file = $this->request->getFile('material_file');
            
            if (!$file || !$file->isValid()) {
                session()->setFlashdata('error', 'Please select a valid file');
                return redirect()->to(base_url('materials/upload/' . $course_id));
            }

            // Create upload directory
            $uploadPath = WRITEPATH . 'uploads/materials/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move file with random name
            $newName = $file->getRandomName();
            if (!$file->move($uploadPath, $newName)) {
                session()->setFlashdata('error', 'Failed to upload file');
                return redirect()->to(base_url('materials/upload/' . $course_id));
            }

            // Save to database
            $data = [
                'course_id' => $course_id,
                'file_name' => $file->getClientName(),
                'file_path' => 'uploads/materials/' . $newName,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->materialModel->insert($data)) {
                session()->setFlashdata('success', 'File uploaded successfully!');
            } else {
                session()->setFlashdata('error', 'Failed to save file record');
                // Delete uploaded file if database save failed
                if (file_exists($uploadPath . $newName)) {
                    unlink($uploadPath . $newName);
                }
            }

            return redirect()->to(base_url('materials/upload/' . $course_id));
        }

        // Get existing materials
        $materials = $this->materialModel->where('course_id', $course_id)
                                        ->orderBy('created_at', 'DESC')
                                        ->findAll();

        $data = [
            'course' => $course,
            'materials' => $materials,
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ];

        return view('materials/upload', $data);
    }

    /**
     * Handle deletion of a material record and associated file
     *
     * @param int $material_id Material ID to delete
     * @return mixed
     */
    public function delete($material_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        // Check if user is admin or teacher
        if (!in_array(session('role'), ['admin', 'teacher'])) {
            session()->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('login'));
        }

        // Get material record
        $material = $this->materialModel->find($material_id);
        if (!$material) {
            session()->setFlashdata('error', 'Material not found.');
            return redirect()->back();
        }

        // Delete physical file
        $filePath = WRITEPATH . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete database record
        if ($this->materialModel->delete($material_id)) {
            session()->setFlashdata('success', 'Material deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete material record.');
        }

        return redirect()->back();
    }

    /**
     * Handle file download for enrolled students
     *
     * @param int $material_id Material ID to download
     * @return mixed
     */
    public function download($material_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        // Get material record
        $material = $this->materialModel->find($material_id);
        if (!$material) {
            session()->setFlashdata('error', 'Material not found.');
            return redirect()->back();
        }

        // Get user information
        $user_id = session('user_id');
        $user_role = session('role');
        $course_id = $material['course_id'];

        // Check if user has access to this course
        if (in_array($user_role, ['admin', 'teacher'])) {
            // Admin can access all materials
            if ($user_role === 'admin') {
                // Allow access
            } elseif ($user_role === 'teacher') {
                // For teachers, verify they teach this course
                if (!$this->teacherTeachesCourse($user_id, $course_id)) {
                    session()->setFlashdata('error', 'You do not teach this course.');
                    return redirect()->to(base_url('teacher/dashboard'));
                }
            }
        } else {
            // For students, check enrollment
            if ($user_role !== 'student') {
                session()->setFlashdata('error', 'Unauthorized access.');
                return redirect()->to(base_url('login'));
            }

            // Check if student is enrolled in this course
            if (!$this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
                session()->setFlashdata('error', 'You are not enrolled in this course.');
                return redirect()->to(base_url('student/materials'));
            }

            // Additional check: verify enrollment is active
            $enrollment = $this->enrollmentModel->getEnrollment($user_id, $course_id);
            if (!$enrollment || $enrollment['status'] !== 'active') {
                session()->setFlashdata('error', 'Your enrollment in this course is not active.');
                return redirect()->to(base_url('student/materials'));
            }
        }

        // Check if file exists
        $filePath = WRITEPATH . $material['file_path'];
        if (!file_exists($filePath)) {
            session()->setFlashdata('error', 'File not found on server.');
            return redirect()->back();
        }

        // Verify file is readable
        if (!is_readable($filePath)) {
            session()->setFlashdata('error', 'File is not accessible.');
            return redirect()->back();
        }

        // Get file information
        $fileSize = filesize($filePath);
        $fileName = $material['file_name'];
        $mimeType = mime_content_type($filePath);

        // Set appropriate headers for secure download
        $response = $this->response;
        
        // Set content type
        $response->setHeader('Content-Type', $mimeType);
        
        // Set content disposition to force download
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        
        // Set content length
        $response->setHeader('Content-Length', $fileSize);
        
        // Set cache control to prevent caching
        $response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', '0');
        
        // Set security headers
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('X-Frame-Options', 'DENY');
        
        // Read and output file content
        $fileContent = file_get_contents($filePath);
        
        if ($fileContent === false) {
            session()->setFlashdata('error', 'Unable to read file.');
            return redirect()->back();
        }

        // Log download activity (optional)
        log_message('info', "Material downloaded: {$fileName} by user {$user_id} (role: {$user_role})");

        // Return file content with headers
        return $response->setBody($fileContent);
    }

    /**
     * Handle file upload from POST data (FileUpload-style)
     * This method handles uploads when course_id comes from POST data
     */
    public function uploadFile()
    {
        $course_id = $this->request->getPost('course_id') ?? 1;
        
        // Check if file was uploaded
        $file = $this->request->getFile('material_file');
        
        if (!$file || !$file->isValid()) {
            session()->setFlashdata('error', 'Please select a valid file');
            return redirect()->to(base_url('materials/upload/' . $course_id));
        }

        // Create upload directory
        $uploadPath = WRITEPATH . 'uploads/materials/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Move file with random name
        $newName = $file->getRandomName();
        if (!$file->move($uploadPath, $newName)) {
            session()->setFlashdata('error', 'Failed to upload file');
            return redirect()->to(base_url('materials/upload/' . $course_id));
        }

        // Save to database
        $data = [
            'course_id' => $course_id,
            'file_name' => $file->getClientName(),
            'file_path' => 'uploads/materials/' . $newName,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->materialModel->insert($data)) {
            session()->setFlashdata('success', 'File uploaded successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to save file record');
            // Delete uploaded file if database save failed
            if (file_exists($uploadPath . $newName)) {
                unlink($uploadPath . $newName);
            }
        }

        // Always redirect back to materials/upload page
        return redirect()->to(base_url('materials/upload/' . $course_id));
    }

    /**
     * Simple download method (FileUpload-style)
     */
    public function downloadFile($material_id)
    {
        $material = $this->materialModel->find($material_id);
        
        if (!$material) {
            session()->setFlashdata('error', 'File not found');
            return redirect()->back();
        }

        $filePath = WRITEPATH . $material['file_path'];
        
        if (!file_exists($filePath)) {
            session()->setFlashdata('error', 'File not found on server');
            return redirect()->back();
        }

        return $this->response->download($filePath, null, $material['file_name']);
    }

    /**
     * Simple delete method (FileUpload-style)
     */
    public function deleteFile($material_id)
    {
        $material = $this->materialModel->find($material_id);
        
        if (!$material) {
            session()->setFlashdata('error', 'File not found');
            return redirect()->back();
        }

        // Delete physical file
        $filePath = WRITEPATH . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete database record
        if ($this->materialModel->delete($material_id)) {
            session()->setFlashdata('success', 'File deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete file');
        }

        return redirect()->back();
    }
}
