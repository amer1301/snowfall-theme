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
