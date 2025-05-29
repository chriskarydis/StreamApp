<?php
require_once "auth_check.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["video_id"], $_POST["list_id"])) {
    $user_id = $_SESSION["user_id"];
    $video_id = (int)$_POST["video_id"];
    $list_id = (int)$_POST["list_id"];

    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

    $stmt = $conn->prepare("SELECT id FROM lists WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $list_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $delete = $conn->prepare("DELETE FROM videos WHERE id = ? AND list_id = ?");
        $delete->bind_param("ii", $video_id, $list_id);
        $delete->execute();
        $_SESSION["success_message"] = "Το βίντεο αφαιρέθηκε.";
    }

    $stmt->close();
    $conn->close();

    header("Location: view_list.php?list_id=$list_id");
    exit();
}
?>
