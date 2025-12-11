<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseScheduleModel extends Model
{
    protected $table = 'course_schedules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'course_id',
        'day_of_week',
        'start_time',
        'end_time'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all schedules for a course
     */
    public function getSchedulesByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('day_of_week', 'ASC')
                    ->orderBy('start_time', 'ASC')
                    ->findAll();
    }

    /**
     * Delete all schedules for a course
     */
    public function deleteByCourse($course_id)
    {
        return $this->where('course_id', $course_id)->delete();
    }

    /**
     * Get schedules by teacher and day
     */
    public function getSchedulesByTeacherAndDay($teacher_id, $day_of_week, $exclude_course_id = null)
    {
        $builder = $this->db->table('course_schedules');
        $builder->select('course_schedules.*, courses.title as course_title');
        $builder->join('courses', 'courses.id = course_schedules.course_id');
        $builder->where('courses.instructor_id', $teacher_id);
        $builder->where('course_schedules.day_of_week', $day_of_week);
        
        if ($exclude_course_id) {
            $builder->where('course_schedules.course_id !=', $exclude_course_id);
        }
        
        return $builder->get()->getResultArray();
    }
}
