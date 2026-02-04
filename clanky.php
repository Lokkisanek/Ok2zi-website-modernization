<?php
// Načtení článků
$articles_file = __DIR__ . '/data/articles.json';
$articles = file_exists($articles_file) ? json_decode(file_get_contents($articles_file), true) : [];

// Filtrování podle kategorie
$kategorie = isset($_GET['kategorie']) ? $_GET['kategorie'] : 'all';
$valid_categories = ['all', 'expedice', 'technika'];

if (!in_array($kategorie, $valid_categories)) {
    $kategorie = 'all';
}

// Seřazení článků podle data
usort($articles, function($a, $b) {
    return strtotime($b['datum']) - strtotime($a['datum']);
});

// Filtrování
if ($kategorie !== 'all') {
    $filtered_articles = array_filter($articles, fn($a) => $a['kategorie'] === $kategorie);
} else {
    $filtered_articles = $articles;
}

// Názvy stránek
$page_titles = [
    'all' => 'Všechny články',
    'expedice' => 'DX Expedice',
    'technika' => 'Technické články'
];

$page_title = $page_titles[$kategorie];

include 'includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Domů</a>
            <span>›</span>
            <span><?php echo $page_title; ?></span>
        </nav>
        <h1><?php echo $page_title; ?></h1>
        <p>
            <?php if ($kategorie === 'expedice'): ?>
                Reporty a plány z DX expedic po celém světě.
            <?php elseif ($kategorie === 'technika'): ?>
                Technické články o konstrukcích, anténách a software.
            <?php else: ?>
                Všechny články a příspěvky z portálu OK2ZI.
            <?php endif; ?>
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="filter-tabs">
            <a href="clanky.php" class="filter-tab <?php echo $kategorie === 'all' ? 'active' : ''; ?>">Vše</a>
            <a href="clanky.php?kategorie=expedice" class="filter-tab <?php echo $kategorie === 'expedice' ? 'active' : ''; ?>">Expedice</a>
            <a href="clanky.php?kategorie=technika" class="filter-tab <?php echo $kategorie === 'technika' ? 'active' : ''; ?>">Technika</a>
        </div>
        
        <?php if (empty($filtered_articles)): ?>
        <div class="empty-state">
            <h3>Žádné články</h3>
            <p>V této kategorii zatím nejsou žádné články.</p>
        </div>
        <?php else: ?>
        <div class="blog-grid">
            <?php foreach ($filtered_articles as $article): 
                $datum = new DateTime($article['datum']);
            ?>
            <article class="blog-card">
                <div class="blog-img">
                    <?php if (!empty($article['obrazek'])): ?>
                    <img src="<?php echo htmlspecialchars($article['obrazek']); ?>" alt="<?php echo htmlspecialchars($article['titulek']); ?>">
                    <?php else: ?>
                    800 × 450
                    <?php endif; ?>
                </div>
                <div class="blog-body">
                    <div class="blog-meta">
                        <span class="blog-category"><?php echo htmlspecialchars($article['kategorie']); ?></span>
                        <span class="blog-date"><?php echo $datum->format('j. F Y'); ?></span>
                    </div>
                    <h3 class="blog-title">
                        <a href="clanek.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['titulek']); ?></a>
                    </h3>
                    <p class="blog-excerpt"><?php echo htmlspecialchars(mb_substr(strip_tags($article['obsah']), 0, 150)); ?>...</p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
