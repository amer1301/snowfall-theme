<?php
/* Template Name: Nyheter */
get_header();
?>

<section class="hero hero--news">
  <?php snowfall_render_hero_pan_content('news', false); ?>
</section>

<main class="page-content news-page">

  <?php
  // Välj nyhet via query param: /nyheter/?news=slug
  $selected_slug = isset($_GET['news']) ? sanitize_title(wp_unslash($_GET['news'])) : '';
  $selected_post = null;

  if ($selected_slug) {
    $selected_post = get_page_by_path($selected_slug, OBJECT, 'post');
    if ($selected_post && !has_category('nyheter', $selected_post)) {
      $selected_post = null; // säkerställ att vi bara visar från kategorin "nyheter"
    }
  }
  ?>

  <header class="news-header">
    <h1 class="news-header__title"><?php the_title(); ?></h1>
  </header>

  <div class="news-layout">

    <section class="news-main" aria-label="Nyheter">

      <?php
      // 1) Om användaren valt en nyhet i sidbaren: visa den överst
      if ($selected_post) :
        $selected_id = (int) $selected_post->ID;
        setup_postdata($GLOBALS['post'] = get_post($selected_id));
        $sel_img = get_the_post_thumbnail_url($selected_id, 'large');
      ?>
        <article class="news-feature">
          <div class="news-feature__meta">
            <time class="news-date" datetime="<?php echo esc_attr(get_the_date('c', $selected_id)); ?>">
              <?php echo esc_html(get_the_date('', $selected_id)); ?>
            </time>
            <span class="news-chip">Vald nyhet</span>
          </div>

          <h2 class="news-feature__title"><?php echo esc_html(get_the_title($selected_id)); ?></h2>

          <?php if ($sel_img): ?>
            <img class="news-feature__image" src="<?php echo esc_url($sel_img); ?>" alt="<?php echo esc_attr(get_the_title($selected_id)); ?>">
          <?php endif; ?>

          <div class="news-feature__content">
            <?php echo apply_filters('the_content', get_post_field('post_content', $selected_id)); ?>
          </div>
        </article>
        <?php wp_reset_postdata(); ?>
      <?php
      else:
        $selected_id = 0;
      endif;
      ?>

      <?php
      // 2) Visa 3 nyheter (hela innehållet). Exkludera vald nyhet om den visas.
      $main_q = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'category_name'  => 'nyheter',
        'post__not_in'   => $selected_id ? [$selected_id] : [],
      ]);

      if ($main_q->have_posts()) :
        while ($main_q->have_posts()) : $main_q->the_post();
          $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
      ?>
          <article class="news-item" id="news-<?php the_ID(); ?>">
            <div class="news-item__meta">
              <time class="news-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
              </time>
            </div>

            <h2 class="news-item__title"><?php the_title(); ?></h2>

            <?php if ($img): ?>
              <img class="news-item__image" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
            <?php endif; ?>

            <div class="news-item__content">
              <?php the_content(); ?>
            </div>
          </article>
      <?php
        endwhile;
        wp_reset_postdata();
      else:
      ?>
        <p class="news-empty">
          Inga nyheter ännu. Skapa ett inlägg i kategorin <strong>“nyheter”</strong>.
        </p>
      <?php endif; ?>

    </section>

    <aside class="news-sidebar" aria-label="Tidigare nyheter">
      <div class="news-sidebar__card">
        <h3 class="news-sidebar__title">Tidigare nyheter</h3>

        <?php
        // Sidebar: fler nyheter att klicka på.
        // Vi tar fler än 3 så det faktiskt finns "tidigare".
        $side_q = new WP_Query([
          'post_type'      => 'post',
          'post_status'    => 'publish',
          'posts_per_page' => 20,
          'category_name'  => 'nyheter',
          'post__not_in'   => $selected_id ? [$selected_id] : [],
        ]);

        if ($side_q->have_posts()) : ?>
          <ul class="news-list">
            <?php while ($side_q->have_posts()) : $side_q->the_post();
              $is_active = ($selected_slug && $selected_slug === get_post_field('post_name', get_the_ID()));
              $url = add_query_arg('news', get_post_field('post_name', get_the_ID()), get_permalink(get_queried_object_id()));
            ?>
              <li class="news-list__item">
                <a class="news-list__link <?php echo $is_active ? 'is-active' : ''; ?>" href="<?php echo esc_url($url); ?>">
                  <span class="news-list__title"><?php the_title(); ?></span>
                  <time class="news-list__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date('Y-m-d')); ?>
                  </time>
                </a>
              </li>
            <?php endwhile; ?>
          </ul>
          <?php wp_reset_postdata(); ?>
        <?php else: ?>
          <p class="news-sidebar__empty">Inga tidigare nyheter ännu.</p>
        <?php endif; ?>
      </div>
    </aside>
    <img
  class="news-decor"
  src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/granar.png'); ?>"
  alt=""
  aria-hidden="true"
/>
  </div>

</main>

<?php get_footer(); ?>
