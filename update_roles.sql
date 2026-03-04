-- Update user roles
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
UPDATE users SET role = 'employee' WHERE email IN ('john@example.com', 'jane@example.com');

-- Insert sample announcements
INSERT INTO announcements (title, content, type, meeting_date, is_active, created_at, updated_at) VALUES
('Team Meeting Tomorrow', 'Monthly team meeting will be held tomorrow at 10:00 AM in the conference room.', 'meeting', '2026-02-06 10:00:00', 1, NOW(), NOW()),
('New Company Policy', 'Please review the updated company handbook available on the intranet.', 'general', NULL, 1, NOW(), NOW()),
('System Maintenance', 'Server maintenance scheduled for this weekend. System will be unavailable Saturday 2-4 PM.', 'urgent', NULL, 1, NOW(), NOW());