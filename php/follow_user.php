<?php
require_once "auth_check.php";

$follower = $_SESSION["user_id"];
$followed = $_GET["user_id"] ?? null;

if (!$followed || $follower == $followed) exit("Invalid follow.");

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
$stmt = $conn->prepare("REPLACE INTO follows (follower_id, followed_id) VALUES (?, ?)");
$stmt->bind_param("ii", $follower, $followed);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION["success_message"] = "Ο χρήστης προστέθηκε στους ακολουθούμενους.";
header("Location: profile.php");
exit();
