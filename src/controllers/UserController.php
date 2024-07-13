<?php

require_once '../src/config/session.php';
require_once '../src/controllers/AuthController.php';
require_once __DIR__ . '/../config/database.php';

class UserController {

    private $pdo;
    private $authController;

    public function __construct() {
        global $pdo;

        $this->pdo = $pdo;
        $this->authController = new AuthController($this->pdo);
    }

    public static function isAuthenticated() {
        return isset($_SESSION['auth_token']);
    }

    public function getUser() { // This should not be static to access $this
        if (!self::isAuthenticated()) {
            return null;
        }

        return $this->getUserByToken($_SESSION['auth_token']);
    }

    public static function logout() {
        session_destroy();
        header('Location: login.php');

        exit();
    }

    public static function handleLogout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
            self::logout();
        }
    }

    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: login.php');

            exit();
        }
    }

    public static function redirectIfAuthenticated() {
        if (self::isAuthenticated()) {
            header('Location: index.php');
            exit();
        }
    }

    private function getUserByToken($token) {
        $userId = $this->authController->verifyToken($token);

        if ($userId) {
            $stmt = $this->pdo->prepare('SELECT username, email FROM users WHERE id = ?');
            $stmt->execute([$userId]);

            return $stmt->fetch();
        }
        return null;
    }
}
?>