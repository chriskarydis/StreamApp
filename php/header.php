<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta charset="UTF-8">
  <script>
    (function () {
      const theme = document.cookie.split('; ').find(row => row.startsWith('theme='))?.split('=')[1];
      document.documentElement.classList.add(theme || 'light');
    })();
  </script>
  <title>Stream App</title>
  <link rel="stylesheet" href="style.css">
  <script src="theme.js" defer></script>
</head>
<body>
<?php include "nav.php"; ?>