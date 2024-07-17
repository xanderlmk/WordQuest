<?php

require_once '../../../src/config/session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset(); // Unset all of the session variables
    session_destroy(); // Destroy the session

    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
    exit();
}

?>