<?php

require_once '../src/config/session.php';
require_once '../src/controllers/AuthController.php';
require_once __DIR__ . '/../config/database.php';

class UserController {

    private $pdo;
    private $authController;

    public function __construct($pdo, $authController) {
        $this->$pdo = $pdo;
        $this->$authController = new AuthController($this->$pdo);
    }

    public static function isAuthenticated() {
        return isset($_SESSION['auth_token']);
    }

    public function getUser() {
        if (!self::isAuthenticated()) {
            return null;
        }

        $userId = $this->$authController->verifyToken($_SESSION['auth_token']);

        if ($userId) {
            return self::getUserById($userId);
        }

        return null;
    }

    public static function logout() {
        session_destroy();
        header('Location: login.php');

        exit();
    }

    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: login.php');

            exit();
        }
    }

    private function getUserById($userId) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        
        return $stmt->fetch();
    }
}
?>