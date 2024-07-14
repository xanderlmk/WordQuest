<?php

class Logger {

    public function __construct() {

    }

    function console_log($message) {
        echo "<script>console.log('PHP: " . addslashes($message) . "');</script>";
    }

}
?>