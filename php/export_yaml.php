<?php
require_once "auth_check.php";
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

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

$yaml = Yaml::dump(array_values($lists), 4, 2);

if (isset($_GET["download"])) {
    header("Content-Type: application/x-yaml");
    header("Content-Disposition: attachment; filename=open_data.yaml");
    echo $yaml;
    exit();
}

header("Content-Type: text/yaml");
echo $yaml;
