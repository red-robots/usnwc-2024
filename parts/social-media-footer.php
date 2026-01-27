<?php 
	$social_media = get_social_links();
	$social_links_order = get_field("footer_social_media_order","option");
	$links_order = array();
	
	if($social_links_order) {
		$parts = explode(",",$social_links_order);
		if($parts) {
			foreach($parts as $p) {
				$str = preg_replace('/\s+/','',$p);
				if($str) {
					$links_order[] = $str;
				}
			}
		}
	}

	if ($links_order) { 

		foreach ($links_order as $v) { 
			if( isset($social_media[$v]) && $social_media[$v] ) { 
				$m = $social_media[$v]; 
				$social_link = $m['link'];
				$social_icon = $m['icon']; 
        if($social_link && $social_icon) {
          $socialName = get_root_name_from_url($social_link);
          $icon = get_field( strtolower($socialName).'_icon','option');
          if($icon) { ?>
				  <a href="<?php echo $social_link ?>" target="_blank" class="social-icon-<?php echo $socialName ?>"><span class="sr-only"><?php echo ucwords($socialName) ?></span>
            <img src="<?php echo $icon ?>" alt="<?php echo ucwords($socialName) ?> icon" />
          </a>
          <?php } ?>	
        <?php } ?>
			<?php
			}	
		} 

	} else { ?>
		
		<?php foreach ($social_media as $k=>$m) { 
			$social_link = $m['link'];
			$social_icon = $m['icon'];
			?>
			<a href="<?php echo $social_link ?>" target="_blank"><i class="<?php echo $social_icon ?>"></i></a>	
		<?php } 

	} ?>

