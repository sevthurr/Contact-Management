
CREATE DATABASE IF NOT EXISTS contacts_db;
USE contacts_db;

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    workphone VARCHAR(20),
    homephone VARCHAR(20),
    email VARCHAR(255) UNIQUE NOT NULL,
    createdByID INT NOT NULL,
    createdDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


USE contacts_db;

INSERT INTO contacts (firstname, lastname, birthdate, workphone, homephone, email, createdByID, createdDate) VALUES
('John', 'Doe', '1990-05-15', '123-456-7890', '321-654-0987', 'johndoe@example.com', 1, NOW()),
('Jane', 'Smith', '1985-08-22', '234-567-8901', '432-765-1098', 'janesmith@example.com', 1, NOW()),
('Alice', 'Johnson', '1992-01-30', '345-678-9012', '543-876-2109', 'alicejohnson@example.com', 1, NOW()),
('Bob', 'Williams', '1988-07-11', '456-789-0123', '654-987-3210', 'bobwilliams@example.com', 1, NOW()),
('Charlie', 'Brown', '1995-11-25', '567-890-1234', '765-098-4321', 'charliebrown@example.com', 1, NOW()),
('David', 'Miller', '1983-04-09', '678-901-2345', '876-109-5432', 'davidmiller@example.com', 1, NOW()),
('Emma', 'Davis', '1991-09-17', '789-012-3456', '987-210-6543', 'emmadavis@example.com', 1, NOW()),
('Frank', 'Wilson', '1987-06-05', '890-123-4567', '098-321-7654', 'frankwilson@example.com', 1, NOW()),
('Grace', 'Martinez', '1993-02-14', '901-234-5678', '109-432-8765', 'gracemartinez@example.com', 1, NOW()),
('Henry', 'Taylor', '1989-12-01', '012-345-6789', '210-543-9876', 'henrytaylor@example.com', 1, NOW());

