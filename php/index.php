<?php include "header.php"; ?>
<?php
if (!isset($conn)) {
    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
    if ($conn->connect_error) {
        die("Σφάλμα σύνδεσης με βάση: " . $conn->connect_error);
    }
}
?>

<main class="container">
  <h2>Καλώς ήρθατε στο Stream App <i class="fas fa-film"></i></h2>
  <p>Απολαύστε, δημιουργήστε και διαχειριστείτε τις λίστες περιεχομένου σας.</p>

  <?php if (isset($_SESSION["user_id"])): ?>
    <p>
      Είστε συνδεδεμένος ως
      <a href="profile.php" class="page-btn small">
        <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION["username"]) ?>
      </a>
    </p>

    <?php
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM lists WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    ?>

    <?php if ($count > 0): ?>
      <p><a href="my_lists.php" class="page-btn"><i class="fas fa-list"></i> Περιηγηθείτε στις λίστες σας</a></p>
    <?php else: ?>
      <p><a href="create_list.php" class="page-btn"><i class="fas fa-plus"></i> Ξεκινήστε δημιουργώντας μια λίστα!</a></p>
    <?php endif; ?>

  <?php else: ?>
    <p>
      <a href="login.php" class="page-btn small"><i class="fas fa-sign-in-alt"></i> Συνδεθείτε</a>
      ή
      <a href="register.php" class="page-btn small"><i class="fas fa-user-plus"></i> Εγγραφείτε</a>
      για να ξεκινήσετε.
    </p>
  <?php endif; ?>
</main>

<?php include "footer.php"; ?>
