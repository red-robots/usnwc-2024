<?php if( get_row_layout() == 'faqs_accordion_section' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $text_content = get_sub_field('text_content');
  $faqs = get_sub_field('faqs');
  if($text_content || $faqs) { ?>
  <section id="<?php echo get_row_layout() ?>_<?php echo $ctr ?>" class="repeatable repeatable_<?php echo get_row_layout() ?>">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>
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
            <?php 
              $max_faqs = 3;
              $count_faqs = count($faqs);
              $fi=1; foreach ($faqs as $faq) { 
              $question = $faq['question'];
              $answer = $faq['answer'];
              $faq_id = 'faqsection_' . $ctr . '_faq_item' . $fi;
              $is_hide = ($fi>$max_faqs) ? 'hidden':'visible';
              //$is_visible = ($fi<=$max_faqs) ? ' visible':'';
              if($question && $answer) { ?>
              <div class="faq-item <?php echo $is_hide ?>">
                <h2 role="button" tabindex="0" aria-expanded="false" aria-controls="<?php echo $faq_id ?>" class="faq-question"><span><?php echo $question ?></span><i class="fa fa-chevron-down" aria-hidden="true"></i></h2>
                <div id="<?php echo $faq_id ?>" class="faq-answer"><?php echo anti_email_spam($answer); ?></div>
              </div>
              <?php $fi++; } ?>
            <?php } ?>
          </div>
          <?php if ($count_faqs>3) { ?>
          <div class="faqs-button-bottom">
            <button class="repeaterFAQs btn-sm xs"><span>See More</span></button>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>