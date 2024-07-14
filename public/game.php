<?php

require_once '../src/config/session.php';
require_once '../src/config/logger.php';
require_once '../src/middleware/AuthMiddleware.php';
require_once '../src/controllers/UserController.php';

$logger = new Logger();
$logger->console_log("game.php");

$authMiddleware = new AuthMiddleware();
$authMiddleware->checkSession();

$userController = new UserController();
$user = $userController->getUser();

$authController = new AuthController();
$userId = $authController->verifyToken($_SESSION['auth_token']);

$stmt = $pdo->prepare('SELECT secret_word FROM games WHERE user_id = ? AND status = "in_progress"');
$stmt->execute([$userId]);

$secretWord = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Wordle Game</h2>
    <?php if ($secretWord): ?>
        <p>Secret Word: <?php echo htmlspecialchars($secretWord); ?></p>
    <?php else: ?>
        <p>No active game found.</p>
    <?php endif; ?>
    <form method="POST" action="">
        <button type="submit" name="logout" value="1">Logout</button>
    </form>
</body>
</html>