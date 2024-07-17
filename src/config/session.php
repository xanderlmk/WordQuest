<?php

ini_set('session.cookie_httponly', 1);  // Prevent JavaScript from accessing the session cookie
ini_set('session.cookie_secure', 1);    // Use secure cookies (only via HTTPS)
ini_set('session.use_strict_mode', 1);  // Enforce strict mode for sessions

if (session_status() == PHP_SESSION_NONE) {
    session_start();

    // Regenerate session ID to protect against session fixation attacks
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = true;
    }
}

?>