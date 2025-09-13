CREATE DATABASE IF NOT EXISTS user_management;
USE user_management;

CREATE TABLE IF NOT EXISTS users (
    email VARCHAR(100) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    birthYear INTEGER NOT NULL,
    isMale BOOLEAN NOT NULL
);

INSERT INTO users (email, name, password, birthYear, isMale) VALUES
('admin@example.com', 'Administrator', 'd033e22ae348aeb5660fc2140aec35850c4da997', 2005, TRUE),
('user@example.com', 'Regular User', 'ee11cbb19052e40b07aac0ca060c23ee', 1995, FALSE),
('guest@example.com', 'Guest User', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 1990, TRUE),
('flag_admin@ctf.com', 'Flag Admin', 'impossibletoguesspassword123', 1985, TRUE);
