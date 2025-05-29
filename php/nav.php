<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current = basename($_SERVER["PHP_SELF"]);
?>
<nav>
  <div class="nav-links">
    <a href="index.php" class="nav-logo <?= $current === 'index.php' ? 'active' : '' ?>">
      <i class="fas fa-film"></i> <span>Stream App</span>
    </a>
    <a href="help.php" class="<?= $current === 'help.php' ? 'active' : '' ?>">
      <i class="fas fa-question-circle"></i> Βοήθεια
    </a>
    <a href="info.php" class="<?= $current === 'info.php' ? 'active' : '' ?>">
      <i class="fas fa-info-circle"></i> Πληροφορίες
    </a>
    <a href="search.php" class="<?= $current === 'search.php' ? 'active' : '' ?>">
      <i class="fas fa-search"></i> Αναζήτηση
    </a>

    <?php if (isset($_SESSION["user_id"])): ?>
      <a href="my_lists.php" class="<?= $current === 'my_lists.php' ? 'active' : '' ?>">
        <i class="fas fa-folder"></i> Οι λίστες μου
      </a>
      <a href="public_lists.php" class="<?= $current === 'public_lists.php' ? 'active' : '' ?>">
        <i class="fas fa-folder-open"></i> Δημόσιες λίστες
      </a>
      <a href="youtube_search.php" class="<?= $current === 'youtube_search.php' ? 'active' : '' ?>">
        <i class="fab fa-youtube"></i> YouTube
      </a>
      <a href="open_data.php" class="<?= $current === 'open_data.php' ? 'active' : '' ?>">
        <i class="fas fa-database"></i> Open Data
      </a>
    <?php endif; ?>
  </div>

  <div class="nav-user">
    <?php if (isset($_SESSION["user_id"])): ?>
      <a href="profile.php" class="<?= $current === 'profile.php' ? 'active' : '' ?>" >
        <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION["username"]) ?>
      </a>
      <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Αποσύνδεση
      </a>
    <?php else: ?>
      <a href="register.php" class="<?= $current === 'register.php' ? 'active' : '' ?>">
        <i class="fas fa-user-plus"></i> Εγγραφή
      </a>
      <a href="login.php" class="<?= $current === 'login.php' ? 'active' : '' ?>">
        <i class="fas fa-sign-in-alt"></i> Σύνδεση
      </a>
    <?php endif; ?>
    <button id="theme-toggle" onclick="toggleTheme()" title="Εναλλαγή θέματος">
      <i class="fas fa-moon"></i>
    </button>
  </div>
</nav>
