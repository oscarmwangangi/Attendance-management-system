-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS user_management_attachee;
USE user_management_attachee;

-- Create the users_attachee table
CREATE TABLE IF NOT EXISTS users_attachee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('main_admin', 'second_admin', 'user', '') NOT NULL
);

-- Create the data table
CREATE TABLE IF NOT EXISTS data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users_attachee(id)
);
