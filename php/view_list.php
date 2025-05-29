<?php
session_start();

$list_id = $_GET["list_id"] ?? null;
if (!$list_id) exit("Λείπει το ID της λίστας.");

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

$stmt = $conn->prepare("SELECT name, is_public, user_id FROM lists WHERE id = ?");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$stmt->bind_result($list_name, $is_public, $owner_id);
$stmt->fetch();
$stmt->close();

$current_user = $_SESSION["user_id"] ?? null;

if (!$is_public && $current_user !== $owner_id) {
    exit("Δεν έχετε πρόσβαση σε αυτή τη λίστα.");
}

include "header.php";
?>

<main class="container">

<?php if (!empty($_SESSION["success_message"])): ?>
  <div class="flash-message success"><?= $_SESSION["success_message"] ?></div>
  <?php unset($_SESSION["success_message"]); ?>
<?php endif; ?>

<h2>Λίστα: <?= htmlspecialchars($list_name) ?></h2>

<?php if ($current_user && $current_user == $owner_id): ?>
  <p><a href="youtube_search.php?list_id=<?= $list_id ?>" class="page-btn">
    <i class="fas fa-plus"></i> Προσθέστε βίντεο
  </a></p>
<?php endif; ?>

<?php
if ($current_user && $current_user != $owner_id) {
    $check = $conn->prepare("SELECT 1 FROM follows WHERE follower_id = ? AND followed_id = ?");
    $check->bind_param("ii", $current_user, $owner_id);
    $check->execute();
    $check->store_result();
    $isFollowing = $check->num_rows > 0;
    $check->close();

    if ($isFollowing) {
        echo "<p><i class='fas fa-check-circle'></i> Ακολουθείτε τον δημιουργό αυτής της λίστας.</p>";
    } else {
        echo "<p><a href='follow_user.php?user_id=$owner_id' class='page-btn small'>
          <i class='fas fa-plus'></i> Ακολουθήστε τον δημιουργό</a></p>";
    }
}
?>

<?php
$stmt = $conn->prepare("SELECT v.id, v.title, v.youtube_id, v.created_at FROM videos v WHERE v.list_id = ? ORDER BY v.created_at DESC");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0): ?>
  <p>Η λίστα δεν περιέχει ακόμα βίντεο.</p>
<?php else:
  while ($row = $result->fetch_assoc()):
    $vid_id = $row["id"];
    $title = htmlspecialchars($row["title"]);
    $vid = $row["youtube_id"];
?>
  <div class="video-card">
    <p><strong><?= $title ?></strong></p>
    <iframe width="300" height="180" src="https://www.youtube.com/embed/<?= $vid ?>" frameborder="0" allowfullscreen></iframe><br>

    <?php if ($current_user == $owner_id): ?>
      <form method="POST" action="delete_video.php" onsubmit="return confirm('Σίγουρα διαγραφή;')">
        <input type="hidden" name="video_id" value="<?= $vid_id ?>">
        <input type="hidden" name="list_id" value="<?= $list_id ?>">
        <button type="submit" class="page-btn small danger">
          <i class="fas fa-trash"></i> Αφαίρεση
        </button>
      </form>
    <?php endif; ?>
  </div>
<?php endwhile; endif; ?>

</main>
<?php
$stmt->close();
$conn->close();
include "footer.php";
