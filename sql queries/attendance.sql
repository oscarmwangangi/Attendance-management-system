-- Create the database
CREATE DATABASE IF NOT EXISTS attache_management;
USE attache_management;

-- Create the admin table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create the answers table
CREATE TABLE IF NOT EXISTS answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attache_id INT NOT NULL,
    question_id INT NOT NULL,
    answer VARCHAR(255) NOT NULL,
    name VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (attache_id) REFERENCES attache(id),
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

-- Create the attache table

CREATE TABLE IF NOT EXISTS attache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    ID_number VARCHAR(100) NOT NULL,
    school VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    date_joined DATE DEFAULT NULL,
    date_leaving DATE DEFAULT NULL,
    school_supervisor_name VARCHAR(255) DEFAULT NULL,
    school_supervisor_number VARCHAR(50) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone_number VARCHAR(100) DEFAULT NULL,
    Department VARCHAR(100) DEFAULT NULL,
    Supervisor VARCHAR(100) DEFAULT NULL,
    verified_at DATETIME DEFAULT NULL
);


-- Create the attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attache_id INT DEFAULT NULL,
    check_in_date DATE DEFAULT NULL,
    check_out_datetime TIMESTAMP DEFAULT NULL,
    check_in_time TIME DEFAULT NULL,
    FOREIGN KEY (attache_id) REFERENCES attache(id)
);

-- Create the checkins table
CREATE TABLE IF NOT EXISTS checkins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attache_id INT NOT NULL,
    checkin_time DATETIME NOT NULL,
    checkout_time DATETIME DEFAULT NULL,
    name VARCHAR(255) DEFAULT NULL,
    Supervisor VARCHAR(255) DEFAULT NULL,
    Department VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (attache_id) REFERENCES attache(id)
);

-- Create the mdas table
CREATE TABLE IF NOT EXISTS mdas (
    mda_code VARCHAR(11) PRIMARY KEY,
    mda_name VARCHAR(250) NOT NULL,
    mda_abbr VARCHAR(100) DEFAULT NULL,
    ministry_associated VARCHAR(20) DEFAULT NULL
);

-- Create the otp table
CREATE TABLE IF NOT EXISTS otp (
    email VARCHAR(190) PRIMARY KEY,
    otp VARCHAR(6) NOT NULL,
    expiry DATETIME NOT NULL
);

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    otp VARCHAR(6) DEFAULT NULL,
    otp_expiry DATETIME DEFAULT NULL,
    checkin_time DATETIME DEFAULT NULL
);

-- create the Department table
CREATE TABLE if Not EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);


-- Create the questions table
CREATE TABLE IF NOT EXISTS questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

INSERT INTO questions (question) VALUES
('What is your mother\'s maiden name?'),
('What was the name of your first pet?'),
('What was the make of your first car?'),
('What elementary school did you attend?'),
('In what city were you born?'),
('What is your favorite book?'),
('What is your favorite movie?'),
('What is the name of the street you grew up on?'),
('What was your childhood nickname?'),
('What is your favorite food?'),
('What was the name of your first employer?'),
('Who was your childhood hero?'),
('What is the name of your first school?'),
('What was the name of your best friend in high school?'),
('What is the name of your favorite teacher?'),
('What was your dream job as a child?'),
('What is your favorite hobby?'),
('What was the model of your first mobile phone?'),
('What is your favorite sports team?'),
('What is your favorite color?');



