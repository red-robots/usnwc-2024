<?php 
// wp_reset_postdata();

// echo $raceTerm;
// echo '<pre>';
// print_r($raceTerm);
// echo '</pre>';

$args = array();
$currentPostType = get_post_type();
$currentPostId = get_the_ID();
$similarPosts = get_field("similar_posts_section","option");
$bottomSectionTitle = '';
if($similarPosts) {
	foreach($similarPosts as $s) {
		$posttype = $s['posttype'];
		$sectionTitle = $s['section_title'];
		if($posttype==$currentPostType) {
			$bottomSectionTitle = $sectionTitle;
		}
	}
}
$perpage = 3;
if($currentPostType=='activity') {
	$perpage = -1;
}

if($currentPostType=='race') {
	$terms = get_the_terms($currentPostId, 'activity_type');

  if( $terms ) {
    $term = $terms[0]->slug;
  	$args = array(
  		'posts_per_page'=> $perpage,
  		'post_type'			=> $currentPostType,
  		'orderby' 			=> 'title',
  	  'order'    			=> 'ASC',
  		'post_status'		=> 'publish',
  		'post__not_in' 	=> array($currentPostId),
  		'tax_query' => array(
  			array(
  				'taxonomy' => 'activity_type',
  				'field'    => 'slug',
  				'terms'    => $term,
  			),
  		),
  	);
  }

} else {
	$args = array(
		'posts_per_page'=> $perpage,
		'post_type'			=> $currentPostType,
		'orderby' 			=> 'title', /* rand */
	  'order'    			=> 'ASC',
		'post_status'		=> 'publish',
		'post__not_in' 	=> array($currentPostId)
	);
}


if($args) {
  $posts = new WP_Query($args);
  $otherPosts = get_posts($args);
  $count = count($otherPosts);
  $chunks = array();
  if($count > 2) {
    $num = round( $count/3 );
    $chunks = array_chunk($otherPosts, $num);
  }
  
  

  if( $otherPosts ) { ?>
  <section class="explore-other-stuff">
  	<div class="wrapper">
  		<?php if ($bottomSectionTitle) { ?>
  		<h3 class="sectionTitle"><?php echo $bottomSectionTitle ?></h3>
  		<?php } ?>

  		
  		<div class="post-type-entries">
        <?php if ($chunks) { ?>
        <div class="chunks">
          <?php foreach($chunks as $items) { ?>
          <div class="flexcol">
            <?php foreach ($items as $p) { $pid = $p->ID; ?>
            <div class="entry">
              <a href="<?php echo get_permalink($pid); ?>"><?php echo $p->post_title; ?></a>
            </div>
            <?php } ?>
          </div>
          <?php } ?>
        </div>

        <?php } else { ?>
    			<div class="columns">
    				<?php foreach($otherPosts as $p) { $pid = $p->ID; ?>
    				<div class="entry">
    					<a href="<?php echo get_permalink($pid); ?>"><?php echo $p->post_title; ?></a>
    				</div>
    				<?php } ?>
    			</div>
        <?php } ?>
  		</div>
  		
  	</div>
  </section>
  <?php } ?>
<?php } ?>
