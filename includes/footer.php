<footer id="kontakt" class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="nav-logo" href="index.php">OK2ZI <span>RADIO</span></a>
                <p>Radioamatérský portál zaměřený na vývoj VF techniky, vlastní plošné spoje a DX expedice.</p>
            </div>
            <div>
                <h4 class="footer-title">Navigace</h4>
                <ul class="footer-links">
                    <li><a href="produkty.php">Plošné spoje</a></li>
                    <li><a href="clanky.php?kategorie=expedice">Expedice</a></li>
                    <li><a href="clanky.php?kategorie=technika">Technika</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Odkazy</h4>
                <ul class="footer-links">
                    <li><a href="https://www.qrz.com/db/OK2ZI" target="_blank">QRZ.com</a></li>
                    <li><a href="https://lotw.arrl.org/" target="_blank">LoTW</a></li>
                    <li><a href="https://www.eqsl.cc/" target="_blank">eQSL</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Kontakt</h4>
                <ul class="footer-links">
                    <li><a href="mailto:ok2zi@email.cz">ok2zi@email.cz</a></li>
                    <li><a href="admin.php">Administrace</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© <?php echo date('Y'); ?> OK2ZI. Všechna práva vyhrazena.</span>
            <span class="footer-qth">QTH: JN89GE · Brno, Czechia</span>
        </div>
    </div>
</footer>

<script>
function toggleMenu() {
    const links = document.getElementById('nav-links');
    links.classList.toggle('nav-open');
}

function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    if (newTheme === 'dark') {
        html.setAttribute('data-theme', 'dark');
    } else {
        html.removeAttribute('data-theme');
    }
    
    localStorage.setItem('theme', newTheme);
}

// Carousel functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const totalSlides = slides.length;

function updateCarousel() {
    slides.forEach((slide, index) => {
        let position = index - currentSlide;
        
        // Wrap around for infinite effect
        if (position < -2) position += totalSlides;
        if (position > 2) position -= totalSlides;
        
        if (position >= -2 && position <= 2) {
            slide.setAttribute('data-position', position);
        } else {
            slide.setAttribute('data-position', 'hidden');
        }
    });
}

function moveCarousel(direction) {
    currentSlide += direction;
    
    if (currentSlide < 0) currentSlide = totalSlides - 1;
    if (currentSlide >= totalSlides) currentSlide = 0;
    
    updateCarousel();
}

// Initialize carousel
if (slides.length > 0) {
    updateCarousel();
    
    // Auto-rotate every 5 seconds
    setInterval(() => moveCarousel(1), 5000);
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
});
</script>

</body>
</html>
