# Mubeen coded homepage

The front page is rendered directly by WordPress from this module. It does not
use Elementor widgets, shortcodes, or generated builder markup.

- `template-home.php` — page content, links, card data, and HTML structure
- `assets/home.css` — colors, spacing, layouts, and responsive design
- `assets/home.js` — mobile navigation behavior
- `bootstrap.php` — WordPress template and asset loader

The module is loaded by `../mubeen-site-core.php` and only affects the page set
as WordPress's static front page. Asset versions use file modification times, so
CSS and JavaScript changes automatically bypass stale browser caches.
