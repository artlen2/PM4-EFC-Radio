</main>

   <footer class="border-t border-neutral-700 py-24" style="background-image: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.82)), url('https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/pape-bg.jpg'); background-size: cover; background-position: center;"> 
        
<!-- card footer container -->
    <div class="card-container relative bg-beige h-[500px] m-15 rounded-[40px] flex items-end p-6 border-t-2 border-x-2 border-b-8     border-black-400">

<!-- menu footer -->
   <nav class="flex flex-col gap-4 text-md  text-black absolute top-10 left-180 m-4">

     <ul class="flex flex-col gap-4 font-bold   text-black">
         <h3 class="  mb-2 text-orange text-[1.5rem]">Navigation</h3>
         <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Accueil</a></li>
         <li><a href="<?php echo esc_url( home_url( '/emissions/' ) ); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Émissions</a></li>
         <li><a href="<?php echo esc_url( home_url( '/evenements/' ) ); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Événements</a></li>
         <li><a href="<?php echo esc_url( home_url( '/nous-joindre/' ) ); ?>" class="hover:text-orange ease-in-out duration-200 text-[1.25rem]">Nous joindre</a></li>
     </ul>

    </nav>
    
      <!-- logos footer  -->
      <div class="flex flex-col items-left  w-[300px] h-100 m-4 absolute ">
          <img class="w-sm h-auto  " src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/Logo-horizontal-pop-lilas-scaled.png" alt="logo mauve" >

          <p class="text-lg text-black mt-4">
              La radio communautaire de La Matanie, par et pour les gens d’ici.
          </p>

          <!-- réseaux sociaux icons -->
         <div class="flex mt-8 justify-start items-center gap-4  ">
            <a href="https://www.facebook.com/radiolamatanie"><img class="w-8 h-8 hover:scale-110 ease-in-out duration-200" src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/facebook-app-symbol.png" alt="icon facebook" ></a>
            
             <a href=""><img class="w-8 h-8 hover:scale-110 ease-in-out duration-200" src="https://projet4.timmatane.ca/2026/efc/eq1/wp-content/uploads/2026/11/youtube.png" alt="icon youtube" ></a> 
            
          </div>
        </div>

        <!-- copyright -->
        <div class=" flex justify-between  items-end w-full m-4 mb-8 border-t-2 border-neutral-400 pt-8 ">
            <p class="text-sm text-black opacity-60">
                <?php bloginfo( 'name' ); ?>, tous droits réservés &copy; <?php echo esc_html( date( 'Y' ) ); ?>
            </p>

            <a href="https://projet4.timmatane.ca/2026/efc/eq1/confidentialite/"><p class="text-sm text-black opacity-60">
                politique de confidentialité
            </p>
        </div></a>
            
       
    </div>

   </footer>

    <?php wp_footer(); ?>
</body>
</html>
 