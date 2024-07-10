<?php
require_once __DIR__ . '/../config/database.php';

class AuthController {
    public function login($email, $password) {
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $this->generateToken($user['id']);
        } else {
            return false;
        }
    }

    public function signup($username, $email, $password) {
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            return false; // Username or email already exists
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');

        if ($stmt->execute([$username, $email, $hash])) {
            return $this->generateToken($pdo->lastInsertId());
        } else {
            return false;
        }
    }

    private function generateToken($userId) {
        $payload = [
            'id' => $userId,
            'exp' => time() + (3 * 24 * 60 * 60) // Token valid for 3 days
        ];

        $token = base64_encode(json_encode($payload));

        return $token;
    }

    public function verifyToken($token) {
        $payload = json_decode(base64_decode($token), true);
    
        if (json_last_error() !== JSON_ERROR_NONE || !isset($payload['id']) || !isset($payload['exp'])) {
            return false;
        }
    
        if ($payload['exp'] < time()) {
            return false;
        }
    
        return $payload['id'];
    }

    public function refreshToken($token) {
        $userId = $this->verifyToken($token);

        if ($userId) {
            return $this->generateToken($userId);
        } else {
            return false;
        }
    }
}
?>