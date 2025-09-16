<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('tt4-parent', get_template_directory_uri() . '/style.css');
  wp_enqueue_style('tt4-child', get_stylesheet_uri(), array('tt4-parent'), '0.2.0');
});

add_filter('acf/settings/save_json', function($path) {
  return get_stylesheet_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function($paths) {
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
});

if (!function_exists('tripa_reading_time_fallback')) {
  function tripa_reading_time_fallback($content) {
    $words = str_word_count( wp_strip_all_tags( $content ) );
    return max(1, (int) ceil($words / 200));
  }
}
add_shortcode('tripa_meta', function(){
  $id = get_the_ID();
  $reading = function_exists('get_field') ? get_field('reading_time', $id) : null;
  if (!$reading && function_exists('tripa_reading_time_fallback')) {
    $reading = tripa_reading_time_fallback(get_post_field('post_content', $id));
  }
  $mood = function_exists('get_field') ? get_field('mood', $id) : null;
  $spot = function_exists('get_field') ? get_field('spotlight', $id) : null;

  $out = '~' . intval($reading ?: 1) . ' min read';
  if ($mood) { $out .= ' · Mood: ' . esc_html(ucfirst($mood)); }
  if ($spot) { $out .= ' · ⭐ Spotlight'; }
  return $out;
});
