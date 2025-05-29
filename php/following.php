<?php
require_once "auth_check.php";
include "header.php";

$user_id = $_SESSION["user_id"];
$search = $_GET["q"] ?? "";

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

echo '<main class="container">';

echo "<h2>Ακολουθώ</h2>";
?>

<form method="GET" style="margin-bottom: 1rem;">
  <input type="text" name="q" placeholder="Αναζήτηση username..." value="<?= htmlspecialchars($search) ?>">
  <button type="submit" class="page-btn small"><i class="fas fa-search"></i>Αναζήτηση</button>
</form>

<?php
$stmt = $conn->prepare("
  SELECT u.id, u.username FROM follows f
  JOIN users u ON u.id = f.followed_id
  WHERE f.follower_id = ? AND u.username LIKE CONCAT('%', ?, '%')
");
$stmt->bind_param("is", $user_id, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Δεν βρέθηκαν χρήστες που ακολουθείτε με αυτό το όνομα.</p>";
} else {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $username = htmlspecialchars($row["username"]);
        echo "<li><a href='user_profile.php?user_id=$id' class='page-btn small'><i class='fas fa-user'></i>$username</a>";
        if ($id != $user_id) {
            echo " <a href='unfollow_user.php?user_id=$id' class='page-btn small danger' onclick=\"return confirm('Άρση ακολουθίας αυτού του χρήστη;')\"><i class='fas fa-times-circle'></i>Αφαίρεση</a>";
        }
        echo "</li>";
    }
    echo "</ul>";
}

echo "</main>";

$stmt->close();
$conn->close();
include "footer.php";
