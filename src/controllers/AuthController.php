<?php

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';

class AuthController {
    private $pdo;
    private $secretKey = 'Test_Secret_Key'; //  TODO: Move into .env

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $this->generateToken($user['id']);
        } else {
            return false;
        }
    }

    public function signup($username, $email, $password) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            return false; // Username or email already exists
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');

        if ($stmt->execute([$username, $email, $hash])) {
            return $this->generateToken($this->pdo->lastInsertId());
        } else {
            return false;
        }
    }

    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function generateToken($userId) {
        $header = $this->base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = $this->base64UrlEncode(json_encode(['id' => $userId, 'exp' => time() + (3 * 24 * 60 * 60)])); // Token valid for 3 days
        $signature = $this->base64UrlEncode(hash_hmac('sha256', "$header.$payload", $this->secretKey, true));

        return "$header.$payload.$signature";
    }

    public function verifyToken($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($header, $payload, $signature) = $parts;
        $header = json_decode($this->base64UrlDecode($header), true);
        $payload = json_decode($this->base64UrlDecode($payload), true);

        if ($header['alg'] !== 'HS256') {
            return false;
        }

        $expectedSignature = $this->base64UrlEncode(hash_hmac('sha256', "$header.$payload", $this->secretKey, true));

        if ($signature !== $expectedSignature) {
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