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
    </a>

    <nav class="menu" aria-label="Huvudmeny">
      <a href="#">Startsida</a>
      <a href="#">Undersida</a>
      <a href="#">Undersida</a>
      <a href="#">Undersida</a>
      <a href="#">Undersida</a>
    </nav>
  </div>
</header>