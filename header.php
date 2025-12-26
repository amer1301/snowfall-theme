<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="site-header">
  <div class="nav">
<a class="logo" href="<?php echo esc_url(home_url('/')); ?>">
  Snowfall Adventures
  <img
    src="<?php echo get_template_directory_uri(); ?>/assets/images/pinetree.png"
    alt=""
    class="logo__icon"
  >
</a>
<button class="menu-toggle"
        type="button"
        aria-controls="primary-menu"
        aria-expanded="false">
  <span class="menu-toggle__bar"></span>
  <span class="menu-toggle__bar"></span>
  <span class="menu-toggle__bar"></span>
</button>

<nav class="menu" id="primary-menu" aria-label="Huvudmeny">
  <?php
  wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'menu__list',
    'fallback_cb'    => false,
  ]);
  ?>
</nav>
  </div>
</header>