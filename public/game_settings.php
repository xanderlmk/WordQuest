<?php

require_once '../src/config/session.php';
require_once '../src/config/logger.php';
require_once '../src/middleware/AuthMiddleware.php';
require_once '../src/controllers/UserController.php';
require_once '../src/controllers/GameController.php';

$logger = new Logger();
$logger->console_log("game_settings.php");

$authMiddleware = new AuthMiddleware();
$authMiddleware->checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameController = new GameController();
    $wordLength = $_POST['word_length'];
    $token = $_SESSION['auth_token'];

    if ($gameController->createGame($wordLength, $token)) {
        header('Location: game.php');
        exit();
    } else {
        echo "Failed to start the game.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Setup</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Game Setup</h2>
    <form method="POST" action="game_settings.php">
        <label for="word_length">Select word length:</label>
        <select id="word_length" name="word_length">
            <option value="4">4 letters</option>
            <option value="5">5 letters</option>
            <option value="6">6 letters</option>
            <option value="7">7 letters</option>
            <option value="8">8 letters</option>
        </select>
        <br>
        <button type="submit">Start Game</button>
    </form>
</body>
</html>