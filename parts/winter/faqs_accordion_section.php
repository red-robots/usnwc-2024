<?php if( get_row_layout() == 'faqs_accordion_section' ) { ?>
  <?php  
  $text_content = get_sub_field('text_content');
  $faqs = get_sub_field('faqs');
  if($text_content || $faqs) { ?>
  <section id="<?php echo get_row_layout() ?>_<?php echo $ctr ?>" class="repeatable repeatable_<?php echo get_row_layout() ?>">
    <div class="section-inner">
      <?php if ($text_content) { ?>
      <div class="section-intro">
        <div class="wrapper">
          <?php echo anti_email_spam($text_content); ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($faqs) { ?>
      <div class="faqs-section">
        <div class="wrapper">
          <div class="faqs">
            <?php $fi=1; foreach ($faqs as $faq) { 
              $question = $faq['question'];
              $answer = $faq['answer'];
              $faq_id = 'faqsection_' . $ctr . '_faq_item' . $fi;
              if($question && $answer) { ?>
              <div class="faq-item">
                <h2 role="button" tabindex="0" aria-expanded="false" aria-controls="<?php echo $faq_id ?>" class="faq-question"><span><?php echo $question ?></span><i class="fa fa-chevron-down" aria-hidden="true"></i></h2>
                <div id="<?php echo $faq_id ?>" class="faq-answer"><?php echo anti_email_spam($answer); ?></div>
              </div>
              <?php $fi++; } ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>