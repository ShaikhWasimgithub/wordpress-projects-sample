<?php get_header(); ?>

<main class="project-detail">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
    <!-- Project Title -->
    <h1><?php the_title(); ?></h1>

    <!-- Featured Image Only -->
    <?php if ( has_post_thumbnail() ) : ?>
      <div class="project-image">
        <?php the_post_thumbnail('large'); ?>
      </div>
    <?php endif; ?>

    <!-- Short Description -->
    <?php $short_desc = get_post_meta(get_the_ID(), 'short_description', true); ?>
    <?php if ( !empty($short_desc) ) : ?>
      <div class="project-short">
        <strong>Short Description:</strong>
        <p><?php echo esc_html($short_desc); ?></p>
      </div>
    <?php endif; ?>

    <!-- Long Description (Text Only, Image Remove) -->
    <div class="project-long">
      <?php 
        // Content me agar image daal bhi di gayi ho to usko hata do
        $content = get_the_content();
        // Regex se <img> tag remove karte hain
        $content = preg_replace('/<img[^>]+\>/i', '', $content);
        echo apply_filters('the_content', $content);
      ?>
    </div>

    <!-- Categories -->
    <div class="project-cats">
      <strong>Category: </strong>
      <?php the_terms(get_the_ID(), 'project_category', ', '); ?>
    </div>

  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>



