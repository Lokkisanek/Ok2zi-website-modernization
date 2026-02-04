<?php
// Načtení článků
$articles_file = __DIR__ . '/data/articles.json';
$articles = file_exists($articles_file) ? json_decode(file_get_contents($articles_file), true) : [];

// Získání ID článku z URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vyhledání článku
$article = null;
foreach ($articles as $a) {
    if ($a['id'] === $id) {
        $article = $a;
        break;
    }
}

// Pokud článek neexistuje, přesměrování
if (!$article) {
    header('Location: index.php');
    exit;
}

$page_title = $article['titulek'];
$datum = new DateTime($article['datum']);

// Funkce pro jednoduché formátování Markdown
function simple_markdown($text) {
    // Nadpisy
    $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
    
    // Kód
    $text = preg_replace('/```(\w+)?\n([\s\S]*?)```/m', '<pre><code>$2</code></pre>', $text);
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
    
    // Seznamy
    $text = preg_replace('/^- (.+)$/m', '<li>$1</li>', $text);
    $text = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $text);
    $text = preg_replace('/<\/ul>\s*<ul>/', '', $text);
    
    // Odstavce
    $text = preg_replace('/\n\n+/', '</p><p>', $text);
    $text = '<p>' . $text . '</p>';
    
    // Čištění prázdných tagů
    $text = preg_replace('/<p>\s*<\/p>/', '', $text);
    $text = preg_replace('/<p>\s*(<h[23]>)/', '$1', $text);
    $text = preg_replace('/(<\/h[23]>)\s*<\/p>/', '$1', $text);
    $text = preg_replace('/<p>\s*(<ul>)/', '$1', $text);
    $text = preg_replace('/(<\/ul>)\s*<\/p>/', '$1', $text);
    $text = preg_replace('/<p>\s*(<pre>)/', '$1', $text);
    $text = preg_replace('/(<\/pre>)\s*<\/p>/', '$1', $text);
    
    return $text;
}

include 'includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Domů</a>
            <span>›</span>
            <a href="clanky.php?kategorie=<?php echo htmlspecialchars($article['kategorie']); ?>">
                <?php echo $article['kategorie'] === 'expedice' ? 'Expedice' : 'Technika'; ?>
            </a>
            <span>›</span>
            <span><?php echo htmlspecialchars($article['titulek']); ?></span>
        </nav>
    </div>
</div>

<article class="article-detail">
    <div class="container">
        <div class="article-content">
            <h1><?php echo htmlspecialchars($article['titulek']); ?></h1>
            
            <div class="article-meta">
                <span class="category"><?php echo htmlspecialchars($article['kategorie']); ?></span>
                <span><?php echo $datum->format('j. F Y'); ?></span>
            </div>
            
            <?php if (!empty($article['obrazek'])): ?>
            <img src="<?php echo htmlspecialchars($article['obrazek']); ?>" 
                 alt="<?php echo htmlspecialchars($article['titulek']); ?>" 
                 class="article-image">
            <?php endif; ?>
            
            <div class="article-body">
                <?php echo simple_markdown(htmlspecialchars_decode(htmlspecialchars($article['obsah']))); ?>
            </div>
            
            <div style="margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--border);">
                <a href="clanky.php?kategorie=<?php echo htmlspecialchars($article['kategorie']); ?>" class="btn btn-secondary">
                    ← Zpět na přehled
                </a>
            </div>
        </div>
    </div>
</article>

<?php include 'includes/footer.php'; ?>
