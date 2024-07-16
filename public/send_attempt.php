<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/GameController.php';
require_once '../src/controllers/AuthController.php';

$gameController = new GameController();
$gameController->handleAttemptRequest();

?>