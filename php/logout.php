<?php
session_start();

session_destroy();

setcookie("remember_user", "", time() - 3600, "/");

session_start();
$_SESSION["success_message"] = "Αποσυνδεθήκατε με επιτυχία.";

header("Location: login.php");
exit();
