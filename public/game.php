<?php

require_once '../src/config/session.php';
require_once '../src/config/logger.php';
require_once '../src/middleware/AuthMiddleware.php';

$logger = new Logger();
$logger->console_log("game.php");

$authMiddleware = new AuthMiddleware();
$authMiddleware->checkSession();

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
    <!-- Содержимое игры здесь -->
</body>
</html>