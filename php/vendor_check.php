<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

echo '<main class="container">';

echo "<h2>vendor/autoload.php loaded successfully</h2>";

try {
    $array = [
        'project' => 'Stream App',
        'version' => '1.0',
        'features' => ['YouTube API', 'User Lists', 'YAML Export']
    ];

    $yaml = Yaml::dump($array, 4, 2);
    echo "<pre>$yaml</pre>";
} catch (Exception $e) {
    echo "<p style='color:red;'> Yaml::dump() failed: " . $e->getMessage() . "</p>";
}

echo "</main>";