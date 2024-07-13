<?php

require_once '../src/config/session.php';

function console_log($message) {
    echo "<script>console.log('PHP: " . addslashes($message) . "');</script>";
}

console_log("index.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Hello, World!</h1>
    <p>Welcome to the WordQuest project.</p>
</body>
</html>