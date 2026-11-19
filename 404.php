<?php get_header(); ?>
      <main class="container mx-auto px-4 py-12 bg-beige">

<article class="flex flex-col items-center justify-center text-center gap-4 m-20 relative">
    <img src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/micro.png" alt="micro vintage" class="w-40 h-40 mb-4 rotate-[10deg] absolute top-54 left-[52%] translate-x-10">
    <h2 class="text-9xl font-bold mb-4 text-yellow-500 animate-bounce ease-in-out">404</h2>
    <p class="mb-4 text-lg">Désolé... Il n'y a rien ici.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class=" m-10 bg-violet hover:bg-yellow ease-in-out duration-300 text-white font-bold p-4 rounded-[30px] border-t-2 border-x-2 border-b-8 border-black">Revenir à la page d'accueil</a>
</article>

<?php get_footer(); ?>