<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';

$authController = new AuthController($pdo);

if (isset($_SESSION['auth_token'])) {
    $token = $_SESSION['auth_token'];
    $userId = $authController->verifyToken($token);

    if ($userId) {
        header('Location: index.php');
        exit();
    }
}

$title = "Sign Up";
ob_start();

?>

<h2>Sign Up</h2>
<div id="error-message" style="color: red;"></div>
<form id="signup-form">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Sign Up</button>
</form>
<p>Already have an account? <a href="login.php">Login</a></p>

<?php
    $content = ob_get_clean();
    require '../src/views/layouts/main.php';
?>