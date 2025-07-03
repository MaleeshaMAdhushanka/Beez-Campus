CREATE DATABASE IF NOT EXISTS beez;

Use beez;

CREATE TABLE student(
    nic VARCHAR(15) PRIMARY KEY, 
    name VARCHAR(100),
    gender VARCHAR(10),
    address VARCHAR(255),
    contact VARCHAR(15),
    email VARCHAR(100),
    course VARCHAR(50)

);

CREATE TABLE admin(
 id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

INSERT INTO admin(username, password) VALUES('admin', '1234');

