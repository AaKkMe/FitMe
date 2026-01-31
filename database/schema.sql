-- FitMe Gym Management System - Database Schema
-- Run this in phpMyAdmin or MySQL CLI to create the database

CREATE DATABASE IF NOT EXISTS fitme_gym CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fitme_gym;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'trainer', 'user') NOT NULL DEFAULT 'user',
    phone VARCHAR(20),
    membership_expires DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- Workout categories
CREATE TABLE IF NOT EXISTS workout_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Workouts table
CREATE TABLE IF NOT EXISTS workouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    category_id INT,
    trainer_id INT,
    schedule_day VARCHAR(20) NOT NULL,
    schedule_time TIME NOT NULL,
    duration_minutes INT DEFAULT 60,
    max_participants INT DEFAULT 20,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES workout_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (trainer_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_trainer (trainer_id),
    INDEX idx_category (category_id),
    INDEX idx_schedule (schedule_day, schedule_time)
);

-- Workout registrations (users enrolled in workouts)
CREATE TABLE IF NOT EXISTS workout_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workout_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (user_id, workout_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_workout (workout_id)
);

-- Attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workout_id INT,
    check_in_date DATE NOT NULL,
    check_in_time TIME NOT NULL,
    notes VARCHAR(255),
    logged_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE SET NULL,
    FOREIGN KEY (logged_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_date (user_id, check_in_date),
    INDEX idx_date (check_in_date)
);

-- Sessions for CSRF (optional)
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    data TEXT,
    last_activity INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- INSERT SAMPLE DATA
-- ============================================

-- Insert workout categories
INSERT INTO workout_categories (name, description) VALUES 
('Cardio', 'Cardiovascular exercises for heart health'),
('Strength', 'Weight training and resistance exercises'),
('Yoga', 'Flexibility and mindfulness'),
('HIIT', 'High-intensity interval training'),
('CrossFit', 'Functional fitness training'),
('Pilates', 'Core strengthening and body conditioning'),
('Zumba', 'Dance fitness program'),
('Spinning', 'Indoor cycling workouts');

