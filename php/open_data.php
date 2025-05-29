<?php
require_once "auth_check.php";
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

include "header.php";

$show = isset($_GET["show"]);
?>

<main class="container">

<h2><i class="fas fa-folder-open"></i> Open Data: Δημόσιες Λίστες</h2>

<p>Μπορείτε να επιλέξετε να δείτε ή να κατεβάσετε τις δημόσιες λίστες χρηστών σε μορφή YAML.</p>

<div style="margin-top: 1rem;">
  <a href="open_data.php?show=1" class="page-btn"><i class="fas fa-eye"></i> Προβολή YAML</a>
  <a href="export_yaml.php?download=1" class="page-btn"><i class="fas fa-download"></i> Κατέβασμα YAML</a>
</div>

<?php if ($show): ?>
  <hr>
  <h3><i class="fas fa-search"></i> Προβολή αρχείου YAML:</h3>
  <pre style="background:#f4f4f4; padding:1rem; border-radius:6px; overflow-x:auto;">

<?php
$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

$sql = "
  SELECT l.id AS list_id, l.name, u.id AS user_id, u.username, v.title, v.youtube_id, v.created_at
  FROM lists l
  JOIN users u ON l.user_id = u.id
  LEFT JOIN videos v ON l.id = v.list_id
  WHERE l.is_public = 1
  ORDER BY l.id, v.created_at DESC
";

$result = $conn->query($sql);
$lists = [];

while ($row = $result->fetch_assoc()) {
    $hash = hash("sha256", $row["user_id"] . $row["username"]);
    $lid = $row["list_id"];

    if (!isset($lists[$lid])) {
        $lists[$lid] = [
            "id" => $lid,
            "name" => $row["name"],
            "owner" => $hash,
            "videos" => []
        ];
    }

    if ($row["youtube_id"]) {
        $lists[$lid]["videos"][] = [
            "title" => $row["title"],
            "youtube_id" => $row["youtube_id"],
            "created_at" => $row["created_at"]
        ];
    }
}

echo htmlspecialchars(Yaml::dump(array_values($lists), 4, 2));
?>
  </pre>
<?php endif; ?>

</main>

<?php include "footer.php"; ?>
