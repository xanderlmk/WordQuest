<?php

require_once '../src/config/session.php';
require_once '../src/config/logger.php';
require_once '../src/middleware/AuthMiddleware.php';
require_once '../src/controllers/UserController.php';
require_once '../src/controllers/GameController.php';

$logger = new Logger();
$logger->console_log("game.php");

$authMiddleware = new AuthMiddleware();
$authMiddleware->checkSession();

$userController = new UserController();
$user = $userController->getUser();

if ($user) {
    $username = $user['username'];
    $token = $_SESSION['auth_token'];

    $gameController = new GameController();
    $game = $gameController->loadGame($token, $username);

    if (!$game) {
        header('Location: game_settings.php');
        exit();
    } else {
        $secretWord = $game['secret_word'];
    }
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