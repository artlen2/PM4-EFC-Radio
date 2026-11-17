

<?php
get_header();









while (have_posts()) : the_post(); ?>
<div class="prog-page">
    <section class  ="prog-section">
        <h1 class="prog-title"><?php the_title(); ?></h1>
        <div class="prog-content">
            <?php the_content(); ?>
        </div>
    </section>
    </div>
<?php endwhile; ?>

<?php get_footer(); ?>

