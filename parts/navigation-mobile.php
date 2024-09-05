<nav class="new-nav-mobile">
  <ul>
  <?php 
  
    $navigation = get_field('main_menu_item', 'option');
    // echo '<pre>';
    // print_r($navigation);

    foreach( $navigation as $nav ) {
      $i = 0;
      $main_link = $nav['main_link'];
      $column = $nav['column'];

   ?>
    <li class="menu-item <?php if( !empty($column)){ echo 'has-children'; } ?>">
      <a class="menu-link <?php if( !empty($column)){ echo 'mobile-parent-link'; } ?>" href="<?php echo $main_link['url']; ?>"  data-toggled="false"><?php echo $main_link['title']; ?></a><!-- end main a -->
      <?php if( $column ) { ?>
        <div class="mega-menu-mobile">
          <div class="mega-menu-content">
          <?php 
            foreach( $column as $col ) { $i++;
              $links = $col['links']; ?>
              <div class="menu-col is-hovered" data-wow-delay=".<?php echo $i; ?>s">
              <ul>
              <?php foreach( $links as $link ) { 
                  $link_type = $link['link_type'];
                  $sub_link = $link['sub_link'];
                  $description = $link['description'];
                ?>
                  <li class="<?php echo $link_type; ?>">
                    <a href="<?php echo $sub_link['url']; ?>"><?php echo $sub_link['title']; ?></a>
                    <?php if( $description ){ ?>
                    <span class="desc"><?php echo $description; ?></span>
                    <?php } ?>
                  </li>
              <?php } ?>
              </ul>
            </div>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
    </li><!-- end main -->
   <?php } ?>
   </ul><!-- end main -->

   <ul class="today">
      <li class="cal"><a href="<?php bloginfo('url'); ?>/calendar">Calendar</a></li>
        <li class="today-mobile" data-id="1">Today</li>
        <li class="today-mobile-dropdown"><?php get_template_part('parts/today-mobile'); ?></li>
   </ul>
</nav>
