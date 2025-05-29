<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $username  = trim($_POST['username']);
    $password  = $_POST['password'];
    $email     = trim($_POST['email']);

    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Το username ή το email χρησιμοποιούνται ήδη.";
    } else {
        if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}/', $password)) {
            $error = "Ο κωδικός δεν πληροί τα κριτήρια ασφάλειας.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password, email) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $username, $hash, $email);

            if ($stmt->execute()) {
                $_SESSION["success_message"] = "Η εγγραφή ολοκληρώθηκε με επιτυχία. Μπορείτε τώρα να συνδεθείτε.";
                header("Location: login.php");
                exit();
            } else {
                $error = "⚠️ Παρουσιάστηκε σφάλμα κατά την εγγραφή.";
            }
            $stmt->close();
        }
    }

    $check->close();
    $conn->close();
}

include "header.php";
?>

<main class="container">

<h2><i class="fas fa-user-plus"></i> Εγγραφή</h2>

<form method="POST" novalidate>
  <input type="text" name="firstname" placeholder="Όνομα" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" required>
  <input type="text" name="lastname" placeholder="Επώνυμο" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" required>
  <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>

  <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

  <input type="password" name="password" placeholder="Password" id="password" required
         pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
         title="Ο κωδικός πρέπει να έχει τουλάχιστον 8 χαρακτήρες, 1 κεφαλαίο, 1 μικρό, 1 αριθμό και 1 ειδικό σύμβολο.">

  <small id="pass-msg" style="display:block; margin-bottom:0.5rem; font-weight: bold; color: #666;">
    Ο κωδικός πρέπει να έχει 8 χαρακτήρες, κεφαλαίο, μικρό, αριθμό και σύμβολο.
  </small>

  <button type="submit" class="page-btn"><i class="fas fa-user-plus"></i> Εγγραφή</button>
</form>

<?php if (!empty($error)): ?>
  <p class="flash-message error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

</main>


<?php include "footer.php"; ?>
