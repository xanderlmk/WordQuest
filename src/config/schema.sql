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
    secret_word VARCHAR(8) NOT NULL,
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
CREATE TABLE word_x (   -- in our case from word_4 -> word_8
    id INT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(x) UNIQUE NOT NULL
);

--  Fill tables by example words

    -- INSERT INTO words_4 (word) VALUES ('tree'), ('book'), ('star'), ('wolf');
    -- INSERT INTO words_5 (word) VALUES ('apple'), ('bread'), ('crane'), ('flame');
    -- INSERT INTO words_6 (word) VALUES ('orange'), ('planet'), ('stream'), ('bridge');
    -- INSERT INTO words_7 (word) VALUES ('monster'), ('picture'), ('teacher'), ('village');
    -- INSERT INTO words_8 (word) VALUES ('elephant'), ('mountain'), ('alphabet'), ('building');

-- Attempts Table
CREATE TABLE attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    attempt_word VARCHAR(5) NOT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id)
);