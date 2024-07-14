<?php

require_once '../src/config/session.php';
require_once '../src/config/logger.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/UserController.php';

$logger = new Logger();
$logger->console_log("signup.php");

UserController::redirectIfAuthenticated();
$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $token = $authController->signup($username, $email, $password);
    
    if ($token) {
        header('Authorization: Bearer ' . $token);
        header('Location: index.php');

        exit();

    } else {
        $error = 'Registration failed. Username or email might already be taken.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Sign Up</h2>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
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
</body>
</html>