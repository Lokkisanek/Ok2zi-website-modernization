# OK2ZI - Radioamatérský portál

Moderní webový portál pro radioamatéra OK2ZI zaměřený na vývoj VF techniky, vlastní plošné spoje a DX expedice.

## Funkce

- **Hlavní stránka** s hero sekcí a fotogalerií (carousel)
- **E-shop** s plošnými spoji a komponenty
- **Blog** s technickými články a reporty z expedic
- **Administrace** pro snadné přidávání článků bez znalosti kódu
- **Light/Dark téma** s automatickým ukládáním preference
- **Responzivní design** pro všechna zařízení

## Struktura projektu

```
Ok2zi-website-modernization/
├── index.php           # Hlavní stránka
├── produkty.php        # Katalog plošných spojů
├── clanky.php          # Seznam článků
├── clanek.php          # Detail článku
├── admin.php           # Administrace
├── style.css           # Styly
├── includes/
│   ├── header.php      # Hlavička a navigace
│   └── footer.php      # Patička a JavaScript
└── data/
    ├── articles.json   # Články (automaticky ukládáno)
    └── products.json   # Produkty
```

## Požadavky

- PHP 8.0 nebo novější
- Webový server (Apache, Nginx) nebo PHP built-in server

## Lokální spuštění

### Pomocí PHP built-in serveru

1. Otevřete terminál a přejděte do složky projektu:
   ```bash
   cd /cesta/k/Ok2zi-website-modernization
   ```

2. Spusťte PHP server:
   ```bash
   php -S localhost:8000
   ```

3. Otevřete prohlížeč na adrese:
   ```
   http://localhost:8000
   ```

### Pomocí MAMP/XAMPP

1. Zkopírujte složku projektu do `htdocs` (XAMPP) nebo `Sites` (MAMP)
2. Spusťte Apache server
3. Otevřete `http://localhost/Ok2zi-website-modernization`

## Administrace

Přístup k administraci je na `/admin.php`

**Výchozí heslo:** `ok2zi2026`

> ⚠️ **Důležité:** Změňte heslo v souboru `admin.php` na řádku 6 před nasazením na produkci!

### Co můžete v administraci:
- Přidávat nové články
- Upravovat existující články
- Mazat články
- Vybrat kategorii (Technika / Expedice)
- Přidat obrázek (URL)

### Formátování článků

V textu článku můžete použít:
- `## Nadpis` pro nadpis druhé úrovně
- `- položka` pro odrážky
- `` `kód` `` pro inline kód
- ` ```kód``` ` pro blok kódu

## Přizpůsobení

### Barvy
Barevné schéma lze upravit v `style.css` v sekci `:root` (light theme) a `[data-theme="dark"]` (dark theme).

### Fotky v carouselu
Obrázky v hlavním carouselu změníte v `index.php` v sekci `<!-- Carousel -->`.

### Produkty
Produkty se upravují přímo v `data/products.json`.

## Technologie

- HTML5 + CSS3 (Flexbox, Grid)
- Vanilla JavaScript
- PHP 8.x
- JSON pro ukládání dat

## Licence

Soukromý projekt pro OK2ZI.
