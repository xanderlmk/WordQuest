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
}

?>