-- Insert users (password for all: password)
-- Admin users
INSERT INTO users (name, email, password, role, phone, membership_expires) VALUES 
('Admin User', 'admin@fitme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '555-0100', NULL);

-- Trainer users
INSERT INTO users (name, email, password, role, phone, membership_expires) VALUES 
('Ram Bahadur Thapa', 'trainer@fitme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', '9841234501', NULL),
('Hari Prasad Sharma', 'hari@fitme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', '9841234502', NULL),
('Sita Kumari Gurung', 'sita@fitme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', '9841234503', NULL),
('Krishna Bahadur Rai', 'krishna@fitme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', '9841234504', NULL),
('Maya Devi Tamang', 'maya@fitme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', '9841234505', NULL);

-- Regular members (users)
INSERT INTO users (name, email, password, role, phone, membership_expires) VALUES 
('Test User', 'user@fitme.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234501', '2025-12-31'),
('Bikash Shrestha', 'bikash@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234502', '2025-06-30'),
('Anjali Thapa', 'anjali@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234503', '2025-08-15'),
('Rajesh Karki', 'rajesh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234504', '2025-09-20'),
('Sunita Adhikari', 'sunita@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234505', '2025-07-10'),
('Deepak Pandey', 'deepak@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234506', '2025-11-05'),
('Priya Maharjan', 'priya@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234507', '2025-10-12'),
('Ramesh Poudel', 'ramesh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234508', '2025-12-25'),
('Kopila Rana', 'kopila@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234509', '2025-05-18'),
('Nabin Subedi', 'nabin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234510', '2025-09-08'),
('Puja Bhandari', 'puja@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234511', '2025-08-22'),
('Suresh Limbu', 'suresh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234512', '2025-07-30'),
('Binita KC', 'binita@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234513', '2025-11-14'),
('Dipendra Magar', 'dipendra@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234514', '2025-06-25'),
('Sabina Tharu', 'sabina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9851234515', '2025-10-03');

-- Insert workouts with various trainers and schedules
-- Trainer IDs: Ram=2, Hari=3, Sita=4, Krishna=5, Maya=6
INSERT INTO workouts (name, description, category_id, trainer_id, schedule_day, schedule_time, duration_minutes, max_participants, is_active) VALUES
-- Monday workouts
('Morning Cardio Blast', 'High-energy cardio session to start your week', 1, 2, 'Monday', '06:00:00', 45, 25, 1),
('Power Lifting', 'Heavy compound lifts for strength building', 2, 3, 'Monday', '08:00:00', 60, 15, 1),
('Yoga Flow', 'Gentle yoga for flexibility and relaxation', 3, 4, 'Monday', '10:00:00', 60, 20, 1),
('HIIT Training', 'Intense interval training for fat burning', 4, 5, 'Monday', '17:00:00', 45, 20, 1),
('Evening Spin', 'Indoor cycling with upbeat music', 8, 5, 'Monday', '19:00:00', 45, 30, 1),

-- Tuesday workouts
('Strength & Conditioning', 'Full body strength workout', 2, 3, 'Tuesday', '07:00:00', 60, 18, 1),
('Zumba Dance', 'Latin-inspired dance fitness party', 7, 6, 'Tuesday', '09:00:00', 50, 25, 1),
('CrossFit WOD', 'Workout of the day - functional movements', 5, 2, 'Tuesday', '12:00:00', 60, 15, 1),
('Pilates Core', 'Core strengthening and stability', 6, 4, 'Tuesday', '18:00:00', 55, 18, 1),

-- Wednesday workouts
('Cardio Kickboxing', 'Boxing-inspired cardio workout', 1, 5, 'Wednesday', '06:30:00', 50, 22, 1),
('Olympic Lifting', 'Learn clean, jerk, and snatch techniques', 2, 3, 'Wednesday', '08:30:00', 60, 12, 1),
('Gentle Yoga', 'Relaxing yoga for all fitness levels', 3, 4, 'Wednesday', '11:00:00', 60, 20, 1),
('Tabata HIIT', '20 seconds work, 10 seconds rest intervals', 4, 2, 'Wednesday', '17:30:00', 40, 20, 1),

-- Thursday workouts
('Bodybuilding Split', 'Targeted muscle group training', 2, 3, 'Thursday', '07:00:00', 60, 15, 1),
('Dance Cardio', 'Fun cardio through dance moves', 7, 6, 'Thursday', '09:30:00', 50, 25, 1),
('Power Yoga', 'Challenging yoga with strength focus', 3, 4, 'Thursday', '12:00:00', 60, 18, 1),
('Evening CrossFit', 'Functional fitness for all levels', 5, 2, 'Thursday', '18:30:00', 60, 15, 1),

-- Friday workouts
('Friday Cardio Party', 'End the week with energetic cardio', 1, 5, 'Friday', '06:00:00', 45, 30, 1),
('Deadlift Clinic', 'Perfect your deadlift technique', 2, 3, 'Friday', '08:00:00', 60, 15, 1),
('Yin Yoga', 'Deep stretching and relaxation', 3, 4, 'Friday', '10:30:00', 75, 20, 1),
('HIIT & Core', 'High intensity with core focus', 4, 6, 'Friday', '17:00:00', 45, 20, 1),

-- Saturday workouts
('Weekend Warriors', 'Full body strength and cardio', 5, 2, 'Saturday', '08:00:00', 75, 20, 1),
('Pilates Mat', 'Equipment-free pilates workout', 6, 4, 'Saturday', '09:30:00', 60, 18, 1),
('Spin & Sculpt', 'Cycling combined with strength', 8, 5, 'Saturday', '11:00:00', 60, 25, 1),
('Zumba Saturday', 'Dance your way to fitness', 7, 6, 'Saturday', '16:00:00', 60, 30, 1),

-- Sunday workouts
('Sunday Stretch', 'Gentle stretching and mobility', 3, 4, 'Sunday', '09:00:00', 60, 25, 1),
('CrossFit Basics', 'Introduction to CrossFit movements', 5, 2, 'Sunday', '10:30:00', 60, 15, 1),
('Cardio Mix', 'Variety of cardio exercises', 1, 5, 'Sunday', '17:00:00', 45, 20, 1);

-- Insert workout registrations (members registered for workouts)
-- Member IDs: TestUser=7, Bikash=8, Anjali=9, Rajesh=10, Sunita=11, Deepak=12, Priya=13, Ramesh=14, Kopila=15, Nabin=16, Puja=17, Suresh=18, Binita=19, Dipendra=20, Sabina=21
-- Workout IDs: 1-27 (27 total workouts)
INSERT INTO workout_registrations (user_id, workout_id) VALUES
-- Bikash (user_id 8) - regular attendee
(8, 1), (8, 4), (8, 9), (8, 13), (8, 18),
-- Anjali (user_id 9) - strength focused
(9, 2), (9, 6), (9, 11), (9, 14), (9, 19),
-- Rajesh (user_id 10) - yoga enthusiast
(10, 3), (10, 12), (10, 16), (10, 20), (10, 27),
-- Sunita (user_id 11) - CrossFit fan
(11, 8), (11, 13), (11, 17), (11, 22), (11, 28),
-- Deepak (user_id 12) - dance and cardio
(12, 7), (12, 15), (12, 18), (12, 23), (12, 25),
-- Priya (user_id 13) - all-rounder
(13, 1), (13, 6), (13, 12), (13, 17), (13, 22),
-- Ramesh (user_id 14) - morning person
(14, 1), (14, 2), (14, 10), (14, 14), (14, 18),
-- Kopila (user_id 15) - evening workouts
(15, 4), (15, 9), (15, 17), (15, 20), (15, 27),
-- Nabin (user_id 16) - pilates and yoga
(16, 3), (16, 9), (16, 16), (16, 21), (16, 27),
-- Puja (user_id 17) - HIIT enthusiast
(17, 4), (17, 8), (17, 13), (17, 20), (17, 27),
-- Suresh (user_id 18) - weekend warrior
(18, 22), (18, 23), (18, 24), (18, 25), (18, 27),
-- Test User (user_id 7)
(7, 1), (7, 3), (7, 8), (7, 12);

-- Insert attendance records (past 30 days)
-- Logged by trainers: Ram=2, Hari=3, Sita=4, Krishna=5, Maya=6
INSERT INTO attendance (user_id, workout_id, check_in_date, check_in_time, notes, logged_by) VALUES
-- Recent attendance (last week)
(8, 1, DATE_SUB(CURDATE(), INTERVAL 6 DAY), '06:05:00', NULL, 2),
(9, 2, DATE_SUB(CURDATE(), INTERVAL 6 DAY), '08:02:00', NULL, 3),
(10, 3, DATE_SUB(CURDATE(), INTERVAL 6 DAY), '10:03:00', NULL, 4),
(11, 8, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '12:01:00', NULL, 2),
(12, 7, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '09:32:00', NULL, 6),
(13, 6, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '07:05:00', NULL, 3),
(14, 10, DATE_SUB(CURDATE(), INTERVAL 4 DAY), '06:35:00', NULL, 5),
(15, 9, DATE_SUB(CURDATE(), INTERVAL 4 DAY), '18:28:00', NULL, 4),
(8, 4, DATE_SUB(CURDATE(), INTERVAL 4 DAY), '17:02:00', NULL, 5),
(16, 16, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '12:05:00', NULL, 4),
(17, 13, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '17:31:00', NULL, 2),
(9, 14, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '07:03:00', NULL, 3),
(10, 12, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '11:02:00', NULL, 4),
(11, 17, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '18:33:00', NULL, 2),
(12, 19, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '06:01:00', NULL, 5),
(18, 23, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '08:05:00', NULL, 2),
(14, 19, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '06:02:00', NULL, 5),
(15, 21, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '17:05:00', NULL, 6),
(7, 1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '06:10:00', 'First time', 2),
-- Today's attendance
(8, 1, CURDATE(), '06:03:00', NULL, 2),
(9, 2, CURDATE(), '08:01:00', NULL, 3),
(10, 3, CURDATE(), '10:05:00', NULL, 4),
(13, 1, CURDATE(), '06:08:00', NULL, 2),
(16, 3, CURDATE(), '10:07:00', NULL, 4),
-- Older attendance (2-4 weeks ago)
(8, 4, DATE_SUB(CURDATE(), INTERVAL 14 DAY), '17:03:00', NULL, 5),
(9, 6, DATE_SUB(CURDATE(), INTERVAL 14 DAY), '07:02:00', NULL, 3),
(10, 3, DATE_SUB(CURDATE(), INTERVAL 13 DAY), '10:04:00', NULL, 4),
(11, 8, DATE_SUB(CURDATE(), INTERVAL 12 DAY), '12:02:00', NULL, 2),
(12, 15, DATE_SUB(CURDATE(), INTERVAL 11 DAY), '09:35:00', NULL, 6),
(8, 9, DATE_SUB(CURDATE(), INTERVAL 10 DAY), '18:30:00', NULL, 4),
(13, 12, DATE_SUB(CURDATE(), INTERVAL 9 DAY), '11:05:00', NULL, 4),
(14, 2, DATE_SUB(CURDATE(), INTERVAL 8 DAY), '08:03:00', NULL, 3),
(15, 4, DATE_SUB(CURDATE(), INTERVAL 7 DAY), '17:04:00', NULL, 5);
