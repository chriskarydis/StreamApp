<?php
session_start();

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_user"])) {
    $_SESSION["user_id"] = $_COOKIE["remember_user"];
    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->bind_result($uname);
    $stmt->fetch();
    $_SESSION["username"] = $uname ?? "user";
    header("Location: index.php");
    exit();
}

$success = $_SESSION["success_message"] ?? null;
unset($_SESSION["success_message"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;

            if (!empty($_POST["remember"])) {
                setcookie("remember_user", $user_id, time() + (86400 * 30), "/");
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Λάθος κωδικός.";
        }
    } else {
        $error = "Δεν βρέθηκε χρήστης.";
    }

    $stmt->close();
    $conn->close();
}

include "header.php";
?>

<main class="container">

<h2>Σύνδεση</h2>

<?php if (!empty($success)): ?>
  <p style="color: green; font-weight: bold;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST">
  <input type="username" name="username" required placeholder="Username"><br>
  <input type="password" name="password" required placeholder="Password"><br>
  <label class="remember-row">
    Να με θυμάσαι <i class="fas fa-brain"></i> 
    <input type="checkbox" name="remember">
  </label>
  <button type="submit" class="page-btn"><i class="fas fa-sign-in-alt"></i> Σύνδεση</button>
</form>

<?php if (!empty($error)): ?>
  <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

</main>

<?php include "footer.php"; ?>
