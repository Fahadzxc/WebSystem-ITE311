-- Run this SQL in phpMyAdmin to fix the migration gap
-- This will delete the migration record for 2025-12-10-120000 that no longer has a file

DELETE FROM migrations WHERE version = '2025-12-10-120000';

-- If you want to see what migrations exist first, run this:
-- SELECT * FROM migrations WHERE version LIKE '2025-12%' ORDER BY version;

