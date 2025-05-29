<?php
require_once "auth_check.php";

$user_id = $_SESSION["user_id"];
$conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $email = $_POST["email"];

    $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $fname, $lname, $email, $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $_SESSION["success_message"] = "Το προφίλ σας ενημερώθηκε επιτυχώς.";
    header("Location: profile.php");
    exit();
}

$stmt = $conn->prepare("SELECT firstname, lastname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fname, $lname, $email);
$stmt->fetch();
$stmt->close();
$conn->close();

include "header.php";
?>

<main class="container">

<h2>Επεξεργασία Προφίλ</h2>

<form method="POST">
  <label>Όνομα:</label><br>
  <input type="text" name="firstname" value="<?= htmlspecialchars($fname) ?>" required><br><br>

  <label>Επώνυμο:</label><br>
  <input type="text" name="lastname" value="<?= htmlspecialchars($lname) ?>" required><br><br>

  <label>Email:</label><br>
  <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

  <button type="submit" class="page-btn">	<i class="fas fa-download"></i> Αποθήκευση</button>
</form>

</main>

<?php include "footer.php"; ?>
