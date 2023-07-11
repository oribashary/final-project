USE dbShnkr23stud2;


CREATE TABLE tbl_231_gardens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT
);

INSERT INTO tbl_231_gardens (name, description) VALUES
('The Barking Lot', 'Where every bark is music to our ears!'),
('Pawcasso\'s Playground', 'Where dogs unleash their inner artist!'),
('Fido\'s Fiesta', 'Where every tail is wagging!');
