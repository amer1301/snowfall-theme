<?php
get_header();

$news_url = site_url('/nyheter/');
$puff_enabled  = (bool) get_theme_mod('snowfall_puff_enabled', false);
$puff_title    = trim((string) get_theme_mod('snowfall_puff_title', ''));
$puff_text     = trim((string) get_theme_mod('snowfall_puff_text', ''));
$puff_btn_text = trim((string) get_theme_mod('snowfall_puff_btn_text', ''));
$puff_btn_url  = trim((string) get_theme_mod('snowfall_puff_btn_url', ''));
$quote_text   = trim( (string) get_theme_mod('snowfall_quote_text') );
$quote_author = trim( (string) get_theme_mod('snowfall_quote_author') );
?>

<main id="main" role="main">

<section class="hero-pan" id="top">
  <div class="hero-pan__sticky">

    <div class="hero-pan__media" aria-hidden="true">
      <img
        class="hero-pan__img"
        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero.jpg'); ?>"
        alt=""
      >
      <div class="hero-pan__overlay" aria-hidden="true"></div>
    </div>
    <div class="hero-pan__content">
      <?php snowfall_render_hero_pan_content('front', true); ?>
    </div>
  </div>
</section>

<!-- Puff-framsida -->

<?php if ($puff_enabled && ($puff_title || $puff_text || $puff_btn_url)) : ?>
<section class="front-puff" aria-label="Viktig information">
  <div class="front-puff__inner">
    <div class="front-puff__copy">
      <?php if ($puff_title) : ?>
        <h2 class="front-puff__title"><?php echo esc_html($puff_title); ?></h2>
      <?php endif; ?>

      <?php if ($puff_text) : ?>
        <p class="front-puff__text"><?php echo nl2br(esc_html($puff_text)); ?></p>
      <?php endif; ?>
    </div>

    <?php if ($puff_btn_url && $puff_btn_text) : ?>
      <a class="btn front-puff__btn" href="<?php echo esc_url($puff_btn_url); ?>">
        <?php echo esc_html($puff_btn_text); ?>
      </a>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- Citat-sektion -->

<?php if ( $quote_text !== '' || $quote_author !== '' ) : ?>
<section class="quote-bar">
  <div class="quote-bar__inner">
    <span class="quote-bar__mark quote-bar__mark--left">“</span>

    <div class="quote-bar__content">
      <?php if ( $quote_text !== '' ) : ?>
        <p class="quote-bar__text"><?php echo nl2br( esc_html( $quote_text ) ); ?></p>
      <?php endif; ?>

      <?php if ( $quote_author !== '' ) : ?>
<p class="quote-bar__author">— <?php echo esc_html( $quote_author ); ?></p>
      <?php endif; ?>
    </div>

    <span class="quote-bar__mark quote-bar__mark--right">”</span>
  </div>
</section>
<?php endif; ?>

<!-- Upplevelser-sektion -->
<?php
$exp_q = new WP_Query([
  'post_type'           => 'post',
  'posts_per_page'      => -1,
  'category_name'       => 'upplevelser',
  'ignore_sticky_posts' => true,
]);

if ($exp_q->have_posts()) : ?>
<section class="exp-slider" aria-label="Våra upplevelser">
  <h2 class="exp-slider__title">Våra upplevelser</h2>

  <div class="exp-slider__wrap">
    <button class="exp-slider__arrow exp-slider__arrow--prev" aria-label="Föregående">‹</button>

    <div class="exp-slider__viewport">
      <div class="exp-slider__track">
        <?php while ($exp_q->have_posts()) : $exp_q->the_post(); ?>
          <article class="exp-slide">
            <div class="exp-slide__card">
              <?php if (has_post_thumbnail()) : ?>
                <div class="exp-slide__img">
                  <?php the_post_thumbnail('large'); ?>
                </div>
              <?php endif; ?>

              <div class="exp-slide__content">
                <h3 class="exp-slide__title"><?php the_title(); ?></h3>
                <p class="exp-slide__text"><?php echo esc_html(get_the_excerpt()); ?></p>
              </div>
            </div>
          </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>

    <button class="exp-slider__arrow exp-slider__arrow--next" aria-label="Nästa">›</button>
  </div>

  <div class="exp-slider__dots"></div>
