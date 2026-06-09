<?php
require_once 'config.php';

// Clear session array
$_SESSION = array();

// Destroy session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session completely
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
?>
