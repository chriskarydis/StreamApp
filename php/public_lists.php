<?php
require_once "auth_check.php";
include "header.php";

$user_id = $_SESSION["user_id"];
$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

echo '<main class="container">';

echo "<h2>Δημόσιες λίστες από χρήστες που ακολουθείτε</h2>";

$stmt = $conn->prepare("
  SELECT l.id, l.name, u.username, u.id AS user_id
  FROM follows f
  JOIN users u ON u.id = f.followed_id
  JOIN lists l ON l.user_id = u.id
  WHERE f.follower_id = ? AND l.is_public = 1
  ORDER BY l.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Κανείς από αυτούς που ακολουθείτε δεν έχει δημόσιες λίστες.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo'<div class="card">';
            $list_id = $row["id"];
            $list_name = htmlspecialchars($row["name"]);
            $creator_id = $row["user_id"];
            $username = htmlspecialchars($row["username"]);

            echo "<p><b>$list_name</b> από <a href='user_profile.php?user_id=$creator_id' class='page-btn small'>
      <i class='fas fa-user'></i>$username</a>";

            if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != $creator_id) {
                $check = $conn->prepare("SELECT 1 FROM follows WHERE follower_id = ? AND followed_id = ?");
                $check->bind_param("ii", $_SESSION["user_id"], $creator_id);
                $check->execute();
                $check->store_result();
                if ($check->num_rows === 0) {
                    echo " <a href='follow_user.php?user_id=$creator_id' class='page-btn small'><i class='fas fa-plus'></i> Ακολούθησε</a>";
                }
                $check->close();
            }

            echo " <a href='view_list.php?list_id=$list_id' class='page-btn small'><i class='fas fa-eye'></i> Προβολή</a></p>";
        echo "</div>";
    }
}

echo "</main>";

$stmt->close();
$conn->close();
include "footer.php";