</section>
<?php endif; ?>



<?php
// --- Scroll-pan banner ---
$pan_image_id = get_theme_mod('snowfall_pan_banner_image', '');
$pan_image_url = $pan_image_id ? wp_get_attachment_image_url($pan_image_id, 'full') : '';

$pan_title = get_theme_mod('snowfall_pan_banner_title', 'Våra upplevelser');
$pan_text  = get_theme_mod('snowfall_pan_banner_text', 'Kort beskrivning...');
$pan_btn_text = get_theme_mod('snowfall_pan_banner_button_text', 'Knapp');
$pan_btn_url  = get_theme_mod('snowfall_pan_banner_button_url', '');
?>

<?php if ($pan_image_url) : ?>
<section class="pan-banner" aria-label="<?php echo esc_attr($pan_title); ?>">
  <div class="pan-banner__sticky">
    <div class="pan-banner__media" aria-hidden="true">
      <img class="pan-banner__img" src="<?php echo esc_url($pan_image_url); ?>" alt="">
    </div>

    <div class="pan-banner__overlay" aria-hidden="true"></div>

    <div class="pan-banner__content">
      <h2 class="pan-banner__title"><?php echo esc_html($pan_title); ?></h2>
      <span class="pan-banner__line" aria-hidden="true"></span>

      <p class="pan-banner__text">
        <?php echo nl2br(esc_html($pan_text)); ?>
      </p>

      <?php if ($pan_btn_url) : ?>
        <a class="btn pan-banner__btn" href="<?php echo esc_url($pan_btn_url); ?>">
          <?php echo esc_html($pan_btn_text); ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
// --- nästa sektion - skrollbild ---
$next_title    = trim((string) get_theme_mod('snowfall_next_title', 'Lorem ipsum<br>&amp; dolor'));
$next_text     = trim((string) get_theme_mod('snowfall_next_text', 'Lorem ipsum dolor sit amet...'));
$next_btn_text = trim((string) get_theme_mod('snowfall_next_btn_text', 'Knapp'));
$next_btn_url  = trim((string) get_theme_mod('snowfall_next_btn_url', ''));

$next_img_1_id = (int) get_theme_mod('snowfall_next_img_1');
$next_img_2_id = (int) get_theme_mod('snowfall_next_img_2');
$next_img_3_id = (int) get_theme_mod('snowfall_next_img_3');

$next_img_1 = $next_img_1_id ? wp_get_attachment_image_url($next_img_1_id, 'large') : '';
$next_img_2 = $next_img_2_id ? wp_get_attachment_image_url($next_img_2_id, 'large') : '';
$next_img_3 = $next_img_3_id ? wp_get_attachment_image_url($next_img_3_id, 'large') : '';

$has_next =
  ($next_title !== '' || $next_text !== '' || $next_btn_url !== '' || $next_img_1 || $next_img_2 || $next_img_3);
?>

// --- Split-media ---

<?php if ($has_next) : ?>
<section class="split-media" aria-label="<?php echo esc_attr( wp_strip_all_tags($next_title) ); ?>">
  <div class="split-media__inner">

    <div class="split-media__copy">
      <?php if ($next_title !== '') : ?>
        <h2 class="split-media__title"><?php echo wp_kses($next_title, ['br' => []]); ?></h2>
      <?php endif; ?>

      <span class="split-media__line" aria-hidden="true"></span>

      <?php if ($next_text !== '') : ?>
        <p class="split-media__text"><?php echo nl2br(esc_html($next_text)); ?></p>
      <?php endif; ?>

      <?php if ($next_btn_url !== '' && $next_btn_text !== '') : ?>
        <a class="btn split-media__btn" href="<?php echo esc_url($next_btn_url); ?>">
          <?php echo esc_html($next_btn_text); ?>
        </a>
      <?php endif; ?>
    </div>

    <div class="split-media__media" aria-hidden="true">
  <div class="split-media__grid">
    <?php if ($next_img_1) : ?>
      <div class="split-media__img split-media__img--small split-media__img--a">
        <img src="<?php echo esc_url($next_img_1); ?>" alt="" loading="lazy">
      </div>
    <?php endif; ?>

    <?php if ($next_img_2) : ?>
      <div class="split-media__img split-media__img--small split-media__img--b">
        <img src="<?php echo esc_url($next_img_2); ?>" alt="" loading="lazy">
      </div>
    <?php endif; ?>

    <?php if ($next_img_3) : ?>
      <div class="split-media__img split-media__img--tall split-media__img--c">
        <img src="<?php echo esc_url($next_img_3); ?>" alt="" loading="lazy">
      </div>
    <?php endif; ?>
  </div>
