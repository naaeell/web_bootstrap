<?php
session_start();

// Menghapus semua session
$_SESSION = array();

// Menghancurkan session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>
