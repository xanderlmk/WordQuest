<?php

$title = "Login";
ob_start();

?>

<h2>Login</h2>
<div id="error-message" style="color: red;"></div>
<form id="login-form">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="signup.php">Sign up</a></p>

<?php

$content = ob_get_clean();
require '../src/views/layouts/main.php';

?>

<!-- <?php

// require_once '../src/config/session.php';
// require_once '../src/config/logger.php';
// require_once '../src/controllers/AuthController.php';
// require_once '../src/controllers/UserController.php';

// $logger = new Logger();
// $logger->console_log("login.php");

// UserController::redirectIfAuthenticated();
// $authController = new AuthController();

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     $token = $authController->login($email, $password);
    
//     if ($token) {
//         $_SESSION['auth_token'] = $token;
//         header('Location: user.php');

//         exit();

//     } else {
//         $error = 'Invalid login credentials';
//     }
// }
// ?>

// <!DOCTYPE html>
// <html lang="en">
// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Login</title>
//     <link rel="stylesheet" href="assets/css/style.css">
// </head>
// <body>
//     <h2>Login</h2>
//     <?php if (isset($error)): ?>
//         <p style="color: red;"><?php echo $error; ?></p>
//     <?php endif; ?>
//     <form method="POST" action="">
//         <label for="email">Email:</label>
//         <input type="email" id="email" name="email" required>
//         <br>
//         <label for="password">Password:</label>
//         <input type="password" id="password" name="password" required>
//         <br>
//         <button type="submit">Login</button>
//     </form>
//     <p>Don't have an account? <a href="signup.php">Sign up</a></p>
// </body>
</html> -->