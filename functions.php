<?php
/**
 * Snowfall Adventures – Theme functions
 * - Theme setup (supports + menus)
 * - Enqueue CSS/JS
 * - Customizer fields
 */


/* --------------------------------------------------
 * Theme setup
 * -------------------------------------------------- */
add_action('after_setup_theme', function () {

  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');

  register_nav_menus([
    'primary' => __('Huvudmeny', 'snowfall-theme'),
  ]);

});


/* --------------------------------------------------
 * Enqueue styles & scripts
 * -------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {

  wp_enqueue_style(
    'snowfall-style',
    get_stylesheet_uri(),
    [],
    wp_get_theme()->get('Version')
  );

  wp_enqueue_script(
    'snowfall-parallax',
    get_template_directory_uri() . '/assets/js/parallax.js',
    [],
    wp_get_theme()->get('Version'),
    true
  );

  wp_enqueue_script(
    'snowfall-exp-slider',
    get_template_directory_uri() . '/assets/js/exp-slider.js',
    [],
    wp_get_theme()->get('Version'),
    true
  );

  wp_enqueue_script(
    'snowfall-news-slider',
    get_template_directory_uri() . '/assets/js/news-slider.js',
    [],
    wp_get_theme()->get('Version'),
    true
  );

});


/* --------------------------------------------------
 * Helper: Hero-text (front + booking) – hämtar olika settings
 * -------------------------------------------------- */
function snowfall_get_hero_settings(string $context = 'front'): array {

  if ($context === 'booking') {
    return [
      'eyebrow' => trim((string) get_theme_mod('snowfall_booking_eyebrow', 'FJÄLLNÄRA NATURUPPLEVELSER')),
      'title'   => trim((string) get_theme_mod('snowfall_booking_title', 'Boka boende redan<br>idag')),
      'btn1_text' => '',
      'btn1_url'  => '',
      'btn2_text' => '',
      'btn2_url'  => '',
    ];
  }

  // default: front
  return [
    'eyebrow' => trim((string) get_theme_mod('snowfall_front_eyebrow', 'FJÄLLNÄRA NATURUPPLEVELSER')),
    'title'   => trim((string) get_theme_mod('snowfall_front_title', "Guidade vinterturer i<br>fjällen")),
    'btn1_text' => trim((string) get_theme_mod('snowfall_front_btn1_text', 'Se våra upplevelser')),
    'btn1_url'  => trim((string) get_theme_mod('snowfall_front_btn1_url', '#tours')),
    'btn2_text' => trim((string) get_theme_mod('snowfall_front_btn2_text', 'Kontakta oss')),
    'btn2_url'  => trim((string) get_theme_mod('snowfall_front_btn2_url', '#contact')),
  ];
}

/**
 * Renderar hero-pan__content.
 * $context: 'front' eller 'booking'
 * $show_buttons: true/false (t.ex. false på booking)
 */
function snowfall_render_hero_pan_content(string $context = 'front', bool $show_buttons = true): void {

  $s = snowfall_get_hero_settings($context);

  $eyebrow = $s['eyebrow'];
  $title   = $s['title'];

  if ($eyebrow === '' && $title === '') {
    return;
  }
  ?>
  <div class="hero-pan__content">
    <?php if ($eyebrow !== '') : ?>
      <p class="hero-pan__eyebrow"><?php echo esc_html($eyebrow); ?></p>
    <?php endif; ?>

    <?php if ($title !== '') : ?>
      <h1><?php echo wp_kses($title, ['br' => []]); ?></h1>
    <?php endif; ?>

    <?php if ($show_buttons) : ?>
      <?php
        $btn1_text = $s['btn1_text'];
        $btn1_url  = $s['btn1_url'];
        $btn2_text = $s['btn2_text'];
        $btn2_url  = $s['btn2_url'];
      ?>
      <?php if (($btn1_text && $btn1_url) || ($btn2_text && $btn2_url)) : ?>
        <div class="hero-pan__cta">
          <?php if ($btn1_text && $btn1_url) : ?>
            <a class="btn" href="<?php echo esc_url($btn1_url); ?>"><?php echo esc_html($btn1_text); ?></a>
          <?php endif; ?>

          <?php if ($btn2_text && $btn2_url) : ?>
            <a class="btn" href="<?php echo esc_url($btn2_url); ?>"><?php echo esc_html($btn2_text); ?></a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  <?php
}


