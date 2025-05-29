<?php
require_once "auth_check.php";
include "header.php";

$view_id = $_GET["user_id"] ?? null;
if (!$view_id || !is_numeric($view_id)) {
    exit("Δεν καθορίστηκε χρήστης.");
}

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

$stmt = $conn->prepare("SELECT firstname, lastname, username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $view_id);
$stmt->execute();
$stmt->bind_result($fname, $lname, $uname, $email);
if (!$stmt->fetch()) {
    $stmt->close();
    $conn->close();
    exit("Ο χρήστης δεν βρέθηκε.");
}
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = ?");
$stmt->bind_param("i", $view_id);
$stmt->execute();
$stmt->bind_result($following);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM follows WHERE followed_id = ?");
$stmt->bind_param("i", $view_id);
$stmt->execute();
$stmt->bind_result($followers);
$stmt->fetch();
$stmt->close();

echo '<main class="container">';

echo "<h2>Προφίλ χρήστη: " . htmlspecialchars($uname) . "</h2>";

echo "<p><strong>Όνομα:</strong> " . htmlspecialchars($fname) . "</p>";
echo "<p><strong>Επώνυμο:</strong> " . htmlspecialchars(string: $lname) . "</p>";
echo "<p><strong>Username:</strong> " . htmlspecialchars($uname) . "</p>";
echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
echo "<p><strong>Ακολουθώ:</strong> $following χρήστη(ες)</p>";
echo "<p><strong>Με ακολουθούν:</strong> $followers χρήστη(ες)</p>";

$current_user = $_SESSION["user_id"] ?? null;

if ($current_user && $current_user != $view_id) {
    $check = $conn->prepare("SELECT 1 FROM follows WHERE follower_id = ? AND followed_id = ?");
    $check->bind_param("ii", $current_user, $view_id);
    $check->execute();
    $check->store_result();
    $isFollowing = $check->num_rows > 0;
    $check->close();

    if ($isFollowing) {
        echo "<p><a href='unfollow_user.php?user_id=$view_id' class='page-btn'>	<i class='fas fa-times-circle'></i> Αφαίρεση από ακολουθούμενους</a></p>";
    } else {
        echo "<p><a href='follow_user.php?user_id=$view_id' class='page-btn'><i class='fas fa-plus'></i> Ακολουθήστε αυτόν τον χρήστη</a></p>";
    }
}


echo "<h3>Δημόσιες λίστες</h3>";

$stmt = $conn->prepare("SELECT id, name FROM lists WHERE user_id = ? AND is_public = 1 ORDER BY created_at DESC");
$stmt->bind_param("i", $view_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Δεν υπάρχουν δημόσιες λίστες.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row["name"]) . "</strong> 
        <a href='view_list.php?list_id=" . $row["id"] . "'class='page-btn small'><i class='fas fa-eye'></i> Προβολή</a></p>";
    }
}

echo "</main>";

$stmt->close();
$conn->close();
include "footer.php";
