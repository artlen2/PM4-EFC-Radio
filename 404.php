<?php get_header(); ?>

<article class="flex flex-col items-center justify-center text-center gap-4 m-20">
    <h2 class="text-9xl font-bold mb-4 text-yellow-500 animate-bounce ease-in-out">404</h2>
    <p class="mb-4 text-lg">Désolé... Il n'y a rien ici.</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bg-violet hover:bg-yellow ease-in-out duration-300 text-white font-bold p-4 rounded-[30px] border-t-2 border-x-2 border-b-8 border-black">Revenir à la page d'accueil</a>
</article>

<?php get_footer(); ?>
