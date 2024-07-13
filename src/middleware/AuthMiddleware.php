<?php

require_once '../src/config/session.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/config/database.php';

class AuthMiddleware {
    private $pdo;
    private $authController;

    public function __construct() {
        global $pdo;

        $this->pdo = $pdo;
        $this->authController = new AuthController($pdo);
    }

    public function checkSession() {
        if (isset($_SESSION['auth_token'])) {
            $userId = $this->authController->refreshToken($_SESSION['auth_token']);
            if ($userId) {
                return true;
            }
        }

        header('Location: login.php');
        exit();
    }
}
?>