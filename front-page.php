<?php
// Laddar in header.php (WordPress-templatens topp: <head>, meny osv.)
get_header();

// Hämtar citat från Customizer (theme_mod).
// trim + (string) ger stabilt tom-sträng-beteende även om värdet är null/annat.
$quote_text   = trim( (string) get_theme_mod('snowfall_quote_text') );
$quote_author = trim( (string) get_theme_mod('snowfall_quote_author') );
?>

<!-- HERO med sticky + bild som panoreras via JS -->
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

<?php if ( $quote_text !== '' || $quote_author !== '' ) : ?>
    <!-- Visar quote-bar endast om det finns text eller författare angivet i Customizer -->
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

<?php
// --- Upplevelser (Customizer) ---
// Rubrik med fallback-värde om inget är satt i Customizer
$exp_heading = trim((string) get_theme_mod('snowfall_exp_heading', 'Våra upplevelser'));

$exp1_img   = (int) get_theme_mod('snowfall_exp1_img');
$exp1_title = trim((string) get_theme_mod('snowfall_exp1_title'));
$exp1_text  = trim((string) get_theme_mod('snowfall_exp1_text'));
$exp1_link  = trim((string) get_theme_mod('snowfall_exp1_link'));

$exp2_img   = (int) get_theme_mod('snowfall_exp2_img');
$exp2_title = trim((string) get_theme_mod('snowfall_exp2_title'));
$exp2_text  = trim((string) get_theme_mod('snowfall_exp2_text'));
$exp2_link  = trim((string) get_theme_mod('snowfall_exp2_link'));

$exp1_img_url = $exp1_img ? wp_get_attachment_image_url($exp1_img, 'large') : '';
$exp2_img_url = $exp2_img ? wp_get_attachment_image_url($exp2_img, 'large') : '';
?>

<!-- Upplevelser-sektion -->
<section class="experiences" id="tours">
  <div class="experiences__inner">
    <header class="experiences__header">
      <h2 class="experiences__title"><?php echo esc_html($exp_heading); ?></h2>
      <span class="experiences__bar" aria-hidden="true"></span>
    </header>

    <div class="experiences__grid">

      <?php if ($exp1_title !== '' || $exp1_text !== '' || $exp1_img_url !== '') : ?>
      <article class="card">
        <?php if ($exp1_img_url) : ?>
          <div class="card__media">
            <img src="<?php echo esc_url($exp1_img_url); ?>" alt="" loading="lazy">
          </div>
        <?php endif; ?>

        <?php if ($exp1_title) : ?>
          <h3 class="card__title"><?php echo esc_html($exp1_title); ?></h3>
        <?php endif; ?>

        <?php if ($exp1_text) : ?>
          <p class="card__text"><?php echo nl2br(esc_html($exp1_text)); ?></p>
        <?php endif; ?>

        <?php if ($exp1_link) : ?>
          <a class="btn" href="<?php echo esc_url($exp1_link); ?>">Läs mer</a>
        <?php endif; ?>
      </article>
      <?php endif; ?>


      <?php if ($exp2_title !== '' || $exp2_text !== '' || $exp2_img_url !== '') : ?>
      <article class="card">
        <?php if ($exp2_img_url) : ?>
          <div class="card__media">
            <img src="<?php echo esc_url($exp2_img_url); ?>" alt="" loading="lazy">
          </div>
        <?php endif; ?>

        <?php if ($exp2_title) : ?>
          <h3 class="card__title"><?php echo esc_html($exp2_title); ?></h3>
        <?php endif; ?>

        <?php if ($exp2_text) : ?>
          <p class="card__text"><?php echo nl2br(esc_html($exp2_text)); ?></p>
        <?php endif; ?>

        <?php if ($exp2_link) : ?>
          <a class="btn" href="<?php echo esc_url($exp2_link); ?>">Läs mer</a>
        <?php endif; ?>
      </article>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php
// --- Scroll-pan banner (Customizer) ---
// Bilden är ett attachment-id (eller tomt). Vi renderar sektionen bara om vi har en URL.
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
// Laddar in footer.php (sidans avslut: footer, scripts via wp_footer, osv.)
get_footer();
