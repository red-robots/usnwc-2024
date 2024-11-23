<?php if ( get_row_layout() == 'pricing_cards' ) {  
  $title = get_sub_field('title'); 
  $section_text = get_sub_field('section_text'); 
  $infocards = get_sub_field('infocards'); 
  ?>
  <div id="section-pricing_cards-<?php echo $ctr ?>" class="repeatable-block section section-pricing_cards">
    <div class="wrapper">
      <?php if ($title) { ?>
      <div class="content-part content-title">
        <h2><?php echo $title ?></h2>
        <?php if ($section_text) { ?>
        <div class="content-part content-intro">
          <?php echo anti_email_spam($section_text); ?>
        </div> 
        <?php } ?>
      </div> 
      <?php } ?>

      <?php if ($infocards) { ?>
      <div class="content-part infocards">
        <div class="flexwrap show-<?php echo count($infocards); ?>">
          <?php foreach ($infocards as $info) { 
          $image = $info['image'];
          $title = $info['title'];
          $details = $info['details'];
          $priceList = $info['price_list'];
          $buttons1 = $info['button1'];
          $buttons2 = $info['button2'];
          $buttons = array($buttons1, $buttons2);
          ?>
          <div class="fxcol">
            <div class="inner">
              <?php if ($image) { ?>
              <figure><img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>"></figure> 
              <?php } ?>

              <?php if ($title) { ?>
              <h3 class="title"><?php echo $title ?></h3> 
              <?php } ?>

              <?php if ($details) { ?>
              <div class="details"><?php echo $details ?></div> 
              <?php } ?>

              <?php if ($priceList) { ?>
              <div class="priceList">
                <?php foreach ($priceList as $p) { 
                  $name = $p['name'];
                  $price = $p['price'];
                  if($name) { ?>
                  <div class="line-item">
                    <span class="name"><span><?php echo $name ?></span></span>
                    <span class="price"><span><?php echo $price ?></span></span>
                  </div>
                  <?php } ?>
                <?php } ?>
              </div> 
              <?php } ?>

              <?php if ($buttons && array_filter($buttons) ) { ?>
              <div class="button-block">
                <?php foreach ($buttons as $btn) { 
                  $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                  $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                  $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                  if($btnUrl && $btnTitle) { ?>
                  <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="button button-red"><?php echo $btnTitle ?></a>
                  <?php } ?>
                <?php } ?>
              </div> 
              <?php } ?>

            </div>
          </div>
          <?php } ?>
        </div>
      </div> 
      <?php } ?>

      
    </div>
  </div>
<?php } ?>
