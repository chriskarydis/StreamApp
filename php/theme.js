// === Θέμα: Εναλλαγή Light / Dark ===
function toggleTheme() {
  const root = document.documentElement;
  const isDark = root.classList.contains("dark");
  const nextTheme = isDark ? "light" : "dark";

  root.classList.remove(isDark ? "dark" : "light");
  root.classList.add(nextTheme);
  document.cookie = "theme=" + nextTheme + "; path=/";

  updateIcon(nextTheme);
}

function applySavedTheme() {
  const cookie = document.cookie.split("; ").find(row => row.startsWith("theme="));
  const theme = cookie ? cookie.split("=")[1] : "light";
  document.documentElement.classList.add(theme);
  updateIcon(theme);
}

function updateIcon(theme) {
  const btn = document.getElementById("theme-toggle");
  if (!btn) return;

  btn.classList.add("animating");
  btn.title = theme === "dark" ? "Αλλαγή σε φωτεινό θέμα" : "Αλλαγή σε σκοτεινό θέμα";

  setTimeout(() => {
    btn.innerHTML = theme === "dark"
      ? '<i class="fas fa-sun"></i>'
      : '<i class="fas fa-moon"></i>';
    btn.classList.remove("animating");
  }, 200);
}


// === Accordion Toggle ===
function setupAccordion() {
  document.querySelectorAll(".accordion-toggle").forEach(toggle => {
    const content = toggle.nextElementSibling;

    content.classList.remove("open");
    content.style.maxHeight = "0";
    content.style.overflow = "hidden";

    toggle.addEventListener("click", () => {
      const isOpen = content.classList.toggle("open");
      if (isOpen) {
        content.style.maxHeight = content.scrollHeight + "px";
      } else {
        content.style.maxHeight = "0";
      }
    });
  });
}

// === Tabs ===
function showTab(name) {
  document.querySelectorAll(".tab").forEach(tab => {
    tab.classList.remove("visible");
    tab.style.display = "none";
  });

  const target = document.getElementById("tab-" + name);
  if (target) {
    target.style.display = "block";
    requestAnimationFrame(() => target.classList.add("visible"));
    target.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  document.querySelectorAll(".tab-menu button").forEach(b => b.classList.remove("active"));
  const activeBtn = document.querySelector(`.tab-menu button[onclick="showTab('${name}')"]`);
  if (activeBtn) activeBtn.classList.add("active");

  history.replaceState(null, '', '#tab-' + name);
}

function setupTabs() {
  const hash = location.hash.replace('#tab-', '') || 'following';
  showTab(hash);
}

// === Flash message (auto fade out)
function fadeOutFlash() {
  const msg = document.querySelector(".flash-message");
  if (!msg) return;
  setTimeout(() => {
    msg.style.opacity = "0";
    setTimeout(() => msg.remove(), 500);
  }, 4000);
}

// === Εκκίνηση ===
window.onload = () => {
  applySavedTheme();
  setupAccordion();
  setupTabs();
  fadeOutFlash();
};
