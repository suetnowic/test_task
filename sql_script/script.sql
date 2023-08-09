CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50),
    phone VARCHAR(20),
    email VARCHAR(50),
    password VARCHAR(255),
    PRIMARY KEY (id)
)