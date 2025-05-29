<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_user"])) {
    $_SESSION["user_id"] = $_COOKIE["remember_user"];

    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->bind_result($uname);
    $stmt->fetch();
    $_SESSION["username"] = $uname ?? "user";
    $stmt->close();
    $conn->close();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
