<?php
require_once "auth_check.php";

$list_id = $_GET["list_id"] ?? null;
$user_id = $_SESSION["user_id"];

if (!$list_id || !is_numeric($list_id)) {
    exit("Μη έγκυρο αίτημα.");
}

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

$stmt = $conn->prepare("SELECT id FROM lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    $conn->close();
    exit("Δεν έχετε άδεια να διαγράψετε αυτή τη λίστα.");
}
$stmt->close();

$conn->query("DELETE FROM videos WHERE list_id = $list_id");
$conn->query("DELETE FROM lists WHERE id = $list_id");

$conn->close();

$_SESSION["success_message"] = "Η λίστα διαγράφηκε επιτυχώς.";
header("Location: profile.php");
exit();
