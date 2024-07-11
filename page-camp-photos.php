<?php
/**
 * Template Name: Camp Photos
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bellaworks
 */
global $post;
$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); 

if( is_page('waiver') ) {
	$pageClass = 'waiver';
} else {
	$pageClass = '';
}
?>

<style type="text/css">
/* Remove default bullets */
ul, #myUL {
  list-style-type: none;
}

/* Remove margins and padding from the parent ul */
#myUL {
  margin: 0;
  padding: 0;
}

/* Style the caret/arrow */
.caret {
  cursor: pointer;
  user-select: none; /* Prevent text selection */
}

/* Create the caret/arrow with a unicode, and style it */
.caret::before {
  content: "\25B6";
  color: black;
  display: inline-block;
  margin-right: 6px;
}

/* Rotate the caret/arrow icon when clicked on (using JavaScript) */
.caret-down::before {
  transform: rotate(90deg);
}

/* Hide the nested list */
.nested {
  display: none;
}

/* Show the nested list when the user clicks on the caret/arrow (with JavaScript) */
.active {
  display: block;
}
li {
	font-size: 22px;
	margin-bottom: 10px;
}
ul.nested {
	margin-top: 5px;
}
</style>

<div id="primary" class="content-area-full content-default page-default-template <?php echo $has_banner ?>">
	<main id="main" class="site-main wrapper" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<section class="text-centered-section">
				<div class="wrapper text-center">
					<div class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
					<div class="<?php echo $pageClass; ?>">
						<?php the_content(); ?>
					</div>
				</div>
			</section>

		<?php endwhile; ?>




		<?php if ( post_password_required() ) :

						    // if your post is password protected with our Pro version, show our password form instead
						    //echo get_the_password_form();

						/* display the password protected content if the correct password is entered */ 
				else : ?>


					<?php
						function get_hierarchical_pages() {
						    // Retrieve all pages
						    $pages = get_pages(array(
						        'sort_column' => 'menu_order', // You can sort by 'post_title', 'menu_order', etc.
						        'sort_order'  => 'ASC',
						        'post_type'   => 'camp_photos'
						    ));

						    // Initialize an empty array to hold the hierarchical structure
						    $hierarchical_pages = array();

						    // Organize pages into a hierarchical structure
						    foreach ($pages as $page) {
						        // If the page has no parent, it is a top-level page
						        if ($page->post_parent == 0) {
						            $hierarchical_pages[$page->ID] = array(
						                'page' => $page,
						                'children' => array()
						            );
						        } else {
						            // Find the parent page and add this page as a child
						            add_child_page($hierarchical_pages, $page);
						        }
						    }

						    return $hierarchical_pages;
						}

						function add_child_page(&$hierarchical_pages, $page) {
						    foreach ($hierarchical_pages as &$parent_page) {
						        // If the parent page matches the current page's parent ID
						        if ($parent_page['page']->ID == $page->post_parent) {
						            $parent_page['children'][$page->ID] = array(
						                'page' => $page,
						                'children' => array()
						            );
						            return;
						        }
						        // Recursively search for the parent in the child pages
						        add_child_page($parent_page['children'], $page);
						    }
						}


						function display_hierarchical_pages($pages, $is_child = false) {
						    // Add the 'nested' class if the current list is nested
						    $class = $is_child ? ' class="nested"' : '';
						    
						    echo '<ul' . $class . '>';

						    foreach ($pages as $page_data) {
						        $page = $page_data['page'];
						        
						        // Check if it is a child page or a parent page
						        if ($is_child) {
						            // If it's a child page, display with a link
						            echo '<li><a href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a>';
						        } else {
						            // If it's a parent page, display without a link
						            echo '<li><span class="caret">' . $page->post_title;
						        }

						        // If the page has children, display them
						        if (!empty($page_data['children'])) {
						            display_hierarchical_pages($page_data['children'], true);
						        }

						        echo '</span></li>';
						    }

						    echo '</ul>';
						}





						// Get the hierarchical pages
						$hierarchical_pages = get_hierarchical_pages();

						// Display the hierarchical pages
						display_hierarchical_pages($hierarchical_pages);
						?>



						

						 

		<?php endif; ?>





	</main><!-- #main -->
</div><!-- #primary -->
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		var toggler = document.getElementsByClassName("caret");
		var i;

		for (i = 0; i < toggler.length; i++) {
		  toggler[i].addEventListener("click", function() {
		    this.parentElement.querySelector(".nested").classList.toggle("active");
		    this.classList.toggle("caret-down");
		  });
		}
	});// END 

</script>
<?php
get_footer();
