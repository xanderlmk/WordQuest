-- User Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Game Table
CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    secret_word VARCHAR(5) NOT NULL,
    status ENUM('in_progress', 'won', 'lost') DEFAULT 'in_progress',
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Score Table
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    current_score INT DEFAULT 0,
    last_score INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Word Table
-- CREATE TABLE word_x (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     word VARCHAR(5) UNIQUE NOT NULL
-- );

-- Attempts Table
CREATE TABLE attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    attempt_word VARCHAR(5) NOT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id)
);