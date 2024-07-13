<?php

require_once '../src/config/session.php';
require_once '../src/middleware/AuthMiddleware.php';
require_once '../src/controllers/UserController.php';

function console_log($message) {
    echo "<script>console.log('PHP: " . addslashes($message) . "');</script>";
}

console_log("user.php");

$authMiddleware = new AuthMiddleware();
$authMiddleware->checkSession();
console_log("Session checked.");

$userController = new UserController();
$user = $userController->getUser();
$userController::handleLogout();
console_log("User getted.");

if ($user) {
    $username = $user['username'];
    $email = $user['email'];
} else {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>User Page</h2>
    <p>Username: <?php echo htmlspecialchars($username); ?></p>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <form method="POST" action="">
        <input type="hidden" name="logout" value="1">
        <button type="submit">Logout</button>
    </form>
</body>
</html>