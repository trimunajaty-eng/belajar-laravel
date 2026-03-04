-- Set admin role
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
UPDATE users SET role = 'employee' WHERE email IN ('john@example.com', 'jane@example.com');

-- Insert work settings if not exists
INSERT IGNORE INTO work_settings (id, work_start_time, work_end_time, late_threshold, created_at, updated_at) 
VALUES (1, '08:00:00', '17:00:00', '09:00:00', NOW(), NOW());

-- Check current user roles
SELECT name, email, role FROM users;