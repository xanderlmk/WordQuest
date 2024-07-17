<?php

function is_mobile() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    return preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/', $user_agent);
}

ob_start();

require (is_mobile() ? __DIR__ . '/../layouts/mobile.php' : __DIR__ . '/../layouts/desktop.php');

$content = ob_get_clean();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/public/assets/css/global.css">
</head>
<body>
    <?php echo $content; ?>
    <script src="/public/assets/js/script.js"></script>
</body>
</html>