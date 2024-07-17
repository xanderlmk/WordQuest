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
            $token = $this->generateToken($user['id']);
            $_SESSION['auth_token'] = $token;

            return $token;
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
            $token = $this->generateToken($this->pdo->lastInsertId());
            $_SESSION['auth_token'] = $token;

            return $token;
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
        $payload = $this->base64UrlEncode(json_encode(['id' => $userId, 'exp' => time() + (3 * 24 * 60 * 60)]));
        $signature = $this->base64UrlEncode(hash_hmac('sha256', "$header.$payload", $this->secretKey, true));
    
        error_log("Generated token: $header.$payload.$signature");
        return "$header.$payload.$signature";
    }

    public function verifyToken($token) {
        error_log("verifyToken called with token: $token");
    
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            error_log("Token does not have 3 parts");
            return false;
        }
    
        list($header, $payload, $signature) = $parts;
        $headerDecoded = $this->base64UrlDecode($header);
        $payloadDecoded = $this->base64UrlDecode($payload);
        $header = json_decode($headerDecoded, true);
        $payload = json_decode($payloadDecoded, true);
    
        error_log("Header: " . print_r($header, true));
        error_log("Payload: " . print_r($payload, true));
    
        if ($header['alg'] !== 'HS256') {
            error_log("Algorithm is not HS256");
            return false;
        }
    
        $expectedSignature = $this->base64UrlEncode(hash_hmac('sha256', "$parts[0].$parts[1]", $this->secretKey, true));
        error_log("Expected signature: $expectedSignature");
        error_log("Actual signature: $signature");
    
        if ($signature !== $expectedSignature) {
            error_log("Signature does not match");
            return false;
        }
    
        if ($payload['exp'] < time()) {
            error_log("Token has expired");
            return false;
        }
    
        error_log("Token is valid, user ID: " . $payload['id']);
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