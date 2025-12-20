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

  wp_enqueue_script(
    'snowfall-bookingbar',
    get_template_directory_uri() . '/assets/js/bookingbar.js',
    [],
    wp_get_theme()->get('Version'),
    true
  );

  if (is_page_template('page-activities.php')) {
    wp_enqueue_script(
      'snowfall-activities',
      get_template_directory_uri() . '/assets/js/activities.js',
      [],
      wp_get_theme()->get('Version'),
      true
    );
  }

   if (is_page_template('page-contact.php')) {
    wp_enqueue_script(
      'snowfall-snow',
      get_template_directory_uri() . '/assets/js/snow.js',
      [],
      wp_get_theme()->get('Version'),
      true
    );
  }
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
$wp_customize->add_section('snowfall_bookingbar_labels', [
  'title'    => 'Bookingbar – Texter',
  'priority' => 28,
]);

$labels = [
  'rooms'  => ['Rum', 'Label:'],
  'guests' => ['Gäster', 'Label:'],
  'from'   => ['Från', 'Label: Från'],
  'to'     => ['Till', 'Label: Till'],
];

foreach ($labels as $key => [$default, $label]) {
  $setting_id = "snowfall_bookingbar_label_{$key}";

  $wp_customize->add_setting($setting_id, [
    'default'           => $default,
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control($setting_id, [
    'section' => 'snowfall_bookingbar_labels',
    'label'   => $label,
    'type'    => 'text',
  ]);
}
$wp_customize->add_section('snowfall_bookingbar_settings', [
  'title'    => 'Bookingbar – Inställningar',
  'priority' => 29,
]);

$tab_defaults = ['Turer', 'Boende', 'Restaurang'];
for ($i = 1; $i <= 4; $i++) {
  $id = "snowfall_bookingbar_tab_{$i}";

  $wp_customize->add_setting($id, [
    'default'           => $tab_defaults[$i-1],
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control($id, [
    'section' => 'snowfall_bookingbar_settings',
    'label'   => "Tab {$i} – rubrik",
    'type'    => 'text',
  ]);
}
$wp_customize->add_setting('snowfall_bookingbar_button_text', [
  'default'           => 'Hitta tillgänglighet',
  'sanitize_callback' => 'sanitize_text_field',
]);
$wp_customize->add_control('snowfall_bookingbar_button_text', [
  'section' => 'snowfall_bookingbar_settings',
  'label'   => 'Knapptext',
  'type'    => 'text',
]);

$wp_customize->add_setting('snowfall_bookingbar_active', [
  'default'           => 0,
  'sanitize_callback' => 'absint',
]);
$wp_customize->add_control('snowfall_bookingbar_active', [
  'section' => 'snowfall_bookingbar_settings',
  'label'   => 'Default vald tab (0 = första)',
  'type'    => 'number',
]);

$wp_customize->add_setting('snowfall_bookingbar_rooms_min', [
  'default'           => 1,
  'sanitize_callback' => 'absint',
]);
$wp_customize->add_control('snowfall_bookingbar_rooms_min', [
  'section' => 'snowfall_bookingbar_settings',
  'label'   => 'Rum – min',
  'type'    => 'number',
]);

$wp_customize->add_setting('snowfall_bookingbar_rooms_max', [
  'default'           => 6,
  'sanitize_callback' => 'absint',
]);
$wp_customize->add_control('snowfall_bookingbar_rooms_max', [
  'section' => 'snowfall_bookingbar_settings',
  'label'   => 'Rum – max',
  'type'    => 'number',
]);

$wp_customize->add_setting('snowfall_bookingbar_guests_min', [
  'default'           => 1,
  'sanitize_callback' => 'absint',
]);
$wp_customize->add_control('snowfall_bookingbar_guests_min', [
  'section' => 'snowfall_bookingbar_settings',
  'label'   => 'Gäster – min',
  'type'    => 'number',
]);

$wp_customize->add_setting('snowfall_bookingbar_guests_max', [
  'default'           => 10,
  'sanitize_callback' => 'absint',
]);
$wp_customize->add_control('snowfall_bookingbar_guests_max', [
  'section' => 'snowfall_bookingbar_settings',
  'label'   => 'Gäster – max',
  'type'    => 'number',
]);
  $wp_customize->add_section('snowfall_booking_cards', [
    'title'    => 'Booking – Bildkort (över kalendern)',
    'priority' => 31,
  ]);

  $wp_customize->add_setting('snowfall_booking_cards_title', [
    'default'           => 'STARTA DITT ÄVENTYR',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('snowfall_booking_cards_title', [
    'section' => 'snowfall_booking_cards',
    'label'   => 'Rubrik',
    'type'    => 'text',
  ]);

  $wp_customize->add_setting('snowfall_booking_cards_subtitle', [
    'default'           => 'Välj kategori nedan för att se vad som finns tillgängligt.',
    'sanitize_callback' => 'sanitize_textarea_field',
  ]);
  $wp_customize->add_control('snowfall_booking_cards_subtitle', [
    'section' => 'snowfall_booking_cards',
    'label'   => 'Underrubrik',
    'type'    => 'textarea',
  ]);

for ($i = 1; $i <= 3; $i++) {

  $wp_customize->add_setting("snowfall_booking_card_{$i}_image", [
    'default'           => 0,
    'sanitize_callback' => 'absint',
  ]);

  $wp_customize->add_control(new WP_Customize_Media_Control(
    $wp_customize,
    "snowfall_booking_card_{$i}_image",
    [
      'section'   => 'snowfall_booking_cards',
      'label'     => "Kort {$i} – Bild",
      'mime_type' => 'image',
    ]
  ));

  $wp_customize->add_setting("snowfall_booking_card_{$i}_icon", [
    'default'           => 0,
    'sanitize_callback' => 'absint',
  ]);

  $wp_customize->add_control(new WP_Customize_Media_Control(
    $wp_customize,
    "snowfall_booking_card_{$i}_icon",
    [
      'section'   => 'snowfall_booking_cards',
      'label'     => "Kort {$i} – ikon (hexagon)",
      'mime_type' => 'image',
    ]
  ));

  $wp_customize->add_setting("snowfall_booking_card_{$i}_text", [
    'default'           => ($i === 1 ? 'TURER' : ($i === 2 ? 'BOENDE' : 'RESTAURANG')),
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control("snowfall_booking_card_{$i}_text", [
    'section' => 'snowfall_booking_cards',
    'label'   => "Kort {$i} – Text på bilden",
    'type'    => 'text',
  ]);

  $wp_customize->add_setting("snowfall_booking_card_{$i}_url", [
    'default'           => '',
    'sanitize_callback' => 'esc_url_raw',
  ]);

  $wp_customize->add_control("snowfall_booking_card_{$i}_url", [
    'section'     => 'snowfall_booking_cards',
    'label'       => "Kort {$i} – Länk (valfri)",
    'type'        => 'url',
    'description' => 'Om tomt blir kortet inte klickbart.',
  ]);
}
});

add_filter('show_admin_bar', function ($show) {
  if (!is_admin() && isset($_GET['embed']) && $_GET['embed'] == '1') {
    return false;
  }
  return $show;
});

add_filter('body_class', function ($classes) {
  if (isset($_GET['embed']) && $_GET['embed'] === '1') {
    $classes[] = 'is-embed';
  }
  return $classes;
});

/* --------------------------------------------------
 * Shortcode
 * -------------------------------------------------- */
add_shortcode('bookingbar', function($atts) {

  $tabs_default = implode(',', array_filter([
    get_theme_mod('snowfall_bookingbar_tab_1', 'Turer'),
    get_theme_mod('snowfall_bookingbar_tab_2', 'Boende'),
    get_theme_mod('snowfall_bookingbar_tab_3', 'Restaurang'),
  ]));

  $atts = shortcode_atts([
    'tabs'        => $tabs_default,
    'cat_slugs'   => 'turer,boende,restaurang',
    'active'      => (string) get_theme_mod('snowfall_bookingbar_active', 0),
    'button_text' => get_theme_mod('snowfall_bookingbar_button_text', 'Hitta tillgänglighet'),
  ], $atts, 'bookingbar');

  $tabs     = array_map('trim', explode(',', $atts['tabs']));
  $catSlugs = array_map('trim', explode(',', $atts['cat_slugs']));
  $active   = (int) $atts['active'];

  $label_activity = get_theme_mod('snowfall_bookingbar_label_rooms', 'Aktivitet');
  $label_guests   = get_theme_mod('snowfall_bookingbar_label_guests', 'Gäster');
  $label_from     = get_theme_mod('snowfall_bookingbar_label_from', 'Från');
  $label_to       = get_theme_mod('snowfall_bookingbar_label_to', 'Till');

  $events = [];
  if (function_exists('tribe_get_events')) {
    $events = tribe_get_events([
      'posts_per_page' => 200,
      'start_date'     => '2000-01-01 00:00:00',
      'end_date'       => '2100-01-01 23:59:59',
      'orderby'        => 'title',
      'order'          => 'ASC',
    ]);
  } else {
    $events = get_posts([
      'post_type'      => 'tribe_events',
      'posts_per_page' => 200,
      'post_status'    => 'any',
      'orderby'        => 'title',
      'order'          => 'ASC',
      'suppress_filters' => true,
    ]);
  }

  ob_start(); ?>
    <div class="bookingbar"
      data-active="<?php echo esc_attr($active); ?>">

      <div class="bookingbar__tabs" role="tablist">
        <?php foreach ($tabs as $i => $label): ?>
          <button
            type="button"
            class="<?php echo $i === $active ? 'is-active' : ''; ?>"
            data-tab="<?php echo esc_attr($i); ?>"
            data-cat="<?php echo esc_attr($catSlugs[$i] ?? ''); ?>"
            role="tab"
            aria-selected="<?php echo $i === $active ? 'true' : 'false'; ?>">
            <?php echo esc_html($label); ?>
          </button>
        <?php endforeach; ?>
      </div>

      <form class="bookingbar__form" data-bookingbar-form>
        <input type="hidden" name="bb_cat" value="<?php echo esc_attr($catSlugs[$active] ?? ''); ?>">

        <label>
          <span class="bookingbar__selectlabel" data-select-label>
            <?php echo esc_html($label_activity); ?>
          </span>

          <select name="bb_event" data-activity-select>
            <option value="">Välj…</option>
            <?php foreach ($events as $ev):
              $terms = get_the_terms($ev->ID, 'tribe_events_cat');
              $slugs = [];
              if (is_array($terms)) foreach ($terms as $t) $slugs[] = $t->slug;
              $dataCats = esc_attr(implode(',', $slugs));
            ?>
<option
  value="<?php echo esc_attr($ev->ID); ?>"
  data-title="<?php echo esc_attr(get_the_title($ev)); ?>"
  data-cats="<?php echo $dataCats; ?>">
  <?php echo esc_html(get_the_title($ev)); ?>
</option>
            <?php endforeach; ?>
          </select>
        </label>

        <label><?php echo esc_html($label_guests); ?>
          <select name="guests">
            <?php for ($i=1; $i<=10; $i++): ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
        </label>

        <label><?php echo esc_html($label_from); ?>
          <input type="date" name="start_date">
        </label>

        <label><?php echo esc_html($label_to); ?>
          <input type="date" name="end_date">
        </label>

        <button type="submit"><?php echo esc_html($atts['button_text']); ?></button>
      </form>
    </div>
  <?php
  return ob_get_clean();
});

add_shortcode('booking_cards', function () {

  $title    = trim((string) get_theme_mod('snowfall_booking_cards_title', 'STARTA DITT ÄVENTYR'));
  $subtitle = trim((string) get_theme_mod('snowfall_booking_cards_subtitle', ''));

  ob_start(); ?>
    <section class="booking-cards" aria-label="Bokningskategorier">
      <div class="booking-cards__inner">

        <?php if ($title !== ''): ?>
          <h2 class="booking-cards__title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($subtitle !== ''): ?>
          <p class="booking-cards__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <div class="booking-cards__grid">
          <?php for ($i = 1; $i <= 3; $i++):
            $img_id  = (int) get_theme_mod("snowfall_booking_card_{$i}_image", 0);
$fallback = ($i === 1 ? 'TURER' : ($i === 2 ? 'BOENDE' : 'RESTAURANG'));
$text = trim((string) get_theme_mod("snowfall_booking_card_{$i}_text", $fallback));
            $url     = trim((string) get_theme_mod("snowfall_booking_card_{$i}_url", ''));
            $icon_id = (int) get_theme_mod("snowfall_booking_card_{$i}_icon", 0);

            $img_url  = $img_id ? wp_get_attachment_image_url($img_id, 'large') : '';
            if (!$img_url) continue;

            $icon_url = $icon_id ? wp_get_attachment_image_url($icon_id, 'full') : '';

            $tag   = $url ? 'a' : 'div';
            $attrs = $url ? 'href="'.esc_url($url).'"' : '';
          ?>
            <<?php echo $tag; ?> class="booking-cards__card" <?php echo $attrs; ?>>
              <img class="booking-cards__img" src="<?php echo esc_url($img_url); ?>" alt="">

              <?php if ($icon_url): ?>
                <img class="booking-cards__hex" src="<?php echo esc_url($icon_url); ?>" alt="" aria-hidden="true">
              <?php endif; ?>

              <?php if ($text !== ''): ?>
                <div class="booking-cards__overlay">
                  <span class="booking-cards__label"><?php echo esc_html($text); ?></span>
                </div>
              <?php endif; ?>
            </<?php echo $tag; ?>>
          <?php endfor; ?>
        </div>

      </div>
    </section>
  <?php
  return ob_get_clean();
});

add_filter('tribe_events_views_v2_view_repository_args', function ($args, $view) {
  if (!empty($_GET['bb_event_id'])) {
    $id = absint($_GET['bb_event_id']);
    if ($id) {
      $args['post__in'] = [$id];
    }
  }
  return $args;
}, 10, 2);
