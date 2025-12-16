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
}
add_action('customize_register', 'snowfall_customize_register');