/* --------------------------------------------------
 * Customizer
 * -------------------------------------------------- */
add_action('customize_register', function($wp_customize) {

  /* ---------------------------
   * HERO – Startsida
   * --------------------------- */
  $wp_customize->add_section('snowfall_hero_front', [
    'title'    => 'Hero – Startsida',
    'priority' => 25,
  ]);

  $wp_customize->add_setting('snowfall_front_eyebrow', [
    'default'           => 'FJÄLLNÄRA NATURUPPLEVELSER',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_front_eyebrow', [
    'section' => 'snowfall_hero_front',
    'label'   => 'Överrubrik (eyebrow)',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_front_title', [
    'default'           => "Guidade vinterturer i<br>fjällen",
    'sanitize_callback' => function($value) {
      return wp_kses((string) $value, ['br' => []]);
    },
  ]);
  $wp_customize->add_control('snowfall_front_title', [
    'section'     => 'snowfall_hero_front',
    'label'       => 'Rubrik (du kan använda <br>)',
    'type'        => 'textarea',
  ]);

  $wp_customize->add_setting('snowfall_front_btn1_text', [
    'default'           => 'Se våra upplevelser',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_front_btn1_text', [
    'section' => 'snowfall_hero_front',
    'label'   => 'Knapp 1 – text',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_front_btn1_url', [
    'default'           => '#tours',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('snowfall_front_btn1_url', [
    'section'     => 'snowfall_hero_front',
    'label'       => 'Knapp 1 – länk (URL)',
    'type'        => 'url',
    'description' => 'Ex: /aktiviteter/ eller #tours',
  ]);

  $wp_customize->add_setting('snowfall_front_btn2_text', [
    'default'           => 'Kontakta oss',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_front_btn2_text', [
    'section' => 'snowfall_hero_front',
    'label'   => 'Knapp 2 – text',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_front_btn2_url', [
    'default'           => '#contact',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('snowfall_front_btn2_url', [
    'section'     => 'snowfall_hero_front',
    'label'       => 'Knapp 2 – länk (URL)',
    'type'        => 'url',
    'description' => 'Ex: /kontakt/ eller #contact',
  ]);


  /* ---------------------------
   * HERO – Bokningssida
   * --------------------------- */
  $wp_customize->add_section('snowfall_hero_booking', [
    'title'    => 'Hero – Bokningssida',
    'priority' => 26,
  ]);

  $wp_customize->add_setting('snowfall_booking_eyebrow', [
    'default'           => 'FJÄLLNÄRA NATURUPPLEVELSER',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_booking_eyebrow', [
    'section' => 'snowfall_hero_booking',
    'label'   => 'Överrubrik (eyebrow)',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_booking_title', [
    'default'           => 'Boka boende redan<br>idag',
    'sanitize_callback' => function($value) {
      return wp_kses((string) $value, ['br' => []]);
    },
  ]);
  $wp_customize->add_control('snowfall_booking_title', [
    'section'     => 'snowfall_hero_booking',
    'label'       => 'Rubrik (du kan använda <br>)',
    'type'        => 'textarea',
  ]);


  /* -------------------------------------------
   * Quote-bar (under hero)
   * ------------------------------------------- */
  $wp_customize->add_section('snowfall_quotebar', [
    'title'    => 'Quote-bar (under hero)',
    'priority' => 35,
  ]);

  $wp_customize->add_setting('snowfall_quote_text', [
    'default'           => 'En stillsam och mäktig vinterupplevelse. Guiderna var kunniga, trygga och gav oss minnen för livet.',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);
  $wp_customize->add_control('snowfall_quote_text', [
    'label'   => 'Citattext',
    'section' => 'snowfall_quotebar',
    'type'    => 'textarea',
  ]);

  $wp_customize->add_setting('snowfall_quote_author', [
    'default'           => 'Deltagare på vintervandring',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_quote_author', [
    'label'   => 'Avsändare',
    'section' => 'snowfall_quotebar',
    'type'    => 'text',
  ]);


  /* -------------------------------------------
   * Scroll-pan banner (fullbredd bild + content)
   * ------------------------------------------- */
  $wp_customize->add_section('snowfall_pan_banner', [
    'title'    => 'Bildsektion (scroll-pan)',
    'priority' => 37,
  ]);

  $wp_customize->add_setting('snowfall_pan_banner_image', [
    'default'           => 0,
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control(new WP_Customize_Media_Control(
    $wp_customize,
    'snowfall_pan_banner_image',
    [
      'label'     => 'Bild',
      'section'   => 'snowfall_pan_banner',
      'mime_type' => 'image',
    ]
  ));

  $wp_customize->add_setting('snowfall_pan_banner_title', [
    'default'           => 'Våra upplevelser',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_pan_banner_title', [
    'label'   => 'Titel',
    'section' => 'snowfall_pan_banner',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_pan_banner_text', [
    'default'           => 'Kort beskrivning...',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);
  $wp_customize->add_control('snowfall_pan_banner_text', [
    'label'   => 'Text',
    'section' => 'snowfall_pan_banner',
    'type'    => 'textarea',
  ]);

  $wp_customize->add_setting('snowfall_pan_banner_button_text', [
    'default'           => 'Knapp',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_pan_banner_button_text', [
    'label'   => 'Knapptext',
    'section' => 'snowfall_pan_banner',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_pan_banner_button_url', [
    'default'           => '',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('snowfall_pan_banner_button_url', [
    'label'       => 'Knapp-länk (URL)',
    'section'     => 'snowfall_pan_banner',
    'type'        => 'url',
    'description' => 'Ex: https://... eller /undersida/',
  ]);


  /* -------------------------------------------
   * Split media-sektion (titel/text/knapp + 3 bilder)
   * ------------------------------------------- */
  $wp_customize->add_section('snowfall_next_section', [
    'title'    => 'Nästa sektion (split media)',
    'priority' => 38,
  ]);

  $wp_customize->add_setting('snowfall_next_title', [
    'default'           => 'Lorem ipsum<br>&amp; dolor',
    'sanitize_callback' => function($value) {
      return wp_kses((string) $value, ['br' => []]);
    },
  ]);
  $wp_customize->add_control('snowfall_next_title', [
    'section'     => 'snowfall_next_section',
    'label'       => 'Titel',
    'type'        => 'text',
    'description' => 'Tips: använd <br> för radbrytning.',
  ]);

  $wp_customize->add_setting('snowfall_next_text', [
    'default'           => '',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);
  $wp_customize->add_control('snowfall_next_text', [
    'section' => 'snowfall_next_section',
    'label'   => 'Text',
    'type'    => 'textarea',
  ]);

  $wp_customize->add_setting('snowfall_next_btn_text', [
    'default'           => 'Knapp',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_next_btn_text', [
    'section' => 'snowfall_next_section',
    'label'   => 'Knapptext',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_next_btn_url', [
    'default'           => '',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('snowfall_next_btn_url', [
    'section'     => 'snowfall_next_section',
    'label'       => 'Knapp-länk (URL)',
    'type'        => 'url',
    'description' => 'Ex: https://... eller /undersida/',
  ]);

  foreach ([1, 2, 3] as $i) {
    $setting_id = "snowfall_next_img_{$i}";

    $wp_customize->add_setting($setting_id, [
      'default'           => 0,
      'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control(
      $wp_customize,
      $setting_id,
      [
        'section'   => 'snowfall_next_section',
        'label'     => "Bild {$i}",
        'mime_type' => 'image',
      ]
    ));
  }

});
