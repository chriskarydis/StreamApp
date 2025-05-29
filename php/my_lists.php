<?php
require_once "auth_check.php";
include "header.php";

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
$user_id = $_SESSION["user_id"];

$page = max(1, (int)($_GET["page"] ?? 1));
$limit = (int)($_GET["per_page"] ?? 10);
$offset = ($page - 1) * $limit;

$count_stmt = $conn->prepare("SELECT COUNT(*) FROM lists WHERE user_id = ?");
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_stmt->bind_result($total_lists);
$count_stmt->fetch();
$count_stmt->close();

$stmt = $conn->prepare("SELECT id, name, is_public, created_at FROM lists WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="container">

<h2>Οι λίστες μου</h2>

<form method="GET" style="margin-bottom: 1rem;">
  Εμφάνιση ανά:
  <select name="per_page" class="per-page-select" onchange="this.form.submit()">
    <option value="5" <?= $limit === 5 ? 'selected' : '' ?>>5</option>
    <option value="10" <?= $limit === 10 ? 'selected' : '' ?>>10</option>
    <option value="25" <?= $limit === 25 ? 'selected' : '' ?>>25</option>
  </select>
  <input type="hidden" name="page" value="1">
</form>

<?php if ($total_lists === 0): ?>
  <p>⚠️ Δεν έχετε δημιουργήσει λίστες ακόμα.</p>
<?php else: ?>
  <p><i class="fas fa-list-ol"></i> Βρέθηκαν συνολικά <?= $total_lists ?> λίστες.</p>

  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="card">
      <p><strong><?= htmlspecialchars($row["name"]) ?></strong>
      (<?= $row["is_public"] ? "Δημόσια" : "Ιδιωτική" ?>)</p>
      <a href="view_list.php?list_id=<?= $row["id"] ?>" class="page-btn small"><i class="fas fa-eye"></i> Προβολή</a>
      <a href="youtube_search.php?list_id=<?= $row["id"] ?>" class="page-btn small"><i class="fas fa-plus"></i> Προσθήκη βίντεο</a>
      <a href="edit_list.php?list_id=<?= $row["id"] ?>" class="page-btn small"><i class="fas fa-pen"></i> Επεξεργασία</a>
      <a href="delete_list.php?list_id=<?= $row["id"] ?>" class="page-btn small danger" onclick="return confirm('Σίγουρα διαγραφή λίστας;')"><i class="fas fa-trash "></i> Διαγραφή</a>
    </div>
  <?php endwhile; ?>

  <?php $total_pages = ceil($total_lists / $limit); ?>
  <div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <?php if ($i === $page): ?>
        <span class="page-btn active"><?= $i ?></span>
      <?php else: ?>
        <a class="page-btn" href="?<?= http_build_query(array_merge($_GET, ["page" => $i])) ?>"><?= $i ?></a>
      <?php endif; ?>
    <?php endfor; ?>
  </div>
<?php endif;

$stmt->close();
$conn->close();
?>

<p><a href="create_list.php" class="page-btn"><i class="fas fa-plus"></i> Δημιουργία νέας λίστας</a></p>

</main>

<?php include "footer.php"; ?>
