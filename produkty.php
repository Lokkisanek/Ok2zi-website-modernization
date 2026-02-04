<?php
$page_title = 'Plošné spoje & Komponenty';

// Načtení produktů
$products_file = __DIR__ . '/data/products.json';
$products = file_exists($products_file) ? json_decode(file_get_contents($products_file), true) : [];

// Získání unikátních kategorií
$categories = array_unique(array_column($products, 'kategorie'));
sort($categories);

// Filtrování podle kategorie
$filter = isset($_GET['kategorie']) ? $_GET['kategorie'] : 'all';

if ($filter !== 'all') {
    $filtered_products = array_filter($products, fn($p) => $p['kategorie'] === $filter);
} else {
    $filtered_products = $products;
}

include 'includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Domů</a>
            <span>›</span>
            <span>Plošné spoje</span>
        </nav>
        <h1>Plošné spoje & Komponenty</h1>
        <p>Vlastní návrhy plošných spojů a elektronických komponent pro radioamatéry.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="filter-tabs">
            <a href="produkty.php" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">Vše</a>
            <?php foreach ($categories as $cat): ?>
            <a href="produkty.php?kategorie=<?php echo urlencode($cat); ?>" 
               class="filter-tab <?php echo $filter === $cat ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat); ?>
            </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($filtered_products)): ?>
        <div class="empty-state">
            <h3>Žádné produkty</h3>
            <p>V této kategorii zatím nejsou žádné produkty.</p>
        </div>
        <?php else: ?>
        <div class="shop-grid">
            <?php foreach ($filtered_products as $p): ?>
            <article class="product-card" id="produkt-<?php echo $p['id']; ?>">
                <img src="<?php echo htmlspecialchars($p['obrazek']); ?>" 
                     alt="<?php echo htmlspecialchars($p['nazev']); ?>" 
                     class="product-image">
                <span class="product-tag"><?php echo htmlspecialchars($p['kategorie']); ?></span>
                <h3 class="product-name"><?php echo htmlspecialchars($p['nazev']); ?></h3>
                <p class="product-desc"><?php echo htmlspecialchars($p['popis']); ?></p>
                
                <?php if (!empty($p['specifikace'])): ?>
                <div class="product-specs">
                    <?php foreach ($p['specifikace'] as $key => $value): ?>
                    <?php echo htmlspecialchars(ucfirst($key)); ?>: <?php echo htmlspecialchars($value); ?><br>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="product-footer">
                    <div>
                        <span class="product-price"><?php echo number_format($p['cena'], 0, ',', ' '); ?> Kč</span>
                        <span class="product-stock <?php echo $p['skladem'] ? 'in-stock' : 'out-of-stock'; ?>">
                            <?php echo $p['skladem'] ? 'Skladem' : 'Není skladem'; ?>
                        </span>
                    </div>
                    <a href="mailto:ok2zi@email.cz?subject=Objednávka: <?php echo urlencode($p['nazev']); ?>" 
                       class="btn btn-primary <?php echo !$p['skladem'] ? 'disabled' : ''; ?>">
                        Objednat
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div style="margin-top: 48px; padding: 24px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px;">
            <h3 style="font-size: 1rem; margin-bottom: 12px;">Jak objednat?</h3>
            <p style="color: var(--text-muted); font-size: 0.9375rem; line-height: 1.6;">
                Pro objednávku klikněte na tlačítko "Objednat" u vybraného produktu, nebo napište přímo na 
                <a href="mailto:ok2zi@email.cz" style="color: var(--primary);">ok2zi@email.cz</a>. 
                Platba převodem, osobní odběr možný v Brně.
            </p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
