<?php if ($is_repeater_dashed_items && $repeater_dashed_items) { ?>
  <div class="schedule-items-wrap">
    <?php foreach ($repeater_dashed_items as $di) { 
      $d_title = $di['title'];
      $d_items = $di['items']; ?>

      <?php if($d_title || $d_items) { ?>
      <div class="group-dash-items">
        <?php if($d_title) { ?>
        <div class="group-title"><span><?php echo $d_title ?></span></div>
        <?php } ?>

        <?php if($d_items) { ?>
        <div class="group-items">
          <?php foreach ($d_items as $m) { 
            $m_title = $m['title'];
            $m_text = $m['description'];
            $m_text_right = $m['text_right'];
            $m_btn = $m['button'];
            $mbtnName = (isset($m_btn['title']) && $m_btn['title']) ? $m_btn['title'] : '';
            $mbtnLink = (isset($m_btn['url']) && $m_btn['url']) ? $m_btn['url'] : '';
            $mbtnTarget = (isset($m_btn['target']) && $m_btn['target']) ? $m_btn['target'] : '_self';
            if ($m_title) {  ?>
            <div class="dash-item">
              <div class="line">
                <span class="name"><?php echo $m_title ?></span>
                <?php if ($m_text_right || $mbtnName ) { ?>
                  <span class="right-info">
                    <span class="text-desktop">
                      <?php if ($m_text_right) { ?>
                        <span class="text"><?php echo $m_text_right ?></span> 
                      <?php } ?>
                      <?php if ($mbtnName && $mbtnLink) { ?>
                        <span class="link">
                          <a href="<?php echo $mbtnLink ?>" target="<?php echo $mbtnTarget ?>" class="simple-link"><?php echo $mbtnName ?></a>
                        </span>
                      <?php } ?>
                    </span>
                  </span>
                <?php } ?>
              </div>

              <?php if ($m_text) { ?>
                <span class="desc"><?php echo $m_text ?></span> 
              <?php } ?>

              <?php if ($m_text_right || $mbtnName ) { ?>
                <span class="right-info right-info-mobile">
                  <?php if ($m_text_right) { ?>
                    <div class="text-mobile"><?php echo $m_text_right ?></div> 
                  <?php } ?>
                  <?php if ($mbtnName && $mbtnLink) { ?>
                    <a href="<?php echo $mbtnLink ?>" target="<?php echo $mbtnTarget ?>" class="simple-link"><?php echo $mbtnName ?></a>
                  <?php } ?>
                </span>
              <?php } ?>
            </div>
            <?php } ?>
          <?php } ?>
        </div>  
        <?php } ?>

      </div>
      <?php } ?>

    <?php } ?>
  </div>
<?php } else { ?>

  <?php if ($has_dash_items && $line_items) { ?>
  <div class="schedule-items-wrap">
    <?php foreach ($line_items as $m) { 
      $m_title = $m['title'];
      $m_text = $m['description'];
      $m_text_right = $m['text_right'];
      $m_btn = $m['button'];
      $mbtnName = (isset($m_btn['title']) && $m_btn['title']) ? $m_btn['title'] : '';
      $mbtnLink = (isset($m_btn['url']) && $m_btn['url']) ? $m_btn['url'] : '';
      $mbtnTarget = (isset($m_btn['target']) && $m_btn['target']) ? $m_btn['target'] : '_self';
      if ($m_title) {  ?>
      <div class="dash-item">
        <div class="line">
          <span class="name"><?php echo $m_title ?></span>
          <?php if ($m_text_right || $mbtnName ) { ?>
            <span class="right-info">
              <span class="text-desktop">
                <?php if ($m_text_right) { ?>
                  <span class="text"><?php echo $m_text_right ?></span> 
                <?php } ?>
                <?php if ($mbtnName && $mbtnLink) { ?>
                  <span class="link">
                    <a href="<?php echo $mbtnLink ?>" target="<?php echo $mbtnTarget ?>" class="simple-link"><?php echo $mbtnName ?></a>
                  </span>
                <?php } ?>
              </span>
            </span>
          <?php } ?>
        </div>

        <?php if ($m_text) { ?>
          <span class="desc"><?php echo $m_text ?></span> 
        <?php } ?>

        <?php if ($m_text_right || $mbtnName ) { ?>
          <span class="right-info right-info-mobile">
            <?php if ($m_text_right) { ?>
              <div class="text-mobile"><?php echo $m_text_right ?></div> 
            <?php } ?>
            <?php if ($mbtnName && $mbtnLink) { ?>
              <a href="<?php echo $mbtnLink ?>" target="<?php echo $mbtnTarget ?>" class="simple-link"><?php echo $mbtnName ?></a>
            <?php } ?>
          </span>
        <?php } ?>
      </div>
      <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>

<?php } ?>