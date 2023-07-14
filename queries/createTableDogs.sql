USE dbShnkr23stud2;

CREATE TABLE tbl_231_dogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    dogName VARCHAR(50),
    breed VARCHAR(50),
    age INT,
    description VARCHAR(255),
    place INT default 0,
    hugs INT default 0
);

-- Insert three dogs into the table
INSERT INTO tbl_231_dogs (user_id, dogName, breed, age, description)
VALUES
    (1, 'Max', 'Labrador Retriever', 3, 'Friendly and playful'),
    (1, 'Bella', 'Golden Retriever', 2, 'Loves to fetch balls'),
    (2, 'Charlie', 'German Shepherd', 4, 'Protective and loyal');
