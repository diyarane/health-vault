-- ============================================================
-- Health Vault — Seed Data
-- Run AFTER database/schema.sql
-- ============================================================

USE health_vault;

-- ------------------------------------------------------------
-- Admin account
-- Email:    admin@healthvault.local
-- Password: Admin@123   (hashed below with password_hash / bcrypt)
-- ------------------------------------------------------------
INSERT INTO admins (name, email, mobile_number, password, created_at, updated_at)
VALUES ('Health Vault Admin', 'admin@healthvault.local', '9999999999',
        '$2y$10$kVvhTvGyFcgwEd31PK0.IOsP2ciQjZlReLFPf7ZBGX3w0dbJKWD1S',
        NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- ------------------------------------------------------------
-- Medical cards — dates spread across today / yesterday / last 7 days / older
-- ------------------------------------------------------------
INSERT INTO medical_cards
    (reference_number, full_name, date_of_birth, gender, blood_group, contact_number, email, address,
     emergency_contact_name, emergency_contact_number, known_allergies, notes, created_at, updated_at)
VALUES
    ('HV-2026-A1B2C3', 'Asha Verma', '1990-04-12', 'Female', 'O+', '9876543210', 'asha.verma@example.com',
     '221B Lake Road, Nashik', 'Rohit Verma', '9876500000', 'Penicillin', 'Regular checkups annually.',
     NOW(), NOW()),

    ('HV-2026-D4E5F6', 'Karan Mehta', '1985-11-02', 'Male', 'B+', '9123456780', 'karan.mehta@example.com',
     '14 MG Road, Pune', 'Simran Mehta', '9123400000', 'None known', 'History of mild asthma.',
     NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY),

    ('HV-2026-G7H8I9', 'Priya Nair', '1996-06-21', 'Female', 'A+', '9988776655', 'priya.nair@example.com',
     '9 Marine Drive, Mumbai', 'Anil Nair', '9988700000', 'Shellfish', NULL,
     NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

    ('HV-2026-J1K2L3', 'Rahul Singh', '1978-01-30', 'Male', 'AB-', '9012345678', 'rahul.singh@example.com',
     '55 Civil Lines, Nagpur', 'Meera Singh', '9012300000', 'None known', 'Diabetic — Type 2.',
     NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),

    ('HV-2026-M4N5O6', 'Sneha Kulkarni', '2001-09-09', 'Female', 'O-', '9876501234', 'sneha.kulkarni@example.com',
     '78 FC Road, Pune', 'Vijay Kulkarni', '9876500001', 'Latex', NULL,
     NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY),

    ('HV-2026-P7Q8R9', 'Aditya Rao', '1992-03-15', 'Male', 'B-', '9345678901', 'aditya.rao@example.com',
     '32 Jubilee Hills, Hyderabad', 'Kavya Rao', '9345600000', 'None known', 'Post-surgical follow-up.',
     NOW() - INTERVAL 20 DAY, NOW() - INTERVAL 20 DAY)
ON DUPLICATE KEY UPDATE full_name = VALUES(full_name);

-- ------------------------------------------------------------
-- Inquiries — mix of read/unread
-- ------------------------------------------------------------
INSERT INTO inquiries (name, email, subject, message, status, admin_response, responded_at, created_at, updated_at)
VALUES
    ('Neha Joshi', 'neha.joshi@example.com', 'Question about card renewal',
     'Hi, how do I renew my medical card once it expires? Thanks.', 'unread', NULL, NULL, NOW(), NOW()),

    ('Sameer Khan', 'sameer.khan@example.com', 'Unable to find my reference number',
     'I lost the reference number for my medical card. Can you help me retrieve it?', 'unread', NULL, NULL,
     NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY),

    ('Ritu Sharma', 'ritu.sharma@example.com', 'Thank you',
     'Just wanted to say the medical card system worked great during my hospital visit.', 'read',
     'Thank you for the kind words, Ritu! Glad it helped.', NOW() - INTERVAL 2 DAY,
     NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 2 DAY),

    ('Vikram Patel', 'vikram.patel@example.com', 'Update contact number',
     'I need to update the phone number on my medical card record. Please advise.', 'read', NULL, NULL,
     NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 6 DAY);

-- ------------------------------------------------------------
-- Pages — About Us / Contact Us editable content
-- ------------------------------------------------------------
INSERT INTO pages (slug, title, content, contact_email, contact_phone, contact_address, updated_at)
VALUES
    ('about', 'About Us',
     'Health Vault is a simple, secure medical card generation system that helps individuals store their essential medical information and retrieve it instantly using a unique reference number. Our goal is to make critical health details accessible during emergencies, hospital visits, and routine checkups — without paperwork or delay.',
     NULL, NULL, NULL, NOW()),

    ('contact', 'Contact Us',
     'Have a question about your medical card or need help with your account? Send us a message using the form below and our team will get back to you as soon as possible.',
     'support@healthvault.local', '+91 98765 00000', '221B Lake Road, Nashik, Maharashtra, India', NOW())
ON DUPLICATE KEY UPDATE content = VALUES(content);
