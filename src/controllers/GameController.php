<?php
require_once '../src/config/session.php';
require_once '../src/config/logger.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/UserController.php';

$logger = new Logger();
$logger->console_log("GameController.php");

class GameController {
    
    private $pdo;
    private $authController;

    public function __construct() {
        global $pdo;

        $this->pdo = $pdo;
        $this->authController = new AuthController();
    }

    public function createGame($wordLength, $token) {
        $userId = $this->authController->verifyToken($token);

        if (!$userId) {
            return false;
        }

        // Check if there is an active game and mark it as lost
        $stmt = $this->pdo->prepare('UPDATE games SET status = "lost", end_time = NOW() WHERE user_id = ? AND status = "in_progress"');
        $stmt->execute([$userId]);

        // Select a random word from the appropriate table
        $stmt = $this->pdo->query("SELECT word FROM words_{$wordLength} ORDER BY RAND() LIMIT 1");
        $word = $stmt->fetchColumn();

        // Create a new game
        $stmt = $this->pdo->prepare('INSERT INTO games (user_id, secret_word) VALUES (?, ?)');
        $stmt->execute([$userId, $word]);

        return true;
    }

    public function loadGame($token, $username) {
        $userId = $this->authController->verifyToken($token);
        if (!$userId) {
            return false; // Invalid token
        }

        // Retrieve username from database
        $stmt = $this->pdo->prepare('SELECT username FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $dbUsername = $stmt->fetchColumn();

        if ($username !== $dbUsername) {
            return false; // Username does not match
        }

        // Retrieve the last game with status 'in_progress'
        $stmt = $this->pdo->prepare('SELECT * FROM games WHERE user_id = ? AND status = "in_progress" ORDER BY start_time DESC LIMIT 1');
        $stmt->execute([$userId]);
        $game = $stmt->fetch();

        if (!$game) {
            return false; // No active game found
        }

        return $game;
    }
}

?>