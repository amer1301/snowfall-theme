<?php
get_header();
?>

<section class="hero-pan" id="top">
  <div class="hero-pan__sticky">
    <div class="hero-pan__media" aria-hidden="true">
      <img class="hero-pan__img"
           src="<?php echo get_template_directory_uri(); ?>/assets/images/hero.jpg"
           alt="">
    </div>

    <div class="hero-pan__overlay" aria-hidden="true"></div>

    <div class="hero-pan__content">
      <p class="hero-pan__eyebrow">FJÄLLNÄRA NATURUPPLEVELSER</p>
      <h1>Guidade vinterturer i<br>fjällen</h1>

      <div class="hero-pan__cta">
        <a class="btn" href="#tours">Se våra upplevelser</a>
        <a class="btn" href="#contact">Kontakta oss</a>
      </div>
    </div>
  </div>
</section>


<?php
get_footer();
