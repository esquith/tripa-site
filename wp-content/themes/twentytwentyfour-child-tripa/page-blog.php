<?php
/*
Template Name: Blog Index
Template Post Type: page
*/
get_header(); ?>
<main class="tripa-front">
  <h1>Blog</h1>
  <?php
  $paged = max(1, get_query_var('paged'));
  $q = new WP_Query(['post_type'=>'post','paged'=>$paged]);
  if ($q->have_posts()):
    while ($q->have_posts()): $q->the_post();
      $reading_time = function_exists('get_field') ? get_field('reading_time') : null;
      if (!$reading_time && function_exists('tripa_reading_time_fallback')) {
        $reading_time = tripa_reading_time_fallback(get_post_field('post_content', get_the_ID()));
      }
      $mood = function_exists('get_field') ? get_field('mood') : null;
      ?>
      <article class="tripa-card" style="margin:1rem 0;padding:1rem;border:1px solid rgba(255,255,255,.15);border-radius:12px">
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p class="meta">~<?php echo intval($reading_time ?: 1); ?> min read<?php if($mood): ?> · Mood: <?php echo esc_html(ucfirst($mood)); ?><?php endif; ?></p>
        <p><?php echo wp_kses_post( wp_trim_words( get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 28 ) ); ?></p>
      </article>
      <?php
    endwhile;
    the_posts_pagination();
    wp_reset_postdata();
  else:
    echo '<p>No posts yet.</p>';
  endif;
  ?>
</main>
<?php get_footer();
