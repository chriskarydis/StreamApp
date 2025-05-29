<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

$title = $_GET["title"] ?? "";
$from = $_GET["from"] ?? "";
$to = $_GET["to"] ?? "";
$user = $_GET["user"] ?? "";
$page = max(1, (int)($_GET["page"] ?? 1));
$limit = (int)($_GET["per_page"] ?? 10);
$offset = ($page - 1) * $limit;

$filter_sql = " FROM lists l
  JOIN users u ON l.user_id = u.id
  LEFT JOIN videos v ON l.id = v.list_id
  WHERE l.is_public = 1";

$params = [];
$types = "";

if ($title !== "") {
  $filter_sql .= " AND (l.name LIKE ? OR v.title LIKE ?)";
  $params[] = "%$title%";
  $params[] = "%$title%";
  $types .= "ss";
}
if ($from !== "") {
  $filter_sql .= " AND v.created_at >= ?";
  $params[] = $from;
  $types .= "s";
}
if ($to !== "") {
  $filter_sql .= " AND v.created_at <= ?";
  $params[] = $to;
  $types .= "s";
}
if ($user !== "") {
  $filter_sql .= " AND (u.username LIKE ? OR u.email LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?)";
  for ($i = 0; $i < 4; $i++) {
    $params[] = "%$user%";
    $types .= "s";
  }
}

$count_sql = "SELECT COUNT(DISTINCT l.id) AS total" . $filter_sql;
$count_stmt = $conn->prepare($count_sql);
if ($types) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_assoc();
$total_found = $count_result["total"] ?? 0;
$count_stmt->close();

$sql = "SELECT l.id, l.name, l.user_id, u.username, COUNT(v.id) as video_count" . $filter_sql .
       " GROUP BY l.id ORDER BY l.created_at DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

include "header.php";
?>

<main class="container">

<h2><i class="fas fa-search"></i> Αναζήτηση Δημοσίων Λιστών</h2>

<form method="GET" class="search-form">
  <div class="search-bar">
    <label>Τίτλος<br><input type="text" name="title" value="<?= htmlspecialchars($title) ?>"></label>
    <label>Χρήστης<br><input type="text" name="user" value="<?= htmlspecialchars($user) ?>"></label>
    <label>Από<br><input type="date" name="from" value="<?= htmlspecialchars($from) ?>"></label>
    <label>Έως<br><input type="date" name="to" value="<?= htmlspecialchars($to) ?>"></label>
    <label>Ανά<br>
      <select name="per_page" onchange="this.form.submit()">
        <option value="5" <?= $limit === 5 ? 'selected' : '' ?>>5</option>
        <option value="10" <?= $limit === 10 ? 'selected' : '' ?>>10</option>
        <option value="25" <?= $limit === 25 ? 'selected' : '' ?>>25</option>
      </select>
    </label>
    <div class="search-actions-row">
      <input type="hidden" name="page" value="1">
      <button type="submit" class="page-btn small"><i class="fas fa-search"></i>Αναζήτηση</button>
      <button type="button" class="page-btn small" onclick="window.location='search.php'"><i class="fas fa-times-circle"></i> Καθαρισμός</button>
    </div>
  </div>
</form>

<hr>
<p class="total-count"><i class="fas fa-bullseye"></i> Βρέθηκαν συνολικά <?= $total_found ?> λίστες</p>


<?php
$found = false;
while ($row = $result->fetch_assoc()):
  $found = true;
?>
  <div class="card">
    <p>
      <strong><?= htmlspecialchars($row["name"]) ?></strong>
      από <?= htmlspecialchars($row["username"]) ?>,
      <?= $row["video_count"] ?> video(s)
    </p>
    <a href="view_list.php?list_id=<?= $row["id"] ?>" class="page-btn small"><i class="fas fa-eye"></i>Προβολή</a>
  </div>
<?php endwhile; ?>

<?php if (!$found): ?>
  <div class="card">
    <p><i class="fas fa-exclamation-triangle"></i> Δεν βρέθηκαν λίστες που να ταιριάζουν με τα κριτήρια αναζήτησης.</p>
  </div>
<?php endif; ?>

<?php
$total_pages = ceil($total_found / $limit);
?>
<div style="margin-top: 1rem;">
  <?php if ($total_pages > 1): ?>
    <div style="display:inline-block; margin-right:10px;">Σελίδες:</div>
    <?php if ($page > 1): ?>
      <a class="page-btn" href="?<?= http_build_query(array_merge($_GET, ["page" => $page - 1])) ?>">	<i class="fas fa-arrow-left"></i> Προηγούμενη</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <?php if ($i == $page): ?>
        <span class="page-btn active"><?= $i ?></span>
      <?php else: ?>
        <a class="page-btn" href="?<?= http_build_query(array_merge($_GET, ["page" => $i])) ?>"><?= $i ?></a>
      <?php endif; ?>
    <?php endfor; ?>
    <?php if ($page < $total_pages): ?>
      <a class="page-btn" href="?<?= http_build_query(array_merge($_GET, ["page" => $page + 1])) ?>">Επόμενη <i class="fas fa-arrow-right"></i></a>
    <?php endif; ?>
  <?php endif; ?>
</div>

</main>

<?php include "footer.php"; ?>
