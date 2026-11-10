# PM4-EFC-Radio

Thème WordPress « Radio La Matanie », stylé avec Tailwind CSS v4.

## Installation

```bash
npm install
npm run dev     # recompile le CSS à chaque modification
npm run build   # version minifiée
```

Le CSS source est `assets/css/main.css`, compilé dans `style.css` (chargé par `functions.php`).
Ne modifie pas `style.css` à la main : il est écrasé à chaque compilation.
L'en-tête du thème (Theme Name, etc.) est dans `assets/css/main.css`.

## Structure

```
assets/
  css/main.css       source Tailwind + en-tête du thème
  images/            images du thème
templates/           parties de gabarit (get_template_part)
style.css            CSS compilé (généré)
*.php                gabarits WordPress
```
