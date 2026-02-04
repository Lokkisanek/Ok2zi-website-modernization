<?php
// Společný header pro všechny stránky
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | OK2ZI' : 'OK2ZI | Radioamatérský portál & Workshop'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script>
        // Načtení uloženého tématu před renderem
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>
<body>

<nav class="nav">
    <div class="container nav-inner">
        <a class="nav-logo" href="index.php">OK2ZI</a>
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="Přepnout téma">
                <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>
            <button class="nav-toggle" aria-label="Menu" onclick="toggleMenu()">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <ul class="nav-links" id="nav-links">
            <li><a href="index.php" <?php echo $current_page == 'index.php' ? 'class="active"' : ''; ?>>Domů</a></li>
            <li><a href="produkty.php" <?php echo $current_page == 'produkty.php' ? 'class="active"' : ''; ?>>Plošné spoje</a></li>
            <li><a href="clanky.php?kategorie=expedice" <?php echo (isset($_GET['kategorie']) && $_GET['kategorie'] == 'expedice') ? 'class="active"' : ''; ?>>Expedice</a></li>
            <li><a href="clanky.php?kategorie=technika" <?php echo (isset($_GET['kategorie']) && $_GET['kategorie'] == 'technika') ? 'class="active"' : ''; ?>>Technika</a></li>
            <li><a href="index.php#kontakt" class="nav-cta">Kontakt</a></li>
        </ul>
    </div>
</nav>
