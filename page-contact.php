<?php
/* Template Name: Kontakt */
get_header();
?>

<section class="hero hero--contact">
    <canvas id="canvas"></canvas>
  <?php snowfall_render_hero_pan_content('contact', false); ?>
</section>

<main class="page-content contact-layout">

  <img
    class="contact-deco contact-deco--left"
    src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/berg-vänster.png' ); ?>"
    alt=""
    aria-hidden="true"
  />

  <img
    class="contact-deco contact-deco--right"
    src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/berg-höger.png' ); ?>"
    alt=""
    aria-hidden="true"
  />

<?php
$kontakt_cat = get_term_by('name', 'Kontakt', 'category');
$kontakt_cat_id = $kontakt_cat ? (int) $kontakt_cat->term_id : 0;

$contact_q = new WP_Query([
  'post_type'      => 'post',
  'posts_per_page' => 1,
  'post_status'    => 'publish',
  'cat'            => $kontakt_cat_id,
]);


  if ($contact_q->have_posts()) :
    while ($contact_q->have_posts()) : $contact_q->the_post(); ?>
      <article class="contact-card">
        <h1 class="contact-card__title"><?php the_title(); ?></h1>
        <div class="contact-card__content">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile;
    wp_reset_postdata();
  else : ?>
    <article class="contact-card">
      <h1 class="contact-card__title">Kontakt</h1>
      <div class="contact-card__content">
        <p>Skapa ett “Kontaktinlägg” i wp-admin för att visa innehåll här.</p>
      </div>
    </article>
  <?php endif; ?>

</main>

<?php get_footer(); ?>