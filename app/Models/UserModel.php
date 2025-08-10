<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'username', 'email', 'password', 'first_name', 'last_name', 
        'role', 'status', 'remember_token', 'reset_token', 
        'reset_token_expires', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'role' => 'required|in_list[student,instructor,admin]',
        'status' => 'required|in_list[active,inactive,suspended]'
    ];
    
    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters long',
            'max_length' => 'Username cannot exceed 50 characters',
            'is_unique' => 'Username already exists'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email already exists'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters long'
        ],
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters long',
            'max_length' => 'First name cannot exceed 50 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters long',
            'max_length' => 'Last name cannot exceed 50 characters'
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list' => 'Please select a valid role'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Please select a valid status'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Hash password before saving
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    // Before insert callback
    protected $beforeInsert = ['hashPassword'];
    
    // Before update callback
    protected $beforeUpdate = ['hashPassword'];
    
    // Get user by remember token
    public function getUserByRememberToken($token)
    {
        return $this->where('remember_token', $token)->first();
    }
    
    // Get user by reset token
    public function getUserByResetToken($token)
    {
        return $this->where('reset_token', $token)
                   ->where('reset_token_expires >', date('Y-m-d H:i:s'))
                   ->first();
    }
    
    // Clear reset token
    public function clearResetToken($userId)
    {
        return $this->update($userId, [
            'reset_token' => null,
            'reset_token_expires' => null
        ]);
    }
    
    // Get users by role
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }
    
    // Get active users
    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    // Search users
    public function searchUsers($search)
    {
        return $this->like('username', $search)
                   ->orLike('email', $search)
                   ->orLike('first_name', $search)
                   ->orLike('last_name', $search)
                   ->findAll();
    }
} 