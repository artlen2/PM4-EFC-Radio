<?php
/*
Template Name: À propos
*/

/*
 * ============================================================
 *  PAGE "À PROPOS" - QUICK GUIDE :)
 * ============================================================
 *  File layout (top to bottom):
 *    1. Queries -> get the animators, team members and partners
 *    2. Helpers -> small functions that print the cards, popups and carousels
 *    3. Data preparation -> clean arrays built from the queries + the page fields
 *    4. !! Page markup -> <main> - HTML of the page !!
 *    5. Script -> carousel loop, arrows and "Voir plus" popups
 *
 *  WANT TO CHANGE THE LOOK?
 *    - Page layout, colors, spacing, text sizes
 *        -> Tailwind classes in the <main> tag down
 *           (carousel cards & popups -> inside the helper functions)
 *    - Carousel sizes 
 *        -> card width, space between cards, big icons, popup width, edge fade
 *        -> main.css (classes starting with "radio-")
 *    - Text and images
 *        -> WordPress
 * ============================================================
 */

get_header();

/* ------------------------------------------------------------
   1. QUERIES
   Get every published animator, team member and partner,
   sorted by "order" then by title.
------------------------------------------------------------ */
$query_args = [
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
];

$all_animators = get_posts($query_args + ['post_type' => 'animateur']);
$all_team_members = get_posts($query_args + ['post_type' => 'equipe']);
$all_partners = get_posts($query_args + ['post_type' => 'partenaire']);

/* ------------------------------------------------------------
   2. HELPERS
   Each function is wrapped in function_exists() so it is only
   declared once.
------------------------------------------------------------ */

// Turns an SCF image field (array, id or url) into ['url' => ..., 'alt' => ...]
if (!function_exists('radio_image')) {
    function radio_image($field, $fallback_alt = '')
    {
        if (!$field) {
            return null;
        }
        if (is_array($field)) {
            return [
                'url' => $field['url'] ?? '',
                'alt' => !empty($field['alt']) ? $field['alt'] : $fallback_alt,
            ];
        }
        if (is_numeric($field)) {
            return [
                'url' => wp_get_attachment_image_url((int) $field, 'large'),
                'alt' => get_post_meta((int) $field, '_wp_attachment_image_alt', true) ?: $fallback_alt,
            ];
        }
        return ['url' => (string) $field, 'alt' => $fallback_alt];
    }
}

// Find a file in the theme, ignoring upper/lower case in the file name
// (Linux servers are case-sensitive: "Old-Cam.png" is not "old-cam.png")
if (!function_exists('radio_find_asset')) {
    function radio_find_asset($relative)
    {
        $relative = ltrim((string) $relative, '/');
        $base = get_template_directory();
        if ($relative === '') {
            return null;
        }
        if (file_exists($base . '/' . $relative)) {
            return $relative;
        }
        $dir  = dirname($relative);
        $name = basename($relative);
        $files = @scandir($base . '/' . $dir);
        if ($files) {
            foreach ($files as $file) {
                if (strcasecmp($file, $name) === 0) {
                    return ($dir === '.' ? '' : $dir . '/') . $file;
                }
            }
        }
        return null;
    }
}

