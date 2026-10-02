<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Unset all auth session credentials
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['role']);
unset($_SESSION['full_name']);
unset($_SESSION['admin_logged_in']);

header('Location: ../login.php');
exit();
