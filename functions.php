<?php
/**
 * Snowfall Adventures – Theme functions
 */

/* --------------------------------------------------
 * Enqueue styles & scripts
 * -------------------------------------------------- */
function snowfall_enqueue_assets() {

  // Theme stylesheet (style.css)
  wp_enqueue_style(
    'snowfall-style',
    get_stylesheet_uri(),
    [],
    wp_get_theme()->get('Version')
  );

  // Parallax / scroll-pan script
  wp_enqueue_script(
    'snowfall-parallax',
    get_template_directory_uri() . '/assets/js/parallax.js',
    [],
    wp_get_theme()->get('Version'),
    true // load in footer
  );
}
add_action('wp_enqueue_scripts', 'snowfall_enqueue_assets');


/* --------------------------------------------------
 * Theme support (bra att ha)
 * -------------------------------------------------- */
function snowfall_theme_setup() {

  // Dynamisk <title>
  add_theme_support('title-tag');

  // Menyer (för senare steg)
  register_nav_menus([
    'primary' => __('Primary Menu', 'snowfall'),
  ]);

  // Featured images (om du vill använda dem senare)
  add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'snowfall_theme_setup');

function snowfall_customize_register($wp_customize) {

  $wp_customize->add_section('snowfall_quotebar', [
    'title'    => 'Quote-bar (under hero)',
    'priority' => 35,
  ]);

  // Quote text
  $wp_customize->add_setting('snowfall_quote_text', [
    'default'           => 'En stillsam och mäktig vinterupplevelse. Guiderna var kunniga, trygga och gav oss minnen för livet.',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);

  $wp_customize->add_control('snowfall_quote_text', [
    'label'   => 'Citattext',
    'section' => 'snowfall_quotebar',
    'type'    => 'textarea',
  ]);

  // Quote author
  $wp_customize->add_setting('snowfall_quote_author', [
    'default'           => 'Deltagare på vintervandring',
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control('snowfall_quote_author', [
    'label'   => 'Avsändare',
    'section' => 'snowfall_quotebar',
    'type'    => 'text',
  ]);

  $wp_customize->add_section('snowfall_experiences', [
  'title'    => 'Våra upplevelser',
  'priority' => 36,
]);

// Heading
$wp_customize->add_setting('snowfall_exp_heading', [
  'default'           => 'Våra upplevelser',
  'sanitize_callback' => 'sanitize_text_field',
]);
$wp_customize->add_control('snowfall_exp_heading', [
  'label'   => 'Rubrik',
  'section' => 'snowfall_experiences',
  'type'    => 'text',
]);

// Card 1
$wp_customize->add_setting('snowfall_exp1_img', [
  'sanitize_callback' => 'absint',
]);
$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'snowfall_exp1_img', [
  'label'   => 'Bild (kort 1)',
  'section' => 'snowfall_experiences',
  'mime_type' => 'image',
]));

$wp_customize->add_setting('snowfall_exp1_title', [
  'default'           => 'Titel',
  'sanitize_callback' => 'sanitize_text_field',
]);
$wp_customize->add_control('snowfall_exp1_title', [
  'label'   => 'Titel (kort 1)',
  'section' => 'snowfall_experiences',
  'type'    => 'text',
]);

$wp_customize->add_setting('snowfall_exp1_text', [
  'default'           => 'Kort beskrivning...',
  'sanitize_callback' => 'sanitize_textarea_field',
]);
$wp_customize->add_control('snowfall_exp1_text', [
  'label'   => 'Text (kort 1)',
  'section' => 'snowfall_experiences',
  'type'    => 'textarea',
]);

$wp_customize->add_setting('snowfall_exp1_link', [
  'sanitize_callback' => 'esc_url_raw',
]);
$wp_customize->add_control('snowfall_exp1_link', [
  'label'       => 'Länk (kort 1) – klistra in URL till sidan',
  'section'     => 'snowfall_experiences',
  'type'        => 'url',
]);


// Card 2
$wp_customize->add_setting('snowfall_exp2_img', [
  'sanitize_callback' => 'absint',
]);
$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'snowfall_exp2_img', [
  'label'   => 'Bild (kort 2)',
  'section' => 'snowfall_experiences',
  'mime_type' => 'image',
]));

$wp_customize->add_setting('snowfall_exp2_title', [
  'default'           => 'Titel',
  'sanitize_callback' => 'sanitize_text_field',
]);
$wp_customize->add_control('snowfall_exp2_title', [
  'label'   => 'Titel (kort 2)',
  'section' => 'snowfall_experiences',
  'type'    => 'text',
]);

$wp_customize->add_setting('snowfall_exp2_text', [
  'default'           => 'Kort beskrivning...',
  'sanitize_callback' => 'sanitize_textarea_field',
]);
$wp_customize->add_control('snowfall_exp2_text', [
  'label'   => 'Text (kort 2)',
  'section' => 'snowfall_experiences',
  'type'    => 'textarea',
]);

$wp_customize->add_setting('snowfall_exp2_link', [
  'sanitize_callback' => 'esc_url_raw',
]);
$wp_customize->add_control('snowfall_exp2_link', [
  'label'       => 'Länk (kort 2) – klistra in URL till sidan',
  'section'     => 'snowfall_experiences',
  'type'        => 'url',
]);

  /* -------------------------------------------
   * Scroll-pan banner (fullbredd bild + content)
   * ------------------------------------------- */
  $wp_customize->add_section('snowfall_pan_banner', [
    'title'    => 'Bildsektion (scroll-pan)',
    'priority' => 36,
  ]);

  // Bild
  $wp_customize->add_setting('snowfall_pan_banner_image', [
    'default'           => '',
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

  // Titel
  $wp_customize->add_setting('snowfall_pan_banner_title', [
    'default'           => 'Våra upplevelser',
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control('snowfall_pan_banner_title', [
    'label'   => 'Titel',
    'section' => 'snowfall_pan_banner',
    'type'    => 'text',
  ]);

  // Text
  $wp_customize->add_setting('snowfall_pan_banner_text', [
    'default'           => 'Kort beskrivning...',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);

  $wp_customize->add_control('snowfall_pan_banner_text', [
    'label'   => 'Text',
    'section' => 'snowfall_pan_banner',
    'type'    => 'textarea',
  ]);

  // Knapptext
  $wp_customize->add_setting('snowfall_pan_banner_button_text', [
    'default'           => 'Knapp',
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control('snowfall_pan_banner_button_text', [
    'label'   => 'Knapptext',
    'section' => 'snowfall_pan_banner',
    'type'    => 'text',
  ]);

  // Knapp-länk
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

}
add_action('customize_register', 'snowfall_customize_register');