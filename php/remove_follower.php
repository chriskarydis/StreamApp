<?php
require_once "auth_check.php";

$current_user = $_SESSION["user_id"];
$target_id = $_GET["user_id"] ?? null;

if (!$target_id || !is_numeric($target_id)) {
    exit("Μη έγκυρο αίτημα.");
}

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

$stmt = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
$stmt->bind_param("ii", $target_id, $current_user);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION["success_message"] = "Ο χρήστης αφαιρέθηκε από τους ακόλουθούς σας.";
header("Location: followers.php");
exit();
