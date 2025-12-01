<?php
session_start();
session_unset();
session_destroy();
header("Location: dummy_login.php");
exit();
?>
