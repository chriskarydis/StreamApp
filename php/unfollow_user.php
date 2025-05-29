<?php
require_once "auth_check.php";

$follower = $_SESSION["user_id"];
$followed = $_GET["user_id"] ?? null;

if (!$followed || $follower == $followed) exit("Invalid unfollow.");

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
$stmt = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
$stmt->bind_param("ii", $follower, $followed);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION["success_message"] = "Ο χρήστης αφαιρέθηκε από τους ακολουθούμενους.";
header("Location: profile.php");
exit();
