<?php

/**
 * Individual news articles — regular WordPress posts.
 */

get_header();

$blog_page_id = (int) get_option('page_for_posts');
$blog_url = $blog_page_id
    ? get_permalink($blog_page_id)
    : home_url('/');
?>

<main class="news-single bg-beige py-12 font-sans text-black">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        <!-- Back to the news listing -->
        <nav class="mb-8" aria-label="Retour aux actualités">
            <a
                href="<?php echo esc_url($blog_url); ?>"
                class="btn-press inline-flex items-center justify-center gap-2 rounded-full bg-yellow px-5 py-3 text-sm font-bold text-black transition-colors hover:bg-orange focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple">
                <span aria-hidden="true">←</span>
                Toutes les actualités
            </a>
        </nav>

        <?php while (have_posts()) : the_post(); ?>

            <article
                id="post-<?php the_ID(); ?>"
                <?php post_class('news-single__article'); ?>
                aria-labelledby="news-article-title">
                <header class="news-single__header">

                    <!-- Categories -->
                    <?php $categories = get_the_category(); ?>

                    <?php if ($categories) : ?>
                        <ul
                            class="news-single__categories"
                            aria-label="Catégories">
                            <?php foreach ($categories as $category) : ?>
                                <li>
                                    <span class="news-single__category bg-yellow">
                                        <?php echo esc_html($category->name); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Publication date and time -->
                    <p class="news-single__date">
                        Publié le
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date('j F Y')); ?>
                            à
                            <?php echo esc_html(get_the_time('H \h i')); ?>
                        </time>
                    </p>

                    <h1
                        id="news-article-title"
                        class="news-single__title font-title text-orange">
                        <?php the_title(); ?>
                    </h1>

                </header>

                <!-- Featured image -->
                <?php if (has_post_thumbnail()) : ?>
                    <figure class="news-single__figure">
                        <?php
                        the_post_thumbnail('full', [
                            'class'         => 'news-single__image',
                            'loading'       => 'eager',
                            'fetchpriority' => 'high',
                            'sizes'         => '(max-width: 1152px) 100vw, 1152px',
                        ]);

                        $image_caption = get_the_post_thumbnail_caption();
                        ?>

                        <?php if ($image_caption) : ?>
                            <figcaption class="news-single__caption">
                                <?php echo wp_kses_post($image_caption); ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>

                <!-- Full article, including editor blocks -->
                <div class="news-single__content">
                    <?php the_content(); ?>

                    <?php
                    wp_link_pages([
                        'before' => '<nav class="news-single__pages" aria-label="Pages de l’article"><span>Pages :</span>',
                        'after'  => '</nav>',
                    ]);
                    ?>
                </div>

            </article>

            <!-- Previous / next article -->
            <?php
            $older_post = get_previous_post();
            $newer_post = get_next_post();
            ?>

            <?php if ($older_post || $newer_post) : ?>
                <nav
                    class="news-single__navigation"
                    aria-label="Autres actualités">
                    <div class="news-single__navigation-item">
                        <?php if ($older_post) : ?>
                            <a
                                href="<?php echo esc_url(get_permalink($older_post)); ?>"
                                class="btn-press inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-bold text-black transition-colors hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple">
                                <span aria-hidden="true">←</span>
                                Article précédent
                            </a>

                            <p class="news-single__navigation-title">
                                <?php echo esc_html(get_the_title($older_post)); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="news-single__navigation-item news-single__navigation-item--next">
                        <?php if ($newer_post) : ?>
                            <a
                                href="<?php echo esc_url(get_permalink($newer_post)); ?>"
                                class="btn-press inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-bold text-black transition-colors hover:bg-yellow focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple">
                                Article suivant
                                <span aria-hidden="true">→</span>
                            </a>

                            <p class="news-single__navigation-title">
                                <?php echo esc_html(get_the_title($newer_post)); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>