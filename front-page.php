<?php
get_header();

$quote_text   = trim( (string) get_theme_mod('snowfall_quote_text') );
$quote_author = trim( (string) get_theme_mod('snowfall_quote_author') );
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


<?php
get_footer();
