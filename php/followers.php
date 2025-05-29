<?php
require_once "auth_check.php";
include "header.php";

$user_id = $_SESSION["user_id"];
$search = $_GET["q"] ?? "";

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

echo '<main class="container">';

echo "<h2>Με ακολουθούν</h2>";
?>

<form method="GET" style="margin-bottom: 1rem;">
  <input type="text" name="q" placeholder="Αναζήτηση username..." value="<?= htmlspecialchars($search) ?>">
  <button type="submit" class="page-btn small"><i class="fas fa-search"></i>Αναζήτηση</button>
</form>

<?php
$stmt = $conn->prepare("
  SELECT u.id, u.username FROM follows f
  JOIN users u ON u.id = f.follower_id
  WHERE f.followed_id = ? AND u.username LIKE CONCAT('%', ?, '%')
");
$stmt->bind_param("is", $user_id, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Δεν βρέθηκαν χρήστες που σας ακολουθούν με αυτό το όνομα.</p>";
} else {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $username = htmlspecialchars($row["username"]);

        $check = $conn->prepare("SELECT 1 FROM follows WHERE follower_id = ? AND followed_id = ?");
        $check->bind_param("ii", $user_id, $id);
        $check->execute();
        $check->store_result();
        $isFollowing = $check->num_rows > 0;
        $check->close();

        echo "<li><a href='user_profile.php?user_id=$id' class='page-btn small'><i class='fas fa-user'></i>$username</a>";

        if ($id != $user_id) {
            if ($isFollowing) {
                echo " <a href='unfollow_user.php?user_id=$id' class='page-btn small danger'><i class='fas fa-times-circle'></i> Αφαίρεση</a>";
            } else {
                echo " <a href='follow_user.php?user_id=$id' class='page-btn small'><i class='fas fa-plus'></i> Ακολουθήστε</a>";
                echo " <a href='remove_follower.php?user_id=$id' class='page-btn small danger' onclick=\"return confirm('Αφαίρεση αυτού του ακολούθου;')\">❌ Αφαίρεση</a>";
            }
        }

        echo "</li>";

    }
    echo "</ul>";
}

echo "</main>";

$stmt->close();
$conn->close();
include "footer.php";
