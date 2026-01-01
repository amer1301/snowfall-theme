<?php
/* Template Name: Booking */
get_header();
?>

<section class="hero hero--booking">
  <div class="hero__top" aria-hidden="true"></div>

<?php snowfall_render_hero_pan_content('booking', false); ?>

  <div class="hero__forest" aria-hidden="true">
    <img
      src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/header.png'); ?>"
      alt=""
      class="hero__bottom"
    >
  </div>
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