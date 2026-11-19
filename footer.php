</main>

<footer class="border-t border-neutral-700 py-10 md:py-24 px-4" style="background-image: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.82)), url('https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/pape-bg.jpg'); ">
  <!-- card footer container -->
  <div class="card-container bg-beige max-w-[1600px] mx-auto rounded-[24px] md:rounded-[40px] p-6 md:p-10 border-t-2 border-x-2 border-b-8 border-black lg:scale-115 ">

    <div class="grid gap-20 md:grid-cols-2 lg:grid-cols-4">

      <!-- logo + description + réseaux sociaux -->
      <div class="flex flex-col gap-4 text-center text-left">
        <img class="w-48 md:w-64 h-auto" src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/Logo-horizontal-pop-lilas-scaled.png" alt="logo mauve">

        <p class="text-lg text-black mt-4">
          La radio communautaire de La Matanie, par et pour les gens d’ici.
        </p>

        <div class="flex mt-6 items-center gap-4">
          <a href="https://www.facebook.com/radiolamatanie">
            <img class="w-8 h-8 hover:scale-110 ease-in-out duration-200" src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/facebook-app-symbol.png" alt="icon facebook">
          </a>
          <a href="">
            <img class="w-8 h-8 hover:scale-110 ease-in-out duration-200" src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/youtube.png" alt="icon youtube">
          </a>
        </div>
      </div>

      <!-- menu footer -->
      <nav class="text-black">
        <h3 class="mb-4 text-orange text-[1.5rem] font-bold archivo">Navigation</h3>
        <ul class="flex flex-col gap-4 font-bold">
          <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Accueil</a></li>
          <li><a href="<?php echo esc_url(home_url('/programmation/')); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Programmation</a></li>
          <li><a href="<?php echo esc_url(home_url('/communaute/')); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Communauté</a></li>
          <li><a href="<?php echo esc_url(home_url('/a-propos/')); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">À propos</a></li>
          <li><a href="<?php echo esc_url(home_url('/nous-soutenir/')); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Nous soutenir</a></li>
        </ul>
      </nav>

      <!-- contact -->
      <div class="flex flex-col gap-4 text-black">
        <h3 class="mb-0 text-orange text-[1.5rem] font-bold archivo">Contact</h3>
        <p class="text-lg">Matane (Québec)</p>
        <p class="text-lg">418 000-0000</p>
        <p class="text-lg break-words">courriel@radiolamatanie.ca</p>
      </div>

      <!-- en ondes -->
      <div class="flex flex-col gap-4 text-black">
        <h3 class="mb-0 text-orange text-[1.5rem] font-bold archivo">En ondes</h3>
        <p class="text-lg">Écoutez-nous en direct, partout dans La Matanie!</p>
        <!-- btn faire un don -->
        <a href="<?php echo esc_url(home_url('/')); ?>"
          class="bg-white text-black font-bold no-underline text-center p-4 w-full md:w-50  rounded-[30px] hover:scale-105 ease-in-out duration-300 border-t-2 border-x-2 border-b-8 border-black">
          Faire un don
        </a>
      </div>

    </div>

    <!-- copyright -->
    <div class="mt-10 pt-6 border-t-2 border-black opacity-40 flex flex-col sm:flex-row sm:justify-between gap-2">
      <p class="text-sm text-black ">
        <?php bloginfo('name'); ?>, tous droits réservés &copy; <?php echo esc_html(date('Y')); ?>
      </p>
      <a href="https://projet4.timmatane.ca/2026/efc/eq1/confidentialite/" class="text-sm text-black ">
        politique de confidentialité
      </a>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>