-- Fix enrollment status ENUM to include 'pending' and 'rejected'
-- Run this SQL command in phpMyAdmin

ALTER TABLE enrollments 
MODIFY COLUMN status ENUM('pending', 'active', 'completed', 'dropped', 'suspended', 'rejected') 
DEFAULT 'pending';

-- Add rejection_reason column if it doesn't exist
ALTER TABLE enrollments 
ADD COLUMN IF NOT EXISTS rejection_reason TEXT NULL AFTER status;
