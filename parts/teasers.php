<?php
// Check value exists.
 if ( have_rows('alternating_cards') ) :

    // Loop through rows.
    while ( have_rows('alternating_cards') ) : the_row(); 
    	$cards = get_sub_field('the_cards');
    	$smallCards = get_sub_field('cards');
    	// echo '<pre>';
    	// print_r($smallCards);
    	// echo '</pre>';

        // Case: Paragraph layout.
        if( get_row_layout() == 'alternating_card' ):
        	$sectionTitle = get_sub_field('section_title');
        	?>
        	<?php if($sectionTitle){ ?>
	            <section class="section-title">
	            	<h2 class="stitle"><?php echo $sectionTitle; ?></h2>
	            </section>
        	<?php } ?>
        	<?php
            foreach( $cards as $c ) : 
            	$creative = $c['creative'];
	            $title = $c['title'];
	            $description = $c['description'];
	            $link = $c['calls_to_action'];
            ?>
            
            <section class="alt-cards">
            	<div class="creative <?php echo $cClass; ?>">
            		<img src="<?php echo $creative['url']; ?>" alt="<?php echo $creative['alt']; ?>">
            	</div>
            	<div class="info <?php echo $iClass; ?>">
            		<h2><?php echo $title; ?></h2>
            		<div class="desc"><?php echo $description; ?></div>
            		<div class="btn-wrapper">
            		<?php foreach( $link as $l ){ ?>
            			<div class="button">
            				<a href="<?php echo $l['button']['url']; ?>" target="<?php echo $l['button']['target']; ?>" class="btn-sm">
            					<span><?php echo $l['button']['title']; ?></span>
            				</a>
            			</div>
            		<?php } ?>
            		</div>
            	</div>
            </section>
        	<?php endforeach; ?>

        <?php
    	elseif( get_row_layout() == 'mailchimp_signup' ):
    		$mc_form = get_sub_field('form');
    		$mc_title = get_sub_field('title');
    		$mc_desc = get_sub_field('description');

    		if( $mc_form == 'general' ) { 
    			include( locate_template('parts/mc-embed-form.php') );
    		} ?>

        <?php // Case: Download layout.
        elseif( get_row_layout() == 'small_row_cards' ): 
        	$sectionTitle = get_sub_field('section_title');
        	?>
        	<?php if($sectionTitle){ ?>
	            <section class="section-title">
	            	<h2 class="stitle"><?php echo $sectionTitle; ?></h2>
	            </section>
        	<?php } ?>
        	<section class="small-cards cardwrap">
        		<?php foreach( $smallCards as $sm ): 
        			$img = $sm['creative'];
		            $title = $sm['title'];
		            $description = $sm['description'];
		            $link = $sm['call_to_action'];
        			?>
        			<div class="small-card">
        				<div class="image">
        					<img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
        				</div>
        				<div class="desc">
        					<?php if($title){ ?><h3><?php echo $title; ?></h3><?php } ?>
        					<?php if($description){ ?><p><?php echo $description; ?></p><?php } ?>
        					<?php if($link){ ?>
        						<div class="btn">
        							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="btn-sm">
        								<span><?php echo $link['title']; ?></span>
        							</a>
        						</div>
        					<?php } ?>
        				</div>
        			</div>
        		<?php endforeach; ?>
        	</section>
        <?php endif;

    // End loop.
    endwhile;

// No value.
else :
    // Do something...
endif;