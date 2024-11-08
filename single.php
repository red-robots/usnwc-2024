<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bellaworks
 */

get_header(); 
?>
<div id="primary" class="content-area-single">
  <main id="main" class="site-main" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
      <?php  
      $post_id = get_the_ID();
      $thumbId = get_post_thumbnail_id($post_id); 
      $featImg = wp_get_attachment_image_src($thumbId,'full');
      $short_description = get_field("short_description_text");
      $custom_post_author = get_field("custom_post_author");
      $postdate = get_the_date('F j, Y');
      ?>

      <div class="entry-title">
        <div class="contentWrap">
          <h1 class="post-title"><?php the_title(); ?></h1>  
          <div class="meta">
            <?php if ($custom_post_author) { ?>
              <span>By <?php echo $custom_post_author ?></span>
            <?php } ?>
              <span>Posted on <?php echo $postdate ?></span>
          </div>
        </div>
      </div>

      <?php if ($short_description) { ?>
      <div class="contentWrap quote">
        <blockquote><?php echo $short_description ?></blockquote> 
      </div>
      <?php } ?>


      <?php if ( has_post_thumbnail() ) { ?>
      <div class="featured-image">
        <figure><?php the_post_thumbnail() ?></figure>
      </div>
      <?php } ?>
      
    <?php endwhile; ?>

    <?php if( have_rows('the_story') ) { ?>
      <?php $ctr=1; while( have_rows('the_story') ): the_row(); ?>
        
        <?php include( locate_template('parts/story/text_block.php') ); ?>
        <?php include( locate_template('parts/story/gallery.php') ); ?>
        <?php include( locate_template('parts/story/quote.php') ); ?>
      
      <?php $ctr++; endwhile;  ?>
    <?php } ?>


    
  </main>
</div>
<?php
get_footer();
