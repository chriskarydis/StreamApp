<?php
require_once "auth_check.php";

$list_id = $_GET["list_id"] ?? null;
$user_id = $_SESSION["user_id"];

if (!$list_id || !is_numeric($list_id)) {
    exit("Μη έγκυρο αίτημα.");
}

$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

$stmt = $conn->prepare("SELECT name, is_public FROM lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$stmt->bind_result($name, $is_public);
if (!$stmt->fetch()) {
    $stmt->close();
    $conn->close();
    exit("Δεν έχετε δικαίωμα επεξεργασίας.");
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_name = $_POST["name"];
    $new_public = $_POST["is_public"] == "1" ? 1 : 0;

    $stmt = $conn->prepare("UPDATE lists SET name = ?, is_public = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("siii", $new_name, $new_public, $list_id, $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $_SESSION["success_message"] = "Η λίστα ενημερώθηκε.";
    header("Location: my_lists.php");
    exit();
}

include "header.php";
?>

<main class="container">

<h2><i class="fas fa-pen"></i> Επεξεργασία λίστας</h2>

<form method="POST">
  <label>Όνομα λίστας:</label><br>
  <input type="text" name="name" required value="<?= htmlspecialchars($name) ?>"><br><br>

  <div class="visibility-options">
    <label class="option-public">
      <input type="radio" name="is_public" value="1" <?= $is_public ? 'checked' : '' ?>>
      <i class="fas fa-globe"></i> Δημόσια
    </label>

    <label class="option-private">
      <input type="radio" name="is_public" value="0" <?= !$is_public ? 'checked' : '' ?>>
      <i class="fas fa-lock"></i> Ιδιωτική
    </label>
  </div><br>

  <button type="submit" class="page-btn">	<i class="fas fa-download"></i> Αποθήκευση</button>
</form>

</main>

<?php include "footer.php"; ?>
