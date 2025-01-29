<?php
/**
 * Template Name: Sitemap
 */

$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); ?>

<div id="primary" class="content-area-full content-default page-default-template sitemappage <?php echo $has_banner ?>">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<section class="text-centered-section">
				<div class="wrapper text-center">
					<div class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
					<?php the_content(); ?>
				</div>
			</section>

		<?php endwhile; ?>

		<section class="site-map">
				<div class="wrapper">
					
					<?php
					/* Main Navigation */
					global $post;
					$current_post_id = ( isset($post->ID) && $post->ID ) ? $post->ID : '';
					$current_url = ($current_post_id) ? get_permalink($current_post_id) : '';
					$current_url = ($current_url) ? rtrim($current_url,"/") : '';

					$parents = get_field("parent_menu","option");
					$childenMenuItems = array();
					$secondary_menu = get_field("secondary_menu","option");
          $sitemap__links = get_field('sitemapLinks','option');
					if($parents || $sitemap__links) { ?>
					<div id="sitemapLinks">
						<?php if ($parents) { get_template_part("parts/navigation-sitemap"); } ?>
            <?php if ($sitemap__links) { ?>
            <nav class="navigation-sitemap other-links">
              <?php foreach ($sitemap__links as $s) { 
                $group_title = $s['group_title'];
                $group_links = $s['group_links'];
                if($group_links) { ?>
                <ul class="sitemap">
                  <li class="grid-item other-link">
                    <?php if ($group_title) { ?>
                    <div class="parent-menu-link link-title"><?php echo $group_title ?></div>
                    <?php } ?>
                    <div class="menu-col">
                      <ul class="links">
                        <?php foreach ($group_links as $g) { 
                          $url = $g['link'];
                          $aName = (isset($url['title']) && $url['title']) ? $url['title'] : '';
                          $aLink = (isset($url['url']) && $url['url']) ? $url['url'] : '';
                          $aTarget = (isset($url['target']) && $url['target']) ? $url['target'] : '_self';
                          if($aName && $aLink) { ?>
                          <li><a href="<?php echo $aLink ?>" target="<?php echo $aTarget ?>"><?php echo $aName ?></a></li>
                          <?php } ?>
                        <?php } ?>
                      </ul>
                    </div>
                  </li>
                </ul>
                <?php } ?>
              <?php } ?>
            </nav>
            <?php } ?>
					</div>
					<?php } ?>

					</div>
				</section>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
