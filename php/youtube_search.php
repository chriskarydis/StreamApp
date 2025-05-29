<?php
require_once "auth_check.php";

$user_id = $_SESSION["user_id"];
$token = $_SESSION["youtube_token"] ?? null;
$query = $_GET["q"] ?? "";
$list_id = $_GET["list_id"] ?? null;

if (!$token) {
    include "header.php";
    echo '<main class="container">';
    echo '<p><a href="youtube_auth.php" class="page-btn"><i class="fas fa-link"></i> Σύνδεση με YouTube</a></p>';
    echo '</main>';
    include "footer.php";
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["title"], $_POST["video_id"], $_POST["list_id"])) {
    $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");

    $stmt = $conn->prepare("SELECT id FROM lists WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $_POST["list_id"], $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt = $conn->prepare("INSERT INTO videos (list_id, title, youtube_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_POST["list_id"], $_POST["title"], $_POST["video_id"]);
        $stmt->execute();
        $_SESSION["success_message"] = "Το βίντεο προστέθηκε στη λίστα!";
    }

    $stmt->close();
    $conn->close();

    $qs = http_build_query([
        "q" => $query,
        "list_id" => $_POST["list_id"]
    ]);
    header("Location: youtube_search.php?$qs");
    exit();
}

include "header.php";

if (!empty($_SESSION["success_message"])) {
    echo "<div class='flash-message success'>" . htmlspecialchars($_SESSION["success_message"]) . "</div>";
    unset($_SESSION["success_message"]);
}
?>

<main class="container">

<h2><i class="fas fa-search"></i> Αναζήτηση στο YouTube</h2>

<form method="GET" class="search-form" style="margin-bottom: 1rem;">
  <?php if ($list_id): ?>
    <input type="hidden" name="list_id" value="<?= $list_id ?>">
  <?php endif; ?>
  <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Αναζήτηση βίντεο..." required>
  <button type="submit" class="page-btn"><i class="fas fa-search"></i> Αναζήτηση</button>
</form>

</main>

<?php

echo '<main class="container">';

if ($query) {
    $search_url = "https://www.googleapis.com/youtube/v3/search?" . http_build_query([
        "part" => "snippet",
        "q" => $query,
        "type" => "video",
        "maxResults" => 8
    ]);

    $opts = ["http" => ["header" => "Authorization: Bearer $token"]];
    $context = stream_context_create($opts);
    $response = @file_get_contents($search_url, false, $context);
    $data = json_decode($response, true);

    if ($response === false || isset($data["error"])) {
        unset($_SESSION["youtube_token"]);

        echo "<div class='flash-message error'>";
        echo "<i class='fas fa-times-circle'></i>  Η σύνδεση με το YouTube απέτυχε ή έληξε.<br>";
        echo "Θα μεταφερθείτε για επανεξουσιοδότηση...";
        echo "<br><a href='youtube_auth.php' class='page-btn'>👉 Σύνδεση τώρα</a>";
        echo "</div>";
        echo "<script>setTimeout(() => window.location.href = 'youtube_auth.php', 3000);</script>";
        include "footer.php";
        exit();
    }

    if (!empty($data["items"])) {
        echo "<hr><h3>Αποτελέσματα:</h3><ul>";

        foreach ($data["items"] as $item) {
            $videoId = $item["id"]["videoId"];
            $title = htmlspecialchars($item["snippet"]["title"]);

            echo "<div class='youtube-result'>";
            echo "<h3>$title</h3>";
            echo "<iframe src='https://www.youtube.com/embed/$videoId' frameborder='0' allowfullscreen></iframe>";

            echo "<form method='POST'>";
            echo "<input type='hidden' name='video_id' value='$videoId'>";
            echo "<input type='hidden' name='title' value=\"" . htmlspecialchars($title, ENT_QUOTES) . "\">";

            if ($list_id) {
                echo "<input type='hidden' name='list_id' value='$list_id'>";
            } else {
                echo "<label>Σε ποια λίστα: ";
                echo "<select name='list_id' required>";
                $conn = new mysqli("db", "webuser", "webpass", "di_internet_technologies_project");
                $lists = $conn->query("SELECT id, name FROM lists WHERE user_id = $user_id ORDER BY created_at DESC");
                while ($list = $lists->fetch_assoc()) {
                    $lname = htmlspecialchars($list["name"]);
                    echo "<option value='{$list["id"]}'>$lname</option>";
                }
                $conn->close();
                echo "</select></label><br>";
            }

            echo "<button type='submit' class='page-btn'><i class='fas fa-plus'></i> Προσθήκη</button>";
            echo "</form>";
            echo "</div>";
        }

        echo "</ul>";
    } else {
        echo "<p><i class='fas fa-times-circle'></i>  Δεν βρέθηκαν αποτελέσματα.</p>";
    }
}

echo"</main>";

include "footer.php";
