<?php
require_once "auth_check.php";

$user_id = $_SESSION["user_id"];
$success = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST["current_password"] ?? "";
    $new = $_POST["new_password"] ?? "";

    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $hashed)) {
        $error = "Λάθος τρέχων κωδικός.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new)) {
        $error = "Ο νέος κωδικός δεν πληροί τα κριτήρια ασφάλειας.";
    } else {
        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hashed, $user_id);
        if ($stmt->execute()) {
            $success = "Ο κωδικός αλλάχθηκε επιτυχώς.";
        } else {
            $error = "Σφάλμα κατά την αλλαγή.";
        }
        $stmt->close();
    }

    $conn->close();
}

include "header.php";
?>

<main class="container">
  <h2><i class="fas fa-key"></i> Αλλαγή Κωδικού</h2>

  <?php if ($success): ?>
    <div class="flash-message success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="flash-message error"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="change_password.php">
    <input type="password" name="current_password" placeholder="Τρέχων κωδικός" required>
    <input type="password" name="new_password" placeholder="Νέος κωδικός" required
      pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
      title="8+ χαρακτήρες, 1 κεφαλαίο, 1 μικρό, 1 αριθμός, 1 σύμβολο.">
    <small>Πρέπει να έχει τουλάχιστον 8 χαρακτήρες, 1 κεφαλαίο, 1 μικρό, 1 αριθμό, 1 σύμβολο.</small>
    <br>
    <button type="submit" class="page-btn"><i class="fas fa-sync-alt"></i> Αλλαγή</button>
    <a href="profile.php" class="page-btn small danger"><i class="fas fa-times"></i> Ακύρωση</a>
  </form>
</main>

<?php include "footer.php"; ?>
