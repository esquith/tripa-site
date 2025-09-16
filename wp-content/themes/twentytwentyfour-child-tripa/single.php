<?php get_header(); ?>
<main id="site-content" class="tripa-single" style="max-width:820px;margin:0 auto;padding:2rem 1rem;">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article <?php post_class(); ?>>
      <header class="entry-header" style="margin-bottom:1rem;">
        <h1 style="margin:0 0 .25rem 0;"><?php the_title(); ?></h1>
        <?php
          $reading_time = function_exists('get_field') ? get_field('reading_time') : null;
          if (!$reading_time && function_exists('tripa_reading_time_fallback')) {
            $reading_time = tripa_reading_time_fallback(get_post_field('post_content', get_the_ID()));
          }
          $mood = function_exists('get_field') ? get_field('mood') : null;
          $spotlight = function_exists('get_field') ? get_field('spotlight') : null;
        ?>
        <p class="entry-meta" style="opacity:.85;font-size:.95rem;">
          <span><?php echo '~' . intval($reading_time ?: 1) . ' min read'; ?></span>
          <?php if ($mood): ?><span> · Mood: <?php echo esc_html(ucfirst($mood)); ?></span><?php endif; ?>
          <?php if ($spotlight): ?><span> · ⭐ Spotlight</span><?php endif; ?>
        </p>
      </header>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
