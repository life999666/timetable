CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE faculty (
    faculty_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    department VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved') DEFAULT 'pending'
);
CREATE TABLE department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) UNIQUE NOT NULL
);
CREATE TABLE subject (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    department_id INT NOT NULL,
    semester INT NOT NULL,
    FOREIGN KEY (department_id) REFERENCES department(department_id) ON DELETE CASCADE
);
CREATE TABLE timetable (
    timetable_id INT AUTO_INCREMENT PRIMARY KEY,
    department VARCHAR(50) NOT NULL,
    semester INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    faculty_id INT NULL,
    day ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    venue VARCHAR(50) NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(faculty_id) ON DELETE SET NULL
);
-- Insert Admin Account
INSERT INTO admin (admin_name, email, password) VALUES 
('Admin User', 'admin@example.com', 'admin123');

-- Insert Departments
INSERT INTO department (department_name) VALUES 
('Computer Science'), 
('Mechanical Engineering'),
 ('Cyber Security Engineering'),
('Electrical Engineering');

-- Insert Faculty (Pending Approval)
INSERT INTO faculty (name, email, department, password) VALUES 
('John Doe', 'johndoe@example.com', 'Computer Science', 'password123');

-- Insert Subjects
INSERT INTO subject (subject_name, department_id, semester) VALUES 
('Database Management', 1, 4),
('COA', 1, 4), 
('OS', 1, 4), 
('COI', 1, 4), 
('DE', 1, 4), 
('OS/DBMS LAB', 1, 4), 
('SCRIPTING Language', 1, 4), 
('Machine Learning', 1, 6), 
('Thermodynamics', 2, 3);

-- Insert Timetable Entries
INSERT INTO timetable (department, semester, subject, faculty_id, day, start_time, end_time, venue) VALUES 
('Computer Science', 4, 'Database Management', 1, 'Monday', '09:00:00', '09:50:00', 'Room 101'),
('Computer Science', 4, 'OS', 1, 'Monday', '09:50:00', '10:50:00', 'Room 101'),
('Computer Science', 4, 'COA', 1, 'Monday', '11:00:00', '11:30:00', 'Room 101'),
('Computer Science', 4, 'COI', 1, 'Monday', '11:30:00', '11:30:00', 'Room 101'),
('Computer Science', 4, 'DE', 1, 'Monday', '13:00:00', '13:30:00', 'Room 101'),
('Computer Science', 4, 'OS/DBMS LAB', 1, 'Monday', '14:00:00', '14:30:00', 'Room 101');
INSERT INTO timetable (department, semester, subject, faculty_id, day, start_time, end_time, venue) VALUES 
-- Tuesday
('Computer Science', 4, 'DBMS', 1, 'Tuesday', '09:00:00', '09:50:00', 'Room 102'),
('Computer Science', 4, 'OS', 1, 'Tuesday', '09:50:00', '10:50:00', 'Room 102'),
('Computer Science', 4, 'COA', 1, 'Tuesday', '11:00:00', '11:30:00', 'Room 102'),
('Computer Science', 4, 'COI', 1, 'Tuesday', '11:30:00', '12:00:00', 'Room 102'),
('Computer Science', 4, 'DE', 1, 'Tuesday', '13:00:00', '13:30:00', 'Room 102'),
('Computer Science', 4, 'OS/DBMS LAB', 1, 'Tuesday', '14:00:00', '14:30:00', 'Lab 1'),

-- Wednesday
('Computer Science', 4, 'DBMS', 1, 'Wednesday', '09:00:00', '09:50:00', 'Room 103'),
('Computer Science', 4, 'OS', 1, 'Wednesday', '09:50:00', '10:50:00', 'Room 103'),
('Computer Science', 4, 'COA', 1, 'Wednesday', '11:00:00', '11:30:00', 'Room 103'),
('Computer Science', 4, 'COI', 1, 'Wednesday', '11:30:00', '12:00:00', 'Room 103'),
('Computer Science', 4, 'DE', 1, 'Wednesday', '13:00:00', '13:30:00', 'Room 103'),
('Computer Science', 4, 'OS/DBMS LAB', 1, 'Wednesday', '14:00:00', '14:30:00', 'Lab 1'),

-- Thursday
('Computer Science', 4, 'DBMS', 1, 'Thursday', '09:00:00', '09:50:00', 'Room 104'),
('Computer Science', 4, 'OS', 1, 'Thursday', '09:50:00', '10:50:00', 'Room 104'),
('Computer Science', 4, 'COA', 1, 'Thursday', '11:00:00', '11:30:00', 'Room 104'),
('Computer Science', 4, 'COI', 1, 'Thursday', '11:30:00', '12:00:00', 'Room 104'),
('Computer Science', 4, 'DE', 1, 'Thursday', '13:00:00', '13:30:00', 'Room 104'),
('Computer Science', 4, 'OS/DBMS LAB', 1, 'Thursday', '14:00:00', '14:30:00', 'Lab 2'),

-- Friday
('Computer Science', 4, 'DBMS', 1, 'Friday', '09:00:00', '09:50:00', 'Room 105'),
('Computer Science', 4, 'OS', 1, 'Friday', '09:50:00', '10:50:00', 'Room 105'),
('Computer Science', 4, 'COA', 1, 'Friday', '11:00:00', '11:30:00', 'Room 105'),
('Computer Science', 4, 'COI', 1, 'Friday', '11:30:00', '12:00:00', 'Room 105'),
('Computer Science', 4, 'DE', 1, 'Friday', '13:00:00', '13:30:00', 'Room 105'),
('Computer Science', 4, 'OS/DBMS LAB', 1, 'Friday', '14:00:00', '14:30:00', 'Lab 2');

