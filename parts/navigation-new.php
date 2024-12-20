<?php 
$navigation = get_field('main_menu_item', 'option');
if($navigation) { ?>
<nav class="new-nav-v2 desktop-navigation">
  <ul>
  <?php 
    // echo '<pre>';
    // print_r($navigation);
    $c = 0;
    foreach( $navigation as $nav ) {
      $c++;
      $i = 0;
      $main_link = $nav['main_link'];
      $column = ( isset($nav['column']) ) ? $nav['column'] : '';
      if( $c == 1 ) {
        $navClass = 'discover';
      } elseif ( $c == 2 ) {
        $navClass = 'visit';
      } else {
        $navClass = 'buy';
      }

   ?>
    <li class="v-menu-item <?php echo $navClass; ?> <?php if( !empty($column)){ echo 'has-children'; } ?>">
      <a class="menu-link" href="<?php echo $main_link['url']; ?>"><span><?php echo $main_link['title']; ?></span></a><!-- end main a -->
      <?php if( $column ) {  $count_columns = count($column); ?>
        <div class="mega-menu" ><div class="mega-menu-content count-<?php echo $count_columns ?>">
        <?php foreach( $column as $col ) { $i++;
            $links = $col['links']; ?>
            <div class="menu-col">
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
            </div><!-- end col -->
        <?php } ?>
        </div></div> <!-- end mega -->
      <?php } ?>

    </li><!-- end main -->
   <?php } ?>
   </ul><!-- end main -->
   
</nav>
<?php } ?>
