<?php
require_once __DIR__ . '/../config/database.php';

class AuthMiddleware {
    public static function checkSession() {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
            $authController = new AuthController();
            $userId = $authController->verifyToken($token);

            if ($userId) {
                $newToken = $authController->refreshToken($token);
                header('Authorization: Bearer ' . $newToken);

                return $userId;
            }
        }

        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }
}
?>