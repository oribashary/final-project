USE dbShnkr23stud2;

CREATE TABLE tbl_231_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255),
    username VARCHAR(255),
    password VARCHAR(255),
    role VARCHAR(255)
);

INSERT INTO tbl_231_users (email, username, password, role)
VALUES ('oribashary@gmail.com', 'OriBashary', '123456','admin'), ('giladmeir@gmail.com', 'GiladMeir', '123456','user');