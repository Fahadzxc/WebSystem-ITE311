-- Fix migration gaps and clean up
-- Run this in phpMyAdmin (lms_alalawi database)

-- Step 1: Check what problematic migrations exist
SELECT * FROM migrations WHERE version LIKE '2025-11-15-10000%' OR version LIKE '2025-12-10%' ORDER BY version;

-- Step 2: Delete ALL migration records that don't have corresponding files
-- These are the ones causing gaps:
DELETE FROM migrations WHERE version = '2025-11-15-100004';
DELETE FROM migrations WHERE version = '2025-11-15-100005';
DELETE FROM migrations WHERE version = '2025-12-10-120000';

-- Step 3: After running the deletes above, you can rollback all migrations using:
-- php spark migrate:rollback -b 0

-- ============================================
-- ALTERNATIVE: If you want a COMPLETE RESET
-- ============================================
-- This will delete ALL migration records, then you can re-run all migrations:
-- TRUNCATE TABLE migrations;
-- Then run: php spark migrate

