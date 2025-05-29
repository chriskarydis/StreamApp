<?php
require_once "auth_check.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $is_public = ($_POST["is_public"] ?? "1") === "1" ? 1 : 0;
    $user_id = $_SESSION["user_id"];

    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
    $stmt = $conn->prepare("INSERT INTO lists (user_id, name, is_public) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $name, $is_public);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $_SESSION["success_message"] = "Η λίστα δημιουργήθηκε με επιτυχία.";
    header("Location: profile.php");
    exit();
}

include "header.php";
?>

<main class="container">

<h2>Δημιουργία Νέας Λίστας</h2>

<form method="POST">
  <label>Όνομα λίστας:</label><br>
  <input type="text" name="name" required><br><br>

  <div class="visibility-options">
    <label class="option-public">
      <input type="radio" name="is_public" value="1" checked>
      <i class="fas fa-globe"></i> Δημόσια
    </label>

    <label class="option-private">
      <input type="radio" name="is_public" value="0">
      <i class="fas fa-lock"></i> Ιδιωτική
    </label>
  </div><br>

  <button type="submit" class="page-btn"><i class="fas fa-plus"></i> Δημιουργία</button>
  <a href="my_lists.php" class="page-btn small danger"><i class="fas fa-times"></i> Ακύρωση</a>
</form>

</main>

<?php include "footer.php"; ?>
