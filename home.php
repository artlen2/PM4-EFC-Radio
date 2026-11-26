<?php
/*
 * ============================================================
 *  ACTUALITÉS - list of news (regular WordPress posts)
 * ============================================================
 *  WordPress loads home.php by itself for the "Posts page":
 *    Settings > Reading > "Posts page" = the page "Actualités"
 * ============================================================
 */

get_header();

// Filters: category slug => label shown on the button
$filters = [
    'communaute' => 'Communauté',
    'culturel'   => 'Culturel',
    'politique'  => 'Politique',
    'sport'      => 'Sport',
];

// URL of this page (the filter links point back to it)
$blog_page_id = (int) get_option('page_for_posts');
$blog_url     = $blog_page_id ? get_permalink($blog_page_id) : home_url('/');

// Current filter, from the URL: /actualites/?categorie=sport (empty = "Tous")
$current = isset($_GET['categorie']) ? sanitize_key(wp_unslash($_GET['categorie'])) : '';
if (!isset($filters[$current])) {
    $current = '';
}

// Current page number (for the pagination)
$paged = max(1, (int) get_query_var('paged'));

// Use WordPress's main query, configured in functions.php.
global $wp_query;
$news = $wp_query;
?>

<?php
/* ============================================================
 *  PAGE MARKUP - the look of the page starts here
 * ============================================================ */
?>

<svg class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-[#fdbe00] md:h-24 bg-beige" viewBox="0 0 1440 100"
    preserveAspectRatio="none" aria-hidden="true">
    <path fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>

<main class="overflow-x-clip bg-beige py-12 font-sans text-black">
    <div class="container mx-auto px-4">

        <!-- Filters: "Tous" + one button per category. aria-current marks the active one -->
        <nav class="mb-12 flex flex-wrap gap-3 px-6" aria-label="Filtrer les nouvelles">
            <a href="<?php echo esc_url($blog_url); ?>"
                <?php echo $current === '' ? 'aria-current="page"' : ''; ?>
                class="cursor-pointer rounded-full btn-press px-5 py-2 font-bold hover:bg-yellow
                       focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple
                       <?php echo $current === '' ? 'bg-yellow' : 'bg-white'; ?>">
                Tous
            </a>

            <?php foreach ($filters as $slug => $label) : ?>
                <a href="<?php echo esc_url(add_query_arg('categorie', $slug, $blog_url)); ?>"
                    <?php echo $current === $slug ? 'aria-current="page"' : ''; ?>
                    class="cursor-pointer rounded-full btn-press px-5 py-2 font-bold hover:bg-yellow
                           focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple
                           <?php echo $current === $slug ? 'bg-yellow' : 'bg-white'; ?>">
                    <?php echo esc_html($label); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <?php if ($news->have_posts()) : ?>

            <!-- Grid of news cards -->
            <div class="grid grid-cols-1 gap-10 px-6 lg:grid-cols-2">
                <?php while ($news->have_posts()) : $news->the_post(); ?>

                    <article class="news-card group relative grid h-full min-w-0 grid-cols-1 overflow-hidden rounded-[2.5rem] border-[3px] border-purple bg-white <?php echo has_post_thumbnail() ? 'sm:grid-cols-2' : ''; ?>">
                        <!-- Text -->
                        <div class="flex min-w-0 flex-col p-6 sm:p-7 xl:p-8">
                            <?php $card_categories = get_the_category(); ?>

                            <?php if ($card_categories) : ?>
                                <ul
                                    class="mb-3 flex list-none flex-wrap gap-2 p-0"
                                    aria-label="Catégories">
                                    <?php foreach ($card_categories as $card_category) : ?>
                                        <li class="max-w-full rounded-full border border-purple bg-purple px-3 py-1 text-xs font-bold leading-tight text-white wrap-anywhere">
                                            <?php echo esc_html($card_category->name); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <time
                                datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                                class="mb-2 block text-sm font-semibold text-black/70">
                                <?php echo esc_html(get_the_date('j M Y')); ?>
                                <span aria-hidden="true"> · </span>
                                <?php echo esc_html(get_the_time('H \h i')); ?>
                            </time>

                            <h2 class="m-0 text-left text-xl font-bold leading-tight text-orange [overflow-wrap:anywhere] xl:text-2xl">
                                <?php the_title(); ?>
                            </h2>

                            <?php
                            $card_excerpt = wp_trim_words(
                                wp_strip_all_tags(get_the_excerpt()),
                                25,
                                '…'
                            );
                            ?>

                            <?php if ($card_excerpt) : ?>
                                <p class="mb-8 mt-6 text-left text-base leading-relaxed text-black xl:text-lg">
                                    <?php echo esc_html($card_excerpt); ?>
                                </p>
                            <?php endif; ?>

                            <a
                                href="<?php echo esc_url(get_permalink()); ?>"
                                class="btn-press mt-auto inline-flex w-fit max-w-full items-center justify-center rounded-full bg-yellow px-5 py-3 text-center text-sm font-bold leading-none text-white transition-colors duration-200 hover:bg-orange focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple">
                                Voir l'article
                            </a>
                        </div>

                        <!-- Image: below the text on mobile, right side on wider screens -->
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="relative m-0 min-h-64 min-w-0 p-2 sm:min-h-80 sm:pl-0">
                                <div class="relative h-full min-h-64 overflow-hidden rounded-[1.75rem] sm:min-h-80">
                                    <?php
                                    the_post_thumbnail('large', [
                                        'class' => 'news-card__image absolute inset-0 block h-full w-full object-cover object-center',
                                        'alt'     => '',
                                        'loading' => 'lazy',
                                    ]);
                                    ?>
                                </div>
                            </figure>
                        <?php endif; ?>

                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination (keeps the active filter in the links) -->
            <nav class="mt-12 flex flex-wrap justify-center gap-2 px-6
                        [&_.page-numbers]:rounded-full [&_.page-numbers]:bg-white [&_.page-numbers]:px-4 [&_.page-numbers]:py-2 [&_.page-numbers]:font-bold
                        [&_a.page-numbers:hover]:bg-yellow [&_.current]:bg-yellow"
                aria-label="Pagination">
                <?php
                echo paginate_links([
                    'total'     => $news->max_num_pages,
                    'current'   => $paged,
                    'add_args'  => $current ? ['categorie' => $current] : false,
                    'prev_text' => '←',
                    'next_text' => '→',
                ]);
                ?>
            </nav>

        <?php else : ?>

            <!-- Nothing found (empty category) -->
            <p class="px-6 text-lg">Aucune nouvelle pour le moment.</p>

        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</main>

<svg class="hero-wave -mt-px -mb-px block h-12 w-full rotate-180 text-beige md:h-16" viewBox="0 20 1440 80"
    preserveAspectRatio="none" aria-hidden="true">
    <path fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>

<?php get_footer(); ?>