// CARD: one person in the carousel (only the <li>, the popup is printed separately)
if (!function_exists('radio_member_card')) {
    function radio_member_card($member)
    {
        $uid = 'member-' . $member['id'];
        $img = radio_image($member['photo'], $member['name']);
        $radius = $member['shape'] === 'round' ? 'rounded-full' : 'rounded-3xl';
?>
        <!-- Card width comes from .radio-card (main.css) -->
        <li class="radio-card group shrink-0 snap-center scale-90 opacity-50 transition duration-300 data-[active=true]:scale-100 data-[active=true]:opacity-100" data-card>
            <!-- Name + role. Fixed height so every photo lines up, tall enough for a 2 line name + 2 line role -->
            <div class="radio-card-head flex flex-col justify-end">
                <h4 class="font-title text-2xl font-black uppercase leading-tight sm:text-3xl wrap-anywhere">
                    <?php echo esc_html($member['name']); ?>
                </h4>
                <?php if ($member['role']) : ?>
                    <p class="mt-0.5 font-semibold leading-tight text-purple">
                        <?php echo esc_html($member['role']); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Photo (or the first letter of the name if there is no photo) + "Voir plus" button -->
            <div class="relative mt-2">
                <div class="aspect-square overflow-hidden border-[3px] border-violet bg-white
                            group-data-[active=true]:border-purple <?php echo $radius; ?>">
                    <?php if ($img && $img['url']) : ?>
                        <img src="<?php echo esc_url($img['url']); ?>"
                            alt="<?php echo esc_attr($img['alt']); ?>"
                            class="h-full w-full object-cover"
                            loading="lazy">
                    <?php else : ?>
                        <div class="flex h-full w-full items-center justify-center bg-violet/20 font-title text-purple"
                            aria-hidden="true">
                            <?php echo esc_html(mb_substr($member['name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- data-open = id of the popup to open (see the script at the bottom) -->
                <button type="button"
                    data-open="<?php echo esc_attr($uid); ?>"
                    class="absolute -bottom-2 right-0 translate-x-2 cursor-pointer rounded-lg border-2 border-black
                           bg-yellow px-3 py-1 font-bold text-white shadow-[2px_2px_0_0_#0F0E0F]
                           transition hover:bg-orange hover:translate-y-0.5 hover:shadow-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                    aria-label="<?php echo esc_attr(sprintf('Voir plus sur %s', $member['name'])); ?>">
                    Voir plus
                </button>
            </div>
        </li>
    <?php
    }
}

// POPUP: the "Voir plus" window
if (!function_exists('radio_member_dialog')) {
    function radio_member_dialog($member)
    {
        $uid = 'member-' . $member['id'];
        $img = radio_image($member['photo'], $member['name']);
        $radius = $member['shape'] === 'round' ? 'rounded-full' : 'rounded-3xl';
    ?>
        <!-- Popup width comes from .radio-dialog (main.css) -->
        <dialog id="<?php echo esc_attr($uid); ?>"
            class="radio-dialog m-auto max-h-[85dvh] overflow-x-hidden overflow-y-auto overscroll-contain
                   rounded-3xl border-[3px] border-purple bg-beige p-0 text-black backdrop:bg-black/60"
            aria-labelledby="<?php echo esc_attr($uid); ?>-title">
            <div class="flex flex-col gap-4 p-6 sm:p-8">

                <!-- Popup header -->
                <div class="flex items-start gap-4">
                    <?php if ($img && $img['url']) : ?>
                        <img src="<?php echo esc_url($img['url']); ?>"
                            alt=""
                            class="size-20 shrink-0 border-[3px] border-purple object-cover <?php echo $radius; ?>">
                    <?php endif; ?>

                    <div class="min-w-0 flex-1">
                        <h3 id="<?php echo esc_attr($uid); ?>-title"
                            class="font-title text-2xl font-black uppercase leading-tight text-purple wrap-anywhere sm:text-3xl">
                            <?php echo esc_html($member['name']); ?>
                        </h3>
                        <?php if ($member['role']) : ?>
                            <p class="mt-1 font-semibold text-orange"><?php echo esc_html($member['role']); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- data-close = closes the popup (see the script at the bottom) -->
                    <button type="button" data-close
                        class="shrink-0 cursor-pointer rounded-full btn-press bg-white px-3 py-1 font-bold
                               hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                        aria-label="Fermer">
                        ✕
                    </button>
                </div>

                <!-- Bio / description -->
                <?php if ($member['text']) : ?>
                    <div class="max-w-prose space-y-3 leading-relaxed wrap-anywhere">
                        <?php echo wp_kses_post(wpautop($member['text'])); ?>
                    </div>
                <?php endif; ?>

            </div>
        </dialog>
    <?php
    }
}

// CAROUSEL: title + icon + arrows + the row of cards + the popups
// $icon = 'assets/objets/micro.png'
if (!function_exists('radio_carousel')) {
    function radio_carousel($id, $title, $icon, $members)
    {
        if (empty($members)) {
            return;
        }
    ?>
        <section class="relative py-10" aria-labelledby="<?php echo esc_attr($id); ?>">
            <!-- Carousel header (title, icon, previous / next arrows) -->
            <div class="container mx-auto mb-8 flex items-center gap-4 px-10">
                <h2 id="<?php echo esc_attr($id); ?>"
                    class="font-title font-black uppercase text-yellow">
                    <?php echo esc_html($title); ?>
                </h2>
                <?php
                $icon_rel = radio_find_asset($icon);
                if ($icon_rel) : ?>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/' . $icon_rel); ?>"
                        alt="" class="radio-icon" aria-hidden="true">
                <?php else : ?>
                    <?php echo esc_html($icon); ?>
                <?php endif; ?>

                <div class="ml-auto flex gap-2">
                    <button type="button" data-scroll="-1" data-target="<?php echo esc_attr($id); ?>"
                        class="cursor-pointer rounded-full btn-press bg-white size-10 font-bold
                               hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                        aria-label="Précédent">←</button>
                    <button type="button" data-scroll="1" data-target="<?php echo esc_attr($id); ?>"
                        class="cursor-pointer rounded-full btn-press bg-white size-10 font-bold
                               hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                        aria-label="Suivant">→</button>
                </div>
            </div>

            <!-- The row of cards. data-track = infinite loop -->
            <ul id="<?php echo esc_attr($id); ?>" data-track
                class="radio-track relative flex snap-x snap-mandatory overflow-x-auto pb-8 pt-6
                       list-none m-0 scrollbar-none [&::-webkit-scrollbar]:hidden">
                <?php foreach ($members as $member) {
                    radio_member_card($member);
                } ?>
            </ul>

            <!-- One popup per person, outside of the <ul> so they are never cloned -->
            <?php foreach ($members as $member) {
                radio_member_dialog($member);
            } ?>
        </section>
<?php
    }
}

/* ------------------------------------------------------------
   3. DATA PREPARATION
   Animators and team members have different field names in
   WordPress, so both are turned into the same simple array
------------------------------------------------------------ */
$animators = array_map(function ($p) {
    return [
        'id'    => $p->ID,
        'name'  => get_the_title($p),
        'role'  => get_field('role', $p->ID),
        'photo' => get_field('photo', $p->ID),
        'text'  => get_field('bio', $p->ID),
        'shape' => 'round',
    ];
}, $all_animators);

$team = array_map(function ($p) {
    return [
        'id'    => $p->ID,
        'name'  => get_field('prenom_nom', $p->ID) ?: get_the_title($p),
        'role'  => get_field('role', $p->ID),
        'photo' => get_field('photo', $p->ID),
        'text'  => get_field('description', $p->ID),
        'shape' => 'squircle',
    ];
}, $all_team_members);

// Text fields of this page (edit them in the WordPress admin, on the "À propos" page)
$mission_title = get_field('mission_title');
$mission_text = get_field('mission_text');
$politique_title = get_field('politique_title');
$politique_texte = get_field('politique_texte');
$nous_joindre_title = get_field('nous_joindre_title');
$nous_joindre_texte = get_field('nous_joindre_texte');
$nos_frequences_title = get_field('nos_frequences_title');
$nos_frequences_texte = get_field('nos_frequences_texte');
?>


<?php
/*
 * ============================================================
 *  4. PAGE MARKUP - the look of the page starts here
 *  To change the appearance:
 *    - edit the Tailwind classes below, or
 *    - edit main.css (classes with "radio-")
 * ============================================================
 */
?>
<svg class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-[#fdbe00] md:h-24" viewBox="0 0 1440 100"
    preserveAspectRatio="none" aria-hidden="true">
    <path fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>
<!-- overflow-x-clip hides the sideways overflow of the carousel copies -->
<main class="overflow-x-clip bg-beige py-12 font-sans text-black">

    <!-- Mission (page title + text) -->
    <div class="container mx-auto px-4 bg-violet">
        <h1 class="font-title font-black uppercase text-white">
            <?php echo esc_html($mission_title ?: get_the_title()); ?>
        </h1>

        <?php if ($mission_text) : ?>
            <div class="mt-10 max-w-2xl px-6 text-left leading-relaxed text-white">
                <?php echo wp_kses_post(wpautop($mission_text)); ?>
            </div>
        <?php endif; ?>
    </div>

    <svg class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-violet md:h-24" viewBox="0 0 1440 100"
        preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor"
            d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
    </svg>

    <!-- Animateurs  -->
    <?php radio_carousel('animateurs', 'Animateurs', 'assets/objets/micro.png', $animators); ?>

    <!-- Employés  -->
    <?php radio_carousel('equipe', 'Employés', 'assets/objets/old-cam.png', $team); ?>

    <!-- Everything below is a regular text/logo section inside the page container -->
    <div class="container mx-auto px-4">

        <!-- Nos Fréquences -->
        <?php if ($nos_frequences_title || $nos_frequences_texte) : ?>
            <section class="px-6 py-10" aria-labelledby="politique-title">
                <?php if ($nos_frequences_title) : ?>
                    <h2 id="politique-title" class="mb-4 font-title font-black uppercase text-yellow">
                        <?php echo esc_html($nos_frequences_title); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($nos_frequences_texte) : ?>
                    <div class="space-y-3 leading-relaxed">
                        <?php echo wp_kses_post(wpautop($nos_frequences_texte)); ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <!-- Partenaires (logo grid, each logo links to the partner's site if a link is set) -->
        <?php if (!empty($all_partners)) : ?>
            <section class="px-6 py-16" aria-labelledby="partenaires-title">
                <h2 id="partenaires-title"
                    class="mb-10 font-title font-black uppercase text-yellow">
                    Partenaires
                </h2>

                <ul class="grid list-none grid-cols-2 gap-6 p-0 m-0 sm:grid-cols-3 lg:grid-cols-4">
                    <?php foreach ($all_partners as $partner) :
                        $logo = radio_image(get_field('logo', $partner->ID), get_the_title($partner));
                        $link = get_field('link', $partner->ID);
                        // No logo = skip this partner
                        if (!$logo || !$logo['url']) {
                            continue;
                        }
                    ?>
                        <li>
                            <!-- With a link: <a>. Without: a plain <div>. Both closed after the <img> -->
                            <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link); ?>"
                                    target="_blank" rel="noopener noreferrer"
                                    class="flex aspect-3/2 items-center justify-center rounded-2xl border-2 border-purple/20 bg-white p-6
                                           transition hover:border-purple focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple"
                                    aria-label="<?php echo esc_attr(sprintf('Visiter le site de %s (nouvel onglet)', get_the_title($partner))); ?>">
                                <?php else : ?>
                                    <div class="flex aspect-3/2 items-center justify-center rounded-2xl border-2 border-purple/20 bg-white p-6">
                                    <?php endif; ?>

                                    <img src="<?php echo esc_url($logo['url']); ?>"
                                        alt="<?php echo esc_attr($logo['alt']); ?>"
                                        class="max-h-full max-w-full object-contain"
                                        loading="lazy">

                                    <?php echo $link ? '</a>' : '</div>'; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <!-- Nous joindre -->
        <?php if ($nous_joindre_title || $nous_joindre_texte) : ?>
            <section class="px-6 py-10 pb-16" aria-labelledby="contact-title">
                <?php if ($nous_joindre_title) : ?>
                    <h2 id="contact-title" class="mb-4 font-title font-black uppercase text-yellow">
                        <?php echo esc_html($nous_joindre_title); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($nous_joindre_texte) : ?>
                    <div class="space-y-3 leading-relaxed">
                        <?php echo wp_kses_post(wpautop($nous_joindre_texte)); ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <!-- Nos Politiques -->
        <?php if ($politique_title || $politique_texte) : ?>
            <section class="px-6 py-10" aria-labelledby="politique-title">
                <?php if ($politique_title) : ?>
                    <h2 id="politique-title" class="mb-4 font-title font-black uppercase text-yellow">
                        <?php echo esc_html($politique_title); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($politique_texte) : ?>
                    <div class="space-y-3 leading-relaxed">
                        <?php echo wp_kses_post(wpautop($politique_texte)); ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

    </div>

</main>

<!-- ============================================================
     5. SCRIPT - carousel loop, arrows and popups.
     Not about the look: only edit this to change how the carousels behave.
     ============================================================ -->
<script>
    (function() {
        // Respect the visitor's "reduce motion" setting (arrows jump instead of gliding)
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Copy of a card for the loop: hidden from screen readers and skipped by Tab
        function makeClone(card) {
            var clone = card.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            clone.querySelectorAll('button').forEach(function(b) {
                b.tabIndex = -1;
            });
            return clone;
        }

        // ---- Infinite carousels: runs once for each row of cards ----
        document.querySelectorAll('[data-track]').forEach(function(track) {
            var originals = Array.prototype.slice.call(track.querySelectorAll('[data-card]'));
            var count = originals.length;
            if (!count) return;

            var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
            var setWidth = count * (originals[0].offsetWidth + gap); // width of one full set of cards

            // Move the scroll position without the snapping interfering
            function jump(left) {
                track.style.scrollSnapType = 'none';
                track.scrollLeft = left;
                track.style.scrollSnapType = '';
            }

            // 1. Put identical copies of the set before and after the real cards.
            //    This now also runs when there is only ONE card (no "count > 1" check),
            //    so a carousel with a single animator still loops.
            var perSide = Math.ceil(track.clientWidth / setWidth) + 1;
            var before = document.createDocumentFragment();
            var after = document.createDocumentFragment();
            for (var i = 0; i < perSide; i++) {
                originals.forEach(function(card) {
                    before.appendChild(makeClone(card));
                    after.appendChild(makeClone(card));
                });
            }
            track.insertBefore(before, originals[0]);
            track.appendChild(after);

            // All cards, copies included
            var cards = track.querySelectorAll('[data-card]');

            // 2. The active card is the one closest to the center of the track
            //    (data-active="true" makes it bigger and fully visible, see the card classes)
            function updateActive() {
                var center = track.scrollLeft + track.clientWidth / 2;
                var best = null;
                var bestDist = Infinity;
                cards.forEach(function(card) {
                    var dist = Math.abs(card.offsetLeft + card.offsetWidth / 2 - center);
                    if (dist < bestDist) {
                        bestDist = dist;
                        best = card;
                    }
                });
                cards.forEach(function(card) {
                    card.dataset.active = card === best ? 'true' : 'false';
                });
            }

            // 3. When scrolling stops on a copy, jump to the identical real card
            var touching = false;
            var timer = null;
            var ticking = false;

            function wrap() {
                if (!touching) {
                    var start = originals[0].offsetLeft;
                    var center = track.scrollLeft + track.clientWidth / 2;
                    var target = null;
                    if (center < start) {
                        target = track.scrollLeft + setWidth;
                    } else if (center >= start + setWidth) {
                        target = track.scrollLeft - setWidth;
                    }
                    if (target !== null) {
                        // The copy and the real card look identical, but the real one was "inactive"
                        // (faded + small), so the CSS transition made it flash. Swap with transitions off.
                        track.classList.add('is-jumping');
                        jump(target);
                        updateActive();
                        void track.offsetWidth; // apply the instant change before re-enabling transitions
                        requestAnimationFrame(function() {
                            track.classList.remove('is-jumping');
                        });
                        return;
                    }
                }
                updateActive();
            }

            // While scrolling: update the active card, and wait until scrolling stops to do the jump
            track.addEventListener('scroll', function() {
                if (!ticking) {
                    ticking = true;
                    requestAnimationFrame(function() {
                        updateActive();
                        ticking = false;
                    });
                }
                clearTimeout(timer);
                timer = setTimeout(wrap, 120);
            }, {
                passive: true
            });

            // Do not jump while a finger is still on the track
            track.addEventListener('touchstart', function() {
                touching = true;
            }, {
                passive: true
            });
            ['touchend', 'touchcancel'].forEach(function(type) {
                track.addEventListener(type, function() {
                    touching = false;
                    clearTimeout(timer);
                    timer = setTimeout(wrap, 150);
                }, {
                    passive: true
                });
            });

            // Start on the first real card, centered
            jump(originals[0].offsetLeft - (track.clientWidth - originals[0].offsetWidth) / 2);
            updateActive();
        });

        // ---- Previous / next arrows: scroll the carousel by one card ----
        document.querySelectorAll('[data-scroll]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var track = document.getElementById(btn.dataset.target);
                var card = track.querySelector('[data-card]');
                var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
                track.scrollBy({
                    left: (card.offsetWidth + gap) * Number(btn.dataset.scroll),
                    behavior: reduceMotion ? 'auto' : 'smooth'
                });
            });
        });

        // ---- "Voir plus" popups ----
        // Open (delegated so the cloned cards work too)
        document.addEventListener('click', function(e) {
            var openBtn = e.target.closest('[data-open]');
            if (!openBtn) return;
            var dlg = document.getElementById(openBtn.dataset.open);
            if (dlg) dlg.showModal();
        });
        // Close with the ✕ button or by clicking outside the popup
        document.querySelectorAll('dialog').forEach(function(dlg) {
            dlg.querySelector('[data-close]').addEventListener('click', function() {
                dlg.close();
            });
            dlg.addEventListener('click', function(e) { // click on the backdrop
                if (e.target === dlg) dlg.close();
            });
        });
    })();
</script>

<svg class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-beige md:h-24  bg-transparent" viewBox="0 0 1440 100"
    preserveAspectRatio="none" aria-hidden="true">
    <path fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>

<?php get_footer(); ?>