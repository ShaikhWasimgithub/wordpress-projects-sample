<?php get_header(); ?>

<main>
  <h1>Projects</h1>

  <div class="project-filters">
    <strong>Filter by Category:</strong>
    <?php
      $terms = get_terms(array('taxonomy'=>'project_category','hide_empty'=>false));
      foreach($terms as $term){
        echo '<a href="'.get_term_link($term).'">'.$term->name.'</a> ';
      }
    ?>
  </div>

  <?php
  $args = array(
    'post_type' => 'project',
    'posts_per_page' => 6,
    'paged' => get_query_var('paged') ?: 1
  );
  $projects = new WP_Query($args);

  if($projects->have_posts()):
    echo '<div class="grid">';
    while($projects->have_posts()): $projects->the_post(); ?>
      <article>
        <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail('medium'); ?>
        </a>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php echo esc_html(get_post_meta(get_the_ID(),'short_description',true)); ?></p>
      </article>
    <?php endwhile;
    echo '</div>';

    echo paginate_links(array('total'=>$projects->max_num_pages));

    wp_reset_postdata();
  else:
    echo "<p>No projects found</p>";
  endif;
  ?>
</main>
<?php get_footer(); ?>

