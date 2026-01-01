<?php
/* Template Name: Om */
get_header();
?>

<main id="main" role="main">

<section class="hero hero--about">
  <?php snowfall_render_hero_pan_content('about', false); ?>
</section>

<?php
$about_q = new WP_Query([
  'post_type'      => 'post',
  'posts_per_page' => 2,
  'post_status'    => 'publish',
  'category_name'  => 'om',
]);

if ($about_q->have_posts()) :
  $i = 0;
  while ($about_q->have_posts()) : $about_q->the_post();
    $i++;

    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');

    $classes = 'about-post';
    if ($i === 2) $classes .= ' about-post--reverse about-post--right';
    ?>
    <section class="<?php echo esc_attr($classes); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">

      <div class="about-post__media">
        <?php if ($img_url): ?>
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
        <?php endif; ?>
      </div>

      <div class="about-post__content">
        <h2 class="about-post__title"><?php the_title(); ?></h2>

        <img
          class="about-post__divider"
          src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/linje-berg.png'); ?>"
          alt=""
          aria-hidden="true"
        >

        <div class="about-post__text">
          <?php the_content(); ?>
        </div>
      </div>

    </section>
    <?php
  endwhile;
  wp_reset_postdata();
else : ?>
  <p>Skapa inlägg i kategorin “om” (med utvald bild) för att visa innehåll här.</p>
<?php endif; ?>

<img
  class="about-post__bottom-image"
  src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/berg.png'); ?>"
  alt=""
  aria-hidden="true"
/>

</main>

<?php get_footer(); ?>
