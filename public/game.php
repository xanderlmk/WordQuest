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
        $wordLength = strlen($secretWord);  //  test
        $maxAttempts = 5;
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
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(<?php echo $wordLength; ?>, 1fr);
            gap: 10px;
            max-width: 300px;
            margin: auto;
        }
        .grid input {
            width: 100%;
            text-align: center;
            font-size: 2rem;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <h2>Wordle Game</h2>
    <form method="POST" action="process_guess.php">
        <div class="grid">
            <?php for ($i = 0; $i < $maxAttempts; $i++): ?>
                <?php for ($j = 0; $j < $wordLength; $j++): ?>
                    <input type="text" name="guess[<?php echo $i; ?>][<?php echo $j; ?>]" maxlength="1" required>
                <?php endfor; ?>
            <?php endfor; ?>
        </div>
        <br>
        <button type="submit">Submit Guess</button>
    </form>
    <form method="POST" action="">
        <button type="submit" name="logout" value="1">Logout</button>
    </form>
</body>
</html>