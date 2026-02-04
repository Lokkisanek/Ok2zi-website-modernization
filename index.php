<?php
$page_title = 'Domů';

// Načtení článků
$articles_file = __DIR__ . '/data/articles.json';
$articles = file_exists($articles_file) ? json_decode(file_get_contents($articles_file), true) : [];

// Načtení produktů
$products_file = __DIR__ . '/data/products.json';
$products = file_exists($products_file) ? json_decode(file_get_contents($products_file), true) : [];

// Seřazení článků podle data (nejnovější první)
usort($articles, function($a, $b) {
    return strtotime($b['datum']) - strtotime($a['datum']);
});

// Rozdělení článků podle kategorie
$expedice = array_filter($articles, fn($a) => $a['kategorie'] === 'expedice');
$technika = array_filter($articles, fn($a) => $a['kategorie'] === 'technika');

include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="container hero-grid">
        <div class="hero-content">
            <p class="hero-eyebrow">OK2ZI · JN89GE</p>
            <h1>Radioamatérský portál & Workshop</h1>
            <p class="hero-desc">Vývoj VF techniky, vlastní plošné spoje, DX expedice a technické články pro radioamatéry.</p>
            <div class="hero-stats">
                <div class="stat">
                    <span class="stat-value">335</span>
                    <span class="stat-label">DXCC Confirmed</span>
                </div>
                <div class="stat">
                    <span class="stat-value"><?php echo count($products); ?></span>
                    <span class="stat-label">PCB Designů</span>
                </div>
                <div class="stat">
                    <span class="stat-value"><?php echo count($expedice); ?></span>
                    <span class="stat-label">DX Expedic</span>
                </div>
            </div>
        </div>
        
        <!-- Carousel -->
        <div class="hero-carousel">
            <div class="carousel-container">
                <div class="carousel-track" id="carouselTrack">
                    <div class="carousel-slide">
                        <img src="assets/image.png" alt="Profile picture">
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/image2.png" alt="Picture">
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/image3.png" alt="PCB Design">
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/image4.png" alt="Antenna Stack">
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/image5.png" alt="Contest Operation">
                    </div>
                    
                    
                </div>
            </div>
            <button class="carousel-btn carousel-btn-prev" onclick="moveCarousel(-1)" aria-label="Předchozí">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            <button class="carousel-btn carousel-btn-next" onclick="moveCarousel(1)" aria-label="Další">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<section class="sponsors">
    <div class="container sponsors-inner">
        <span class="sponsors-label">Partneři</span>
        <div class="sponsors-logos">
            <img src="https://via.placeholder.com/100x28/151c25/8b9db5?text=ICOM" alt="ICOM" class="sponsor-logo">
            <img src="https://via.placeholder.com/100x28/151c25/8b9db5?text=YAESU" alt="YAESU" class="sponsor-logo">
            <img src="https://via.placeholder.com/100x28/151c25/8b9db5?text=ELECROW" alt="ELECROW" class="sponsor-logo">
            <img src="https://via.placeholder.com/100x28/151c25/8b9db5?text=JLCPCB" alt="JLCPCB" class="sponsor-logo">
            <img src="https://via.placeholder.com/100x28/151c25/8b9db5?text=MOUSER" alt="MOUSER" class="sponsor-logo">
        </div>
    </div>
</section>

<section id="shop" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Plošné spoje & Komponenty</h2>
            <a href="produkty.php" class="section-link">Zobrazit vše →</a>
        </div>
        <div class="shop-grid">
            <?php 
            $displayed_products = array_slice($products, 0, 3);
            foreach ($displayed_products as $p): 
            ?>
            <article class="product-card">
                <span class="product-tag"><?php echo htmlspecialchars($p['kategorie']); ?></span>
                <h3 class="product-name"><?php echo htmlspecialchars($p['nazev']); ?></h3>
                <p class="product-desc"><?php echo htmlspecialchars($p['popis']); ?></p>
                <div class="product-footer">
                    <span class="product-price"><?php echo number_format($p['cena'], 0, ',', ' '); ?> Kč</span>
                    <a href="produkty.php#produkt-<?php echo $p['id']; ?>" class="btn btn-primary">Detail</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="expeditions" class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">DX Expedice</h2>
            <a href="clanky.php?kategorie=expedice" class="section-link">Všechny expedice →</a>
        </div>
        <div class="timeline">
            <?php 
            $displayed_expedice = array_slice(array_values($expedice), 0, 3);
            foreach ($displayed_expedice as $exp): 
                $datum = new DateTime($exp['datum']);
                $now = new DateTime();
                $is_future = $datum > $now;
            ?>
            <a href="clanek.php?id=<?php echo $exp['id']; ?>" class="timeline-item">
                <span class="timeline-date"><?php echo $datum->format('M Y'); ?></span>
                <div class="timeline-content">
                    <h4><?php echo htmlspecialchars($exp['titulek']); ?></h4>
                    <p><?php echo htmlspecialchars(mb_substr(strip_tags($exp['obsah']), 0, 80)); ?>...</p>
                </div>
                <span class="status-badge <?php echo $is_future ? 'status-upcoming' : 'status-active'; ?>">
                    <?php echo $is_future ? 'Plánováno' : 'Dokončeno'; ?>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="blog" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Technické články</h2>
            <a href="clanky.php?kategorie=technika" class="section-link">Všechny články →</a>
        </div>
        <div class="blog-grid">
            <?php 
            $displayed_technika = array_slice(array_values($technika), 0, 3);
            foreach ($displayed_technika as $art): 
                $datum = new DateTime($art['datum']);
            ?>
            <article class="blog-card">
                <div class="blog-img">
                    <?php if (!empty($art['obrazek'])): ?>
                    <img src="<?php echo htmlspecialchars($art['obrazek']); ?>" alt="<?php echo htmlspecialchars($art['titulek']); ?>">
                    <?php else: ?>
                    800 × 450
                    <?php endif; ?>
                </div>
                <div class="blog-body">
                    <div class="blog-meta">
                        <span class="blog-category"><?php echo htmlspecialchars($art['kategorie']); ?></span>
                        <span class="blog-date"><?php echo $datum->format('j. F Y'); ?></span>
                    </div>
                    <h3 class="blog-title">
                        <a href="clanek.php?id=<?php echo $art['id']; ?>"><?php echo htmlspecialchars($art['titulek']); ?></a>
                    </h3>
                    <p class="blog-excerpt"><?php echo htmlspecialchars(mb_substr(strip_tags($art['obsah']), 0, 120)); ?>...</p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
