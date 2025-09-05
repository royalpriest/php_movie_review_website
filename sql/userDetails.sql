
CREATE TABLE usersDetails (
                              id INT AUTO_INCREMENT PRIMARY KEY,
                              username VARCHAR(50) NOT NULL UNIQUE,
                              email VARCHAR(100) NOT NULL UNIQUE,
                              password VARCHAR(255) NOT NULL,
                              profile_picture VARCHAR(255) DEFAULT NULL,
                              age INT,
                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movies (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        user_id INT NOT NULL,
                        title VARCHAR(100) NOT NULL,
                        poster VARCHAR(255) DEFAULT NULL,
                        genre VARCHAR(50),
                        year INT,
                        director varchar(255),
                        description TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        FOREIGN KEY (user_id) REFERENCES users(id)
);




