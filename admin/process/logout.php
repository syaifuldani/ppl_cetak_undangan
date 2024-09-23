<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: ../login_admin.php");
exit();
?>
