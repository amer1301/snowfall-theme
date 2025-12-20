<?php
/* Template Name: Aktiviteter */
get_header();
?>

<section class="hero hero--activities">
  <?php snowfall_render_hero_pan_content('activities', false); ?>
</section>

<main class="page-content">

  <?php
  while ( have_posts() ) :
    the_post();
    the_content();
  endwhile;
  ?>

  <?php
  $q = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 50,
    'category_name'  => 'aktiviteter',
    'post_status'    => 'publish',
  ]);

  if ( $q->have_posts() ) : ?>
    <section class="activities-hex" aria-label="Aktiviteter">
      <?php while ( $q->have_posts() ) : $q->the_post();
        $id = get_the_ID();
        $img = get_the_post_thumbnail_url($id, 'large');
        if (!$img) $img = '';
      ?>
        <article class="hex-card">
          <div class="hex" style="--hex-bg: url('<?php echo esc_url($img); ?>');">
            <div class="hex__overlay">
              <h2 class="hex__title"><?php the_title(); ?></h2>

              <button
                class="hex__btn js-activity-open"
                type="button"
                data-activity-id="<?php echo esc_attr($id); ?>"
              >
                Läs mer
              </button>
            </div>
          </div>

<template class="js-activity-data" data-activity-id="<?php echo esc_attr($id); ?>">
  <div class="activity-detail__inner">
    <div class="activity-detail__media">
      <?php if ($img): ?>
        <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
      <?php endif; ?>

      <div class="activity-detail__content">
        <h3><?php the_title(); ?></h3>
        <div class="activity-detail__text">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </div>
</template>
        </article>
      <?php endwhile; ?>
    </section>

<section class="activity-detail" id="activity-detail" aria-live="polite"></section>

<?php else: ?>
  <p>Inga aktiviteter hittades ännu.</p>
<?php endif; ?>


</main>

<?php get_footer(); ?>