</div>
  </div>
</section>
<?php endif; ?>


<?php
// --- Nyhetssektion ---
$news_heading = 'Nyheter';


$featured_count = 3;
$sidebar_count  = 6;

$featured_q = new WP_Query([
  'post_type'           => 'post',
  'posts_per_page'      => $featured_count,
  'ignore_sticky_posts' => true,
  'category_name'       => 'nyheter',
]);

$featured_ids = [];

if ($featured_q->have_posts()) :
?>
<section class="news" id="news" aria-label="<?php echo esc_attr($news_heading); ?>">
  <div class="news__inner">

<header class="news__header">
  <h2 class="news__title"><?php echo esc_html($news_heading); ?></h2>
  <span class="news__line" aria-hidden="true"></span>
</header>

    <div class="news__layout">

      <section class="news-hero" aria-label="Senaste nyheter">
        <button class="news-hero__arrow news-hero__arrow--prev" aria-label="Föregående">‹</button>

        <div class="news-hero__viewport">
          <div class="news-hero__track">
            <?php while ($featured_q->have_posts()) : $featured_q->the_post();
              $featured_ids[] = get_the_ID();
              $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
              $date_text = get_the_date();
              $excerpt   = get_the_excerpt();
            ?>
              <article class="news-hero__slide">
                <a class="news-hero__link" href="<?php echo esc_url($news_url); ?>"> 
                  <div class="news-hero__media" aria-hidden="true">
                    <?php if ($thumb_url) : ?>
                      <img class="news-hero__img" src="<?php echo esc_url($thumb_url); ?>" alt="" loading="lazy">
                    <?php endif; ?>
                  </div>

                  <div class="news-hero__content">
                    <h3 class="news-hero__title"><?php the_title(); ?></h3>
                    <p class="news-hero__date"><?php echo esc_html($date_text); ?></p>
                    <?php if ($excerpt) : ?>
                      <p class="news-hero__text"><?php echo esc_html($excerpt); ?></p>
                    <?php endif; ?>
                    <span class="btn news-hero__btn">Läs mer</span>
                  </div>
                </a>
              </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>

        <button class="news-hero__arrow news-hero__arrow--next" aria-label="Nästa">›</button>

        <div class="news-hero__dots" aria-hidden="false"></div>
       </section>

      <?php
      $sidebar_q = new WP_Query([
        'post_type'           => 'post',
        'posts_per_page'      => $sidebar_count,
        'ignore_sticky_posts' => true,
        'category_name'       => 'nyheter',
        'post__not_in'        => $featured_ids,
      ]);

      if ($sidebar_q->have_posts()) :
      ?>
      <aside class="news-side" aria-label="Tidigare nyheter">
        <h3 class="news-side__title">Tidigare nyheter</h3>

<ul class="news-side__list">
  <?php while ($sidebar_q->have_posts()) : $sidebar_q->the_post();
    $side_thumb = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
  ?>
<li class="news-side__item">
  <a class="news-side__link" href="<?php echo esc_url($news_url); ?>">
        <span class="news-side__thumb" aria-hidden="true">
          <?php if ($side_thumb) : ?>
            <img src="<?php echo esc_url($side_thumb); ?>" alt="" loading="lazy">
          <?php else : ?>
            <span class="news-side__thumb--placeholder"></span>
          <?php endif; ?>
        </span>

        <span class="news-side__meta">
          <span class="news-side__item-title"><?php the_title(); ?></span>
          <span class="news-side__item-date"><?php echo esc_html(get_the_date()); ?></span>
        </span>
      </a>
    </li>
  <?php endwhile; ?>
</ul>

<a class="btn news-side__all" href="<?php echo esc_url($news_url); ?>">
  Visa alla nyheter
</a>
      </aside>
      <?php wp_reset_postdata(); endif; ?>

    </div>
  </div>
</section>
<?php endif; ?>


<?php
get_footer();
