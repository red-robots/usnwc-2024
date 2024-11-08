<?php if( get_row_layout() == 'quote' ) { ?>
  <?php  
  $quote = get_sub_field('quote');
  if($quote) { ?>
  <section class="quote-section">
    <div class="contentWrap">
      <blockquote class="large">
        &quot;<?php echo $quote ?>&quot;
      </blockquote>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>