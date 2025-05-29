<?php
require_once "auth_check.php";


$user_id = $_SESSION["user_id"];

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();
$conn->close();

session_destroy();
setcookie("remember_user", "", time() - 3600, "/");

session_start();
$_SESSION["success_message"] = "Το προφίλ σας διαγράφηκε.";

header("Location: register.php");
exit();
