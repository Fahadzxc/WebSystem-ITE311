-- Clean up migration gaps
-- Run this in phpMyAdmin on lms_alalawi database

-- Option 1: Delete only the problematic migration records
DELETE FROM migrations WHERE version = '2025-11-15-100004';
DELETE FROM migrations WHERE version = '2025-11-15-100005';
DELETE FROM migrations WHERE version = '2025-12-10-120000';

-- Option 2: If you want to completely reset (delete ALL migration records)
-- Uncomment the line below and comment out Option 1 above
-- TRUNCATE TABLE migrations;

