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

// The query
$query_args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 4,
    'paged'          => $paged,
];
if ($current) {
    $query_args['category_name'] = $current;
}
$news = new WP_Query($query_args);
?>

<?php
/* ============================================================
 *  PAGE MARKUP - the look of the page starts here
 * ============================================================ */
?>
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
            <div class="grid list-none grid-cols-1 gap-8 px-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php while ($news->have_posts()) : $news->the_post(); ?>

                    <article class="group flex flex-col overflow-hidden rounded-3xl border-[3px] border-purple bg-white">

                        <!-- Image (the title link below is the "real" link, so this one is hidden from keyboard / screen readers) -->
                        <a href="<?php the_permalink(); ?>" class="block aspect-video overflow-hidden bg-violet/20"
                            aria-hidden="true" tabindex="-1">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', [
                                    'class'   => 'h-full w-full object-cover transition duration-300 group-hover:scale-105',
                                    'loading' => 'lazy',
                                ]); ?>
                            <?php endif; ?>
                        </a>

                        <div class="flex flex-1 flex-col gap-3 p-6">

                            <!-- Category tags (only the ones listed in $filters) + date -->
                            <div class="flex flex-wrap items-center gap-2 text-sm">
                                <?php foreach (get_the_category() as $cat) : ?>
                                    <?php if (isset($filters[$cat->slug])) : ?>
                                        <span class="rounded-full bg-violet/20 px-3 py-1 font-semibold text-purple">
                                            <?php echo esc_html($cat->name); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="ml-auto font-semibold text-orange">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                            </div>

                            <!-- Title -->
                            <h2 class="font-title text-2xl font-black uppercase leading-tight wrap-anywhere">
                                <a href="<?php the_permalink(); ?>" class="hover:text-purple">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <!-- Short excerpt -->
                            <p class="leading-relaxed">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 25)); ?>
                            </p>

                            <!-- Read more (pushed to the bottom so all cards line up) -->
                            <a href="<?php the_permalink(); ?>"
                                class="mt-auto font-bold text-purple hover:text-orange">
                                Lire la suite →
                            </a>
                        </div>
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

<?php get_footer(); ?>