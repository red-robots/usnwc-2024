<?php
/**
 * Template Name: FAQs
 */

$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); ?>

<div id="primary" class="content-area-full content-faqs-page <?php echo $has_banner ?>">
	<main id="main" class="site-main" role="main">

		<div id="faqs"></div>

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

		<?php  
    $faq_taxonomy = 'faq_type';
		$faq_categories = get_terms( array(
		    'taxonomy' => $faq_taxonomy,
		    'hide_empty' => true,
		));
		if($faq_categories) { ?>
		<section class="all-faqs-container">
			<div class="tabsWrapper">
        <div class="wrapper">
          <div id="pageTabs" class="pageTabs2 faqsTabLinks aligncenter">
    				<div id="tabcontent">
              <?php foreach ($faq_categories as $k=>$f) { 
                $is_tab_active = ($k==0) ? ' active':'';  ?>
                <span class="mini-nav<?php echo $is_tab_active; ?>">
                  <a href="javascript:void(0)" data-rel="#faq--<?php echo $f->slug; ?>"><?php echo $f->name; ?></a>
                </span>
              <?php } ?>
            </div>
          </div>
        </div>
			</div>

      <?php $i=1; foreach ($faq_categories as $f) { 
        $is_active = ($i==1) ? ' active':''; 
        $is_show = ($i==1) ? ' style="display:block"':''; 
        ?>
        <div id="faq--<?php echo $f->slug; ?>" class="faq-tab-group fadeIn animated fullwidth-float-left<?php echo $is_active; ?>"<?php echo $is_show; ?>>
          <div class="wrapper">
            <?php
            $args = array(
              'posts_per_page'   => -1,
              'post_type'        => 'faqs',
              'post_status'      => 'publish',
              'tax_query' => array(
                    array(
                        'taxonomy' => $faq_taxonomy,
                        'terms' => $f->term_id,
                        'field' => 'term_id',
                    )
              ),
              'orderby' => 'title',
              'order' => 'ASC' 
            );
            $faqs = new WP_Query($args);
            if ( $faqs->have_posts() ) {  ?>
            <div class="faqs">
              <?php while ( $faqs->have_posts() ) : $faqs->the_post(); 
                $faqs_items = get_field('faqs', get_the_ID());
                $terms = get_the_terms( get_the_ID(), $faq_taxonomy );
                $term = ($terms) ? $terms[0]->slug : '';
                if($faqs_items) { ?>
                <div id="faq--<?php echo $term ?>" class="faqs-group accordions-wrapper">
                  <div class="midwrapper">
                    <h2 class="stitle"><?php the_title(); ?></h2>
                    <ul class="accordion">
                    <?php foreach ($faqs_items as $q) { 
                      $question = $q['question'];
                      $answer = $q['answer'];
                      if($question && $answer) { ?>
                      <li class="accordion-item">
                         <button class="accordion-handle" aria-expanded="false"><?php echo $question ?></button>
                         <div class="accordion-details"><?php echo anti_email_spam($answer) ?></div>
                      </li>  
                      <?php } ?>
                    <?php } ?>
                    </ul>
                  </div>
                </div>
                <?php } ?>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php } ?>
          </div>
        </div>
      <?php $i++; } ?>
      
		</section>
		<?php } ?>


	</main><!-- #main -->
</div><!-- #primary -->

<script type="text/javascript">
jQuery(document).ready(function($){
	$(document).on('click', '.faqsTabLinks a', function(e){
    e.preventDefault();
    var tabId = $(this).attr('data-rel');
    var parent = $(this).parent();
    parent.addClass('active');
    $('.faqsTabLinks .mini-nav').not(parent).removeClass('active');

    if( $(tabId).length ) {
      $('.faq-tab-group' + tabId).addClass('active');
      $('.faq-tab-group' + tabId).toggle();
      $('.faq-tab-group').not(tabId).each(function(){
        $(this).removeClass('active');
        $(this).hide();
      });
    }
  });
});
</script>
<?php
get_footer();
