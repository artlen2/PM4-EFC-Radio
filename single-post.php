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

<!-- Wave transition: yellow header -> beige main -->
<svg
    class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 bg-beige text-[#fdbe00] md:h-24"
    viewBox="0 0 1440 100"
    preserveAspectRatio="none"
    aria-hidden="true">
    <path
        fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>

<main
    class="news-single relative isolate overflow-hidden bg-beige py-10 font-sans text-black sm:py-16"
    style="background-image: linear-gradient(rgba(255, 243, 207, 0.92), rgba(255, 243, 207, 0.92)), url('<?php echo esc_url(get_theme_file_uri('/assets/objets/vecteur.png')); ?>');">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        <!-- Back to the news listing -->
        <nav class="news-return" aria-label="Retour aux actualités">
            <a
                href="<?php echo esc_url($blog_url); ?>"
                class="btn-press news-return__link">
                <span class="news-return__arrow" aria-hidden="true">←</span>
                <span>Toutes les actualités</span>
            </a>
        </nav>

        <?php while (have_posts()) : the_post(); ?>

            <article
                id="post-<?php the_ID(); ?>"
                <?php post_class('news-story'); ?>
                aria-labelledby="news-article-title">

                <div class="grid items-center gap-8 sm:gap-10 md:gap-12 <?php echo has_post_thumbnail() ? 'md:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]' : ''; ?>">

                    <!-- Article heading -->
                    <header class="news-story__header">

                        <div class="news-story__meta">
                            <?php $categories = get_the_category(); ?>

                            <?php if ($categories) : ?>
                                <ul
                                    class="news-story__categories"
                                    aria-label="Catégories">
                                    <?php foreach ($categories as $category) : ?>
                                        <li class="news-story__category">
                                            <?php echo esc_html($category->name); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <p class="news-story__date">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date('j F Y')); ?>
                                    <span aria-hidden="true"> · </span>
                                    <?php echo esc_html(get_the_time('H \h i')); ?>
                                </time>
                            </p>
                        </div>

                        <h1
                            id="news-article-title"
                            class="news-story__headline">
                            <?php the_title(); ?>
                        </h1>

                        <?php if (has_excerpt()) : ?>
                            <p class="news-story__intro">
                                <?php
                                echo esc_html(
                                    wp_strip_all_tags(get_the_excerpt())
                                );
                                ?>
                            </p>
                        <?php endif; ?>

                    </header>
                    <!-- Featured image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="relative m-0 mx-auto w-full min-w-0 max-w-xl">

                            <!-- Decorative offset frame -->
                            <div
                                aria-hidden="true"
                                class="pointer-events-none absolute inset-0 translate-x-2 translate-y-2 rounded-[1.75rem] bg-purple sm:translate-x-3 sm:translate-y-3"></div>

                            <div class="relative z-10 overflow-hidden rounded-[1.75rem] border-[3px] border-purple bg-white">
                                <?php
                                the_post_thumbnail('large', [
                                    'class'         => 'block aspect-[16/10] h-auto w-full object-cover object-center',
                                    'loading'       => 'eager',
                                    'fetchpriority' => 'high',
                                    'sizes'         => '(min-width: 1152px) 480px, (min-width: 768px) 45vw, 100vw',
                                ]);
                                ?>
                            </div>

                            <?php $image_caption = get_the_post_thumbnail_caption(); ?>

                            <?php if ($image_caption) : ?>
                                <figcaption class="relative z-10 mt-5 text-sm leading-relaxed text-black/70">
                                    <?php echo wp_kses_post($image_caption); ?>
                                </figcaption>
                            <?php endif; ?>

                        </figure>
                    <?php endif; ?>

                </div>

                <!-- Full article -->
                <div class="news-story__body">
                    <div class="news-single__content">
                        <?php the_content(); ?>

                        <?php
                        wp_link_pages([
                            'before' => '<nav class="news-single__pages" aria-label="Pages de l’article"><span>Pages :</span>',
                            'after'  => '</nav>',
                        ]);
                        ?>
                    </div>
                </div>

            </article>

            <!-- Previous / next article -->
            <?php
            $older_post = get_previous_post();
            $newer_post = get_next_post();
            ?>

            <?php if ($older_post || $newer_post) : ?>
                <nav
                    class="mt-12 grid gap-6 sm:grid-cols-2"
                    aria-label="Autres actualités">

                    <div>
                        <?php if ($older_post) : ?>
                            <a
                                href="<?php echo esc_url(get_permalink($older_post)); ?>"
                                class="news-nav-card group flex h-full flex-col justify-between rounded-2xl border-2 border-purple bg-white p-6 text-black no-underline transition-transform duration-200 hover:-translate-y-1 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple">
                                <span class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-purple">
                                    <span aria-hidden="true" class="transition-transform group-hover:-translate-x-1">←</span>
                                    Article précédent
                                </span>
                                <span class="mt-2 block text-base font-bold leading-snug text-black sm:text-lg">
                                    <?php echo esc_html(get_the_title($older_post)); ?>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div>
                        <?php if ($newer_post) : ?>
                            <a
                                href="<?php echo esc_url(get_permalink($newer_post)); ?>"
                                class="news-nav-card group flex h-full flex-col justify-between rounded-2xl border-2 border-purple bg-white p-6 text-right text-black no-underline transition-transform duration-200 hover:-translate-y-1 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-purple">
                                <span class="inline-flex items-center justify-end gap-2 text-xs font-extrabold uppercase tracking-wider text-purple">
                                    Article suivant
                                    <span aria-hidden="true" class="transition-transform group-hover:translate-x-1">→</span>
                                </span>
                                <span class="mt-2 block text-base font-bold leading-snug text-black sm:text-lg">
                                    <?php echo esc_html(get_the_title($newer_post)); ?>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>

                </nav>
            <?php endif; ?>

        <?php endwhile; ?>

    </div>
</main>

<!-- Wave transition: beige main -> footer -->
<svg
    class="hero-wave -mt-px -mb-px block h-16 w-full rotate-180 text-beige md:h-24"
    viewBox="0 0 1440 100"
    preserveAspectRatio="none"
    aria-hidden="true">
    <path
        fill="currentColor"
        d="M0 40 Q 60 0 120 40 T 240 40 T 360 40 T 480 40 T 600 40 T 720 40 T 840 40 T 960 40 T 1080 40 T 1200 40 T 1320 40 T 1440 40 V100 H0 Z" />
</svg>

<?php get_footer(); ?>