<?php get_header(); ?>
<main class="tripa-front">
<?php
$settings_page = get_page_by_path('blog');
$settings_id   = $settings_page ? $settings_page->ID : 0;

$hero_title = function_exists('get_field') ? get_field('hero_title', $settings_id) : '';
$hero_sub   = function_exists('get_field') ? get_field('hero_subtitle', $settings_id) : '';
$cta_label  = function_exists('get_field') ? get_field('hero_cta_label', $settings_id) : '';
$cta_url    = function_exists('get_field') ? get_field('hero_cta_url', $settings_id) : '';
$hero_bg    = function_exists('get_field') ? get_field('hero_bg', $settings_id) : null;

$bg_url = (is_array($hero_bg) && isset($hero_bg['url'])) ? esc_url($hero_bg['url']) : '';
if (!$hero_title) $hero_title = get_bloginfo('name');
if (!$hero_sub)   $hero_sub   = get_bloginfo('description');
if (!$cta_label)  $cta_label  = 'Read the blog';
if (!$cta_url)    $cta_url    = '/blog';
?>
<section class="tripa-hero" <?php if ($bg_url) echo 'style="--hero-bg:url(' . $bg_url . ')"'; ?>>
  <div class="inner">
    <h1><?php echo esc_html($hero_title); ?></h1>
    <p class="sub"><?php echo esc_html($hero_sub); ?></p>
    <a class="cta" href="<?php echo esc_url($cta_url); ?>"><?php echo esc_html($cta_label); ?></a>
  </div>
</section>

<section aria-labelledby="spotlight">
  <h2 id="spotlight">Selected posts</h2>
  <?php
  $spot_ids = array();
  $spot = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'meta_key'       => 'spotlight',
    'meta_value'     => '1',
  ));
  if ($spot->have_posts()) : echo '<div class="tripa-grid">';
    while ($spot->have_posts()) : $spot->the_post();
      $spot_ids[] = get_the_ID();
      $reading_time = function_exists('get_field') ? get_field('reading_time') : null;
      if (!$reading_time && function_exists('tripa_reading_time_fallback')) {
        $reading_time = tripa_reading_time_fallback(get_post_field('post_content', get_the_ID()));
      }
      $mood = function_exists('get_field') ? get_field('mood') : null; ?>
      <article class="tripa-card">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="meta">
          ~<?php echo intval($reading_time ?: 1); ?> min read
          <?php if($mood): ?> · Mood: <?php echo esc_html(ucfirst($mood)); ?><?php endif; ?>
          · ⭐ Spotlight
        </p>
        <p><?php echo wp_kses_post( wp_trim_words( get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 24 ) ); ?></p>
      </article>
    <?php endwhile;
    echo '</div>';
    wp_reset_postdata();
  else:
    echo '<p>No selected posts yet. Toggle “Spotlight this post?” on a post.</p>';
  endif; ?>
</section>

<section aria-labelledby="latest" style="margin-top:2rem;">
  <h2 id="latest">Latest posts</h2>
  <?php
  $latest = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 6,
    'post__not_in'   => $spot_ids,
  ));
  if ($latest->have_posts()) :
    echo '<ul class="tripa-list">';
    while ($latest->have_posts()) : $latest->the_post();
      echo '<li><a href="'. esc_url(get_permalink()) .'">'. esc_html(get_the_title()) .'</a></li>';
    endwhile;
    echo '</ul>';
    wp_reset_postdata();
  else:
    echo '<p>No additional posts yet.</p>';
  endif; ?>
  <p style="margin-top:1rem;"><a href="/blog">More on the blog →</a></p>
</section>
</main>
<?php get_footer(); ?>
