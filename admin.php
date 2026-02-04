<?php
session_start();

// Jednoduché heslo pro přístup - ZMĚŇTE NA SVÉ VLASTNÍ!
define('ADMIN_PASSWORD', 'ok2zi2026');

$page_title = 'Administrace';
$message = '';
$message_type = '';

// Cesta k souborům s daty
$articles_file = __DIR__ . '/data/articles.json';

// Kontrola přihlášení
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Zpracování odhlášení
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Zpracování přihlášení
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $is_logged_in = true;
    } else {
        $message = 'Nesprávné heslo!';
        $message_type = 'error';
    }
}

// Načtení článků
$articles = file_exists($articles_file) ? json_decode(file_get_contents($articles_file), true) : [];

// Zpracování smazání článku
if ($is_logged_in && isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $articles = array_filter($articles, fn($a) => $a['id'] !== $delete_id);
    $articles = array_values($articles); // Reindex
    file_put_contents($articles_file, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $message = 'Článek byl smazán.';
    $message_type = 'success';
}

// Zpracování editace - načtení článku
$edit_article = null;
if ($is_logged_in && isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    foreach ($articles as $a) {
        if ($a['id'] === $edit_id) {
            $edit_article = $a;
            break;
        }
    }
}

// Zpracování formuláře pro přidání/editaci článku
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulek'])) {
    $titulek = trim($_POST['titulek']);
    $kategorie = trim($_POST['kategorie']);
    $obsah = trim($_POST['obsah']);
    $obrazek = trim($_POST['obrazek']);
    $edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;
    
    if (empty($titulek) || empty($kategorie) || empty($obsah)) {
        $message = 'Vyplňte prosím všechna povinná pole!';
        $message_type = 'error';
    } else {
        if ($edit_id > 0) {
            // Editace existujícího článku
            foreach ($articles as &$a) {
                if ($a['id'] === $edit_id) {
                    $a['titulek'] = $titulek;
                    $a['kategorie'] = $kategorie;
                    $a['obsah'] = $obsah;
                    $a['obrazek'] = $obrazek;
                    break;
                }
            }
            unset($a);
            $message = 'Článek byl upraven!';
        } else {
            // Nový článek
            $max_id = 0;
            foreach ($articles as $a) {
                if ($a['id'] > $max_id) $max_id = $a['id'];
            }
            
            $new_article = [
                'id' => $max_id + 1,
                'titulek' => $titulek,
                'kategorie' => $kategorie,
                'datum' => date('Y-m-d'),
                'obsah' => $obsah,
                'obrazek' => $obrazek
            ];
            
            $articles[] = $new_article;
            $message = 'Článek byl přidán!';
        }
        
        $message_type = 'success';
        file_put_contents($articles_file, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // Reset editace
        $edit_article = null;
    }
}

// Seřazení článků
usort($articles, function($a, $b) {
    return strtotime($b['datum']) - strtotime($a['datum']);
});

include 'includes/header.php';
?>

<?php if (!$is_logged_in): ?>
<!-- Přihlašovací formulář -->
<div class="admin-container">
    <div class="admin-form login-form">
        <h2>Přihlášení do administrace</h2>
        
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="password">Heslo</label>
                <input type="password" id="password" name="password" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Přihlásit se</button>
        </form>
    </div>
</div>

<?php else: ?>
<!-- Administrační rozhraní -->
<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 1.5rem;">Administrace</h1>
        <a href="admin.php?logout=1" class="btn btn-secondary">Odhlásit se</a>
    </div>
    
    <?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>
    
    <div class="admin-form">
        <h2><?php echo $edit_article ? 'Upravit článek' : 'Přidat nový článek'; ?></h2>
        
        <form method="POST">
            <?php if ($edit_article): ?>
            <input type="hidden" name="edit_id" value="<?php echo $edit_article['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="titulek">Titulek *</label>
                <input type="text" id="titulek" name="titulek" required
                       value="<?php echo $edit_article ? htmlspecialchars($edit_article['titulek']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="kategorie">Kategorie *</label>
                <select id="kategorie" name="kategorie" required>
                    <option value="">Vyberte kategorii...</option>
                    <option value="technika" <?php echo ($edit_article && $edit_article['kategorie'] === 'technika') ? 'selected' : ''; ?>>
                        Technika
                    </option>
                    <option value="expedice" <?php echo ($edit_article && $edit_article['kategorie'] === 'expedice') ? 'selected' : ''; ?>>
                        Expedice
                    </option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="obrazek">URL obrázku</label>
                <input type="url" id="obrazek" name="obrazek" placeholder="https://..."
                       value="<?php echo $edit_article ? htmlspecialchars($edit_article['obrazek']) : ''; ?>">
                <small>Volitelné. Zadejte celou URL adresu obrázku.</small>
            </div>
            
            <div class="form-group">
                <label for="obsah">Obsah článku *</label>
                <textarea id="obsah" name="obsah" required placeholder="Pište text článku..."><?php 
                    echo $edit_article ? htmlspecialchars($edit_article['obsah']) : ''; 
                ?></textarea>
                <small>
                    Můžete použít jednoduché formátování:<br>
                    <code>## Nadpis</code> pro nadpis,
                    <code>- položka</code> pro odrážky,
                    <code>`kód`</code> pro inline kód
                </small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <?php echo $edit_article ? 'Uložit změny' : 'Přidat článek'; ?>
                </button>
                <?php if ($edit_article): ?>
                <a href="admin.php" class="btn btn-secondary">Zrušit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <div class="article-list">
        <h3>Existující články (<?php echo count($articles); ?>)</h3>
        
        <?php if (empty($articles)): ?>
        <div class="empty-state">
            <p>Zatím nejsou žádné články.</p>
        </div>
        <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <div class="article-list-item">
            <div class="article-list-info">
                <h4>
                    <a href="clanek.php?id=<?php echo $article['id']; ?>" target="_blank">
                        <?php echo htmlspecialchars($article['titulek']); ?>
                    </a>
                </h4>
                <span>
                    <?php echo htmlspecialchars($article['kategorie']); ?> · 
                    <?php echo date('j. n. Y', strtotime($article['datum'])); ?>
                </span>
            </div>
            <div class="article-list-actions">
                <a href="admin.php?edit=<?php echo $article['id']; ?>" class="btn btn-sm btn-secondary">Upravit</a>
                <a href="admin.php?delete=<?php echo $article['id']; ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Opravdu chcete smazat tento článek?');">
                    Smazat
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
