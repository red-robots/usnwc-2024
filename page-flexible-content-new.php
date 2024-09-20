<?php
/**
 * Template Name: NEW Flexible Content
 */

get_header(); 
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full festival-page">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="intro-text-wrap">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<?php if( get_the_content() ) { ?>
				<div class="intro-text"><?php the_content(); ?></div>
				<?php } ?>
			</div>
		</div>
	<?php endwhile;  ?>

  <?php /* FLEXIBLE CONTENT */ ?>
  <?php if( have_rows('flexible_content') ) { ?>
  <div class="new-flexible-content">
    <?php $i=1; while( have_rows('flexible_content') ): the_row(); ?>

      <?php if( get_row_layout() == 'image_and_text_row' ) { ?>
        <?php  
        $title_top = get_sub_field('title_top');
        $main_title = get_sub_field('main_title');
        $content = get_sub_field('content');
        $image = get_sub_field('image');
        $image_position = get_sub_field('image_position');
        $layout = ( ($main_title || $content) && $image ) ? 'half':'full';
        $layout .= ' image-position-' . $image_position;
        ?>
        <div class="flexibleContentDiv <?php echo $layout ?>">
          <div class="flexwrap">
            <?php if ($image) { ?>
            <div class="fxcol imageCol">
              <figure>
                <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" />
              </figure>
            </div>
            <?php } ?>

            <?php if ($main_title || $content) { ?>
            <div class="fxcol textCol">
              <div class="inside">
                <?php if ($title_top || $main_title) { ?>
                <div class="titleDiv">
                  <?php if ($title_top) { ?>
                  <div class="title-sm"><?php echo $title_top ?></div>
                  <?php } ?>
                  <?php if ($main_title) { ?>
                  <h2 class="title"><?php echo $main_title ?></h2>
                  <?php } ?>
                </div>
                <?php } ?>

                <?php if ($content) { ?>
                <div class="contentDiv">
                  <?php echo anti_email_spam($content) ?>
                </div>
                <?php } ?>
              </div>
            </div>  
            <?php } ?>

          </div>
        </div>
      <?php } ?>

    <?php $i++; endwhile; ?>
  </div>
  <?php } ?>

</div>
<?php 
get_footer();
