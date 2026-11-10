# PM4-EFC-Radio

Thème WordPress du projet EFC Radio, stylé avec Tailwind CSS v4.

## Installation

```bash
npm install
npm run dev     # recompile le CSS à chaque modification
npm run build   # version minifiée
```

> Le CSS compilé (`assets/css/output.css`) n'est pas dans git : lance `npm run build` après chaque `git pull`.

Le CSS source est `assets/css/input.css`, compilé dans `assets/css/output.css` (chargé par `functions.php`).
`style.css` ne contient que l'en-tête du thème exigé par WordPress.

## Structure

```
assets/
  css/input.css      source Tailwind
  css/output.css     CSS compilé (généré, non versionné)
  images/            images du thème
templates/           parties de gabarit (get_template_part)
*.php                gabarits WordPress
```
