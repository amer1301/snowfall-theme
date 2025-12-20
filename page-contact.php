<?php
/* Template Name: Kontakt */
get_header();
?>

<section class="hero hero--contact">
    <canvas id="canvas"></canvas>
  <?php snowfall_render_hero_pan_content('contact', false); ?>
</section>

<main class="page-content">

  <?php
  while ( have_posts() ) :
    the_post();
    the_content();
  endwhile;
  ?>

  </main>

<?php get_footer(); ?>