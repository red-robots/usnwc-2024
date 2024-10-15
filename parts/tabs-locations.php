<?php  
  $wwlocations = get_field('locations');
?>

<?php if ( $wwlocations ) { ?>
<div class="todaySnapshotInfo locationTabsWrapper">
    <ul class="location-tabs">
    <?php $i=1; foreach ($wwlocations as $w) { 
      $loc = $w['locations_taxonomy'];
      $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
      $location = $w['location'];
      $is_active = ($i==1) ? ' active':'';
      $is_selected = ($i==1) ? 'true':'false';
      if($name) { ?>
        <li class="tab<?php echo $is_active ?>">
          <button role="tab" aria-selected="<?php echo $is_selected ?>" aria-controls="tabpanel-<?php echo sanitize_title($name) ?>" id="tab-<?php echo sanitize_title($name) ?>">
            <span class="wname"><?php echo $name ?></span>
            <?php if ($location) { ?>
            <span class="wloc"><?php echo $location ?></span>
            <?php } ?>
          </button>
        </li>
      <?php } ?>
    <?php $i++; } ?>
    </ul> 

    <?php $j=1; foreach ($wwlocations as $w) { 
      $loc = $w['locations_taxonomy'];
      $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
      $location = $w['location'];
      $is_active = ($j==1) ? ' active':'';
      $is_selected = ($j==1) ? 'true':'false';
      $is_display = ($j==1) ? 'flex':'none';
      if($name) { ?>
        
     <?php $j++; } ?>
    <?php } ?>
</div>  
<?php } ?>
