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
require __DIR__ . '/../src/views/layouts/main.php';

?>