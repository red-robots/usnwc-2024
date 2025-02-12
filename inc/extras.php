<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bellaworks
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
define('THEMEURI',get_template_directory_uri() . '/');



function add_author_support_to_posts() {
   add_post_type_support( 'activity_schedule', 'author' ); 
}
add_action( 'init', 'add_author_support_to_posts' );



// Add custom column for sticky post
function add_sticky_post_column($columns) {
    $columns['sticky_post'] = 'Sticky Post';
    return $columns;
}
add_filter('bw_sticky_posts_columns', 'add_sticky_post_column');

// Display custom column content
function sticky_post_column_content($column, $post_id) {
    if ($column == 'sticky_post') {
        $sticky = get_field('sticky_post', $post_id);
        echo $sticky ? 'Yes' : 'No';
    }
}
add_action('bw_sticky_posts_columns', 'sticky_post_column_content', 10, 2);



// Add featured image to REST API
add_action('rest_api_init', 'register_rest_images' );
function register_rest_images(){
    register_rest_field( array('post'),
        'fimg_url',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
function get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}

function add_race_to_api( $args, $post_type ) {
    if ( 'race' === $post_type ) {
        $args['show_in_rest'] = true;
    }
    return $args;
}
add_filter( 'register_post_type_args', 'add_race_to_api', 10, 2 );

function add_retreats_to_api( $args, $post_type ) {
    if ( 'retreat' === $post_type ) {
        $args['show_in_rest'] = true;
    }
    return $args;
}
add_filter( 'register_post_type_args', 'add_retreats_to_api', 10, 2 );

function add_activity_type_to_api( $args, $taxonomy ) {
    if ( 'activity_type' === $taxonomy ) {
        $args['show_in_rest'] = true;
    }
    return $args;
}
add_filter( 'register_taxonomy_args', 'add_activity_type_to_api', 10, 2 );

function add_activity_location_to_api( $args, $taxonomy ) {
    if ( 'activity_location' === $taxonomy ) {
        $args['show_in_rest'] = true;
    }
    return $args;
}
add_filter( 'register_taxonomy_args', 'add_activity_location_to_api', 10, 2 );


function bellaworks_body_classes( $classes ) {
    // Adds a class of group-blog to blogs with more than 1 published author.
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    if ( is_front_page() || is_home() ) {
        $classes[] = 'homepage';
    } else {
        $classes[] = 'subpage';
    }

    $browsers = ['is_iphone', 'is_chrome', 'is_safari', 'is_NS4', 'is_opera', 'is_macIE', 'is_winIE', 'is_gecko', 'is_lynx', 'is_IE', 'is_edge'];
    $classes[] = join(' ', array_filter($browsers, function ($browser) {
        return $GLOBALS[$browser];
    }));

    return $classes;
}
add_filter( 'body_class', 'bellaworks_body_classes' );


function add_query_vars_filter( $vars ) {
  $vars[] = "pg";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


function shortenText($string, $limit, $break=".", $pad="...") {
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }

  return $string;
}


//Changing the Menu Order
// function custom_menu_order($menu_ord) {
//   if (!$menu_ord) return true;
  
//   return array(
//     'index.php', // Dashboard 
//     'separator1', // First separator 
//     'edit.php', // Posts 
//     'upload.php', // Media 
//     'link-manager.php', // Links 
//     'edit.php?post_type=page', // Pages 
//     'edit-comments.php', // Comments 
//     'separator2', // Second separator 
//     'themes.php', // Appearance 
//     'plugins.php', // Plugins 
//     'users.php', // Users 
//     'tools.php', // Tools 
//     'options-general.php', // Settings 
//     'separator-last', // Last separator 
//   );
// }
// add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order 
// add_filter('menu_order', 'custom_menu_order');

/* Fixed Gravity Form Conflict Js */
add_filter("gform_init_scripts_footer", "init_scripts");
function init_scripts() {
    return true;
}

function get_page_id_by_template($fileName) {
    $page_id = 0;
    if($fileName) {
        $pages = get_pages(array(
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => $fileName.'.php'
        ));

        if($pages) {
            $row = $pages[0];
            $page_id = $row->ID;
        }
    }
    return $page_id;
}

function string_cleaner($str) {
    if($str) {
        $str = str_replace(' ', '', $str); 
        $str = preg_replace('/\s+/', '', $str);
        $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
        $str = strtolower($str);
        $str = trim($str);
        return $str;
    }
}

function format_phone_number($string) {
    if(empty($string)) return '';
    $append = '';
    if (strpos($string, '+') !== false) {
        $append = '+';
    }
    $string = preg_replace("/[^0-9]/", "", $string );
    $string = preg_replace('/\s+/', '', $string);
    return $append.$string;
}

function get_instagram_setup() {
    global $wpdb;
    $result = $wpdb->get_row( "SELECT option_value FROM $wpdb->options WHERE option_name = 'sb_instagram_settings'" );
    if($result) {
        $option = ($result->option_value) ? @unserialize($result->option_value) : false;
    } else {
        $option = '';
    }
    return $option;
}


function get_social_links() {
    $social_types = social_icons();
    $social = array();
    foreach($social_types as $k=>$icon) {
        if( $value = get_field($k,'option') ) {
            $social[$k] = array('link'=>$value,'icon'=>$icon);
        }
    }
    return $social;
}

function social_icons() {
    $social_types = array(
        'facebook'  => 'fab fa-facebook-square',
        'twitter'   => 'fab fa-twitter-square',
        'linkedin'  => 'fab fa-linkedin',
        'instagram' => 'fab fa-instagram',
        'youtube'   => 'fab fa-youtube',
        'vimeo'  => 'fab fa-vimeo',
    );
    return $social_types;
}

function parse_external_url( $url = '', $internal_class = 'internal-link', $external_class = 'external-link') {

    $url = trim($url);

    // Abort if parameter URL is empty
    if( empty($url) ) {
        return false;
    }

    //$home_url = parse_url( $_SERVER['HTTP_HOST'] );     
    $home_url = parse_url( home_url() );  // Works for WordPress

    $target = '_self';
    $class = $internal_class;

    if( $url!='#' ) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {

            $link_url = parse_url( $url );

            // Decide on target
            if( empty($link_url['host']) ) {
                // Is an internal link
                $target = '_self';
                $class = $internal_class;

            } elseif( $link_url['host'] == $home_url['host'] ) {
                // Is an internal link
                $target = '_self';
                $class = $internal_class;

            } else {
                // Is an external link
                $target = '_blank';
                $class = $external_class;
            }
        } 
    }

    // Return array
    $output = array(
        'class'     => $class,
        'target'    => $target,
        'url'       => $url
    );

    return $output;
}


function GetDays($sStartDate, $sEndDate){  
    // Firstly, format the provided dates.  
    // This function works best with YYYY-MM-DD  
    // but other date formats will work thanks  
    // to strtotime().  
    $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));  
    $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));  

    // Start the variable off with the start date  
    $aDays[] = $sStartDate;  

    // Set a 'temp' variable, sCurrentDate, with  
    // the start date - before beginning the loop  
    $sCurrentDate = $sStartDate;  

    // While the current date is less than the end date  
    while($sCurrentDate < $sEndDate){  
        // Add a day to the current date  
        $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  

        // Add this new day to the aDays array  
        $aDays[] = $sCurrentDate;  
    }  

    // Once the loop has finished, return the  
    // array of days.  
    return $aDays;  
}  


function get_event_date_range($start,$end,$fullMonth=false) {
    $event_date = '';
    $date_range = array($start,$end);
    if($date_range && array_filter($date_range) ) {
        $dates = array_filter( array_unique($date_range) );
        $count = count($dates);
        if($count>1) {
            $s_day =  date('d',strtotime($start));
            $e_day =  date('d',strtotime($end));
            
            /* If the same year */
            if( date("Y",strtotime($start)) == date("Y",strtotime($end)) ) {
                $year = date('Y',strtotime($start));

                if( date("m",strtotime($start)) == date("m",strtotime($end)) ) {
                    if($fullMonth) {
                        $month = date('F',strtotime($start));
                    } else {
                        $month = date('M',strtotime($start));
                    }
                    $event_date = $month . ' ' . $s_day . '-' . $e_day . ', ' . $year;
                }
                if( date("m",strtotime($start)) != date("m",strtotime($end)) ) {
                    $event_date_start = date('M',strtotime($start)) . ' ' . $s_day;
                    $event_date_end = date('M',strtotime($end)) . ' ' . $e_day;
                    $event_date = $event_date_start . ' - ' . $event_date_end . ', ' . $year;
                }

            } else {

                $year_start = date('Y',strtotime($start));
                $year_end = date('Y',strtotime($end));
                $event_date_start = date('M',strtotime($start)) . ' ' . $s_day . ', ' . $year_start;
                $event_date_end = date('M',strtotime($end)) . ' ' . $e_day . ', ' . $year_end;
                $event_date = $event_date_start . ' - ' . $event_date_end;

            }

        } else {

            if($start) {
                if($fullMonth) {
                    $event_date = date('F d, Y', strtotime($start));
                } else {
                    $event_date = date('M d, Y', strtotime($start));
                }
                
            }
            
        } 

    }
    return $event_date;
}

function date_range_no_leading_zero($start,$end,$fullMonth=false) {
    $event_date = '';
    $date_range = array($start,$end);
    if($date_range && array_filter($date_range) ) {
        $dates = array_filter( array_unique($date_range) );
        $count = count($dates);
        if($count>1) {
            $s_day =  date('j',strtotime($start));
            $e_day =  date('j',strtotime($end));
            
            /* If the same year */
            if( date("Y",strtotime($start)) == date("Y",strtotime($end)) ) {
                $year = date('Y',strtotime($start));

                if( date("m",strtotime($start)) == date("m",strtotime($end)) ) {
                    if($fullMonth) {
                        $month = date('F',strtotime($start));
                    } else {
                        $month = date('M',strtotime($start));
                    }
                    $event_date = $month . ' ' . $s_day . '-' . $e_day . ', ' . $year;
                }
                if( date("m",strtotime($start)) != date("m",strtotime($end)) ) {
                    $event_date_start = date('M',strtotime($start)) . ' ' . $s_day;
                    $event_date_end = date('M',strtotime($end)) . ' ' . $e_day;
                    $event_date = $event_date_start . ' - ' . $event_date_end . ', ' . $year;
                }

            } else {

                $year_start = date('Y',strtotime($start));
                $year_end = date('Y',strtotime($end));
                $event_date_start = date('M',strtotime($start)) . ' ' . $s_day . ', ' . $year_start;
                $event_date_end = date('M',strtotime($end)) . ' ' . $e_day . ', ' . $year_end;
                $event_date = $event_date_start . ' - ' . $event_date_end;

            }

        } else {

            if($start) {
                if($fullMonth) {
                    $event_date = date('F j, Y', strtotime($start));
                } else {
                    $event_date = date('M j, Y', strtotime($start));
                }
                
            }
            
        } 

    }
    return $event_date;
}

add_action('admin_head', 'my_custom_admin_css');
function my_custom_admin_css() { ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/css/admin.css">
<style type="text/css">
[data-name="child_menu_pagelink"] .acf-label {position:relative;}
[data-name="child_menu_pagelink_target"],
#menu-posts-instructions ul.wp-submenu li:last-child{display:none!important}
<?php 
$has_expiration_post_types = array('festival','music'); 
foreach($has_expiration_post_types as $pt) { ?>
    body.post-type-<?php echo $pt?> div.acf-field[data-name="is_event_completed"] h2.hndle,
    body.post-type-<?php echo $pt?> div.acf-field[data-name="is_event_completed"] .acf-input,
    body.post-type-<?php echo $pt?> div.acf-field[data-name="is_event_completed"] .acf-label label {
        display: none;
    }
<?php } ?>


.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_name"] .acf-checkbox-list [type="checkbox"],
.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_location"] .acf-checkbox-list [type="checkbox"] {
  position: absolute;
  pointer-events: none;
  z-index: -999;
  visibility: hidden;
}
.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_name"] .acf-checkbox-list label a i,
.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_location"] .acf-checkbox-list label a i {
  display: inline;
  position: relative;
  top: -5px;
  text-decoration: none;
}
</style>
<?php }

/*===== START ADMIN CUSTOM SCRIPTS ======*/
add_action('admin_footer', 'my_custom_admin_js');
function my_custom_admin_js() { ?>
<script type="text/javascript">
jQuery(document).ready(function($){


  // if( $('.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_name"] .acf-checkbox-list li').length ) {
  //   $('.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_name"] .acf-checkbox-list li').each(function(){
  //     var termId = $(this).attr('data-id');
  //     var adminUrl = '<?php echo get_admin_url() ?>term.php?taxonomy=whitewater-location&tag_ID='+termId+'&post_type=activity_schedule';
  //     if( $(this).find('label span').length ) {
  //       var termName = $(this).find('label span').text();
  //       $(this).find('label span').replaceWith('<a href="'+adminUrl+'" target="_blank">'+termName+' <i class="dashicons dashicons-edit"></i></a>');
  //     }
  //   });
  // }

  if( $('ul#adminmenu li.wp-has-submenu#menu-posts-special-events').length && $('ul#adminmenu li.wp-has-submenu#menu-posts-tribe_events').length ) {
    $('ul#adminmenu li.wp-has-submenu#menu-posts-special-events').insertAfter('ul#adminmenu li.wp-has-submenu#menu-posts-tribe_events');
  }


  if( $('.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_name"] .acf-checkbox-list li').length || $('.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_location"] .acf-checkbox-list') ) {
    let branchNameEl = '.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_name"] .acf-checkbox-list';
    let branchLocEl = '.acf-field-taxonomy[data-type="taxonomy"][data-name="branch_location"] .acf-checkbox-list';

    let previousHTML_branchName = $(branchNameEl).html();
    let previousHTML_branchLoc = $(branchLocEl).html();

    setInterval(function() {
      const currentHTML_branchName = $(branchNameEl).html();
      const currentHTML_branchLoc = $(branchLocEl).html();

      if (currentHTML_branchName !== previousHTML_branchName) {
        detectBranchChanges();
        previousHTML_branchName = currentHTML_branchName;
      }

      if (currentHTML_branchLoc !== previousHTML_branchLoc) {
        detectBranchChanges();
        previousHTML_branchLoc = currentHTML_branchLoc;
      }
    }, 200); // Check every 500 milliseconds
  
    detectBranchChanges();
    function detectBranchChanges() {
      if( $(branchNameEl).find('li').length ) {
        $(branchNameEl).find('li').each(function(){
          var termId = $(this).attr('data-id');
          var adminUrl = '<?php echo get_admin_url() ?>term.php?taxonomy=whitewater-location&tag_ID='+termId+'&post_type=activity_schedule';
          if( $(this).find('label span').length ) {
            var termName = $(this).find('label span').text();
            $(this).find('label span').replaceWith('<a href="'+adminUrl+'" target="_blank">'+termName+' <i class="dashicons dashicons-edit"></i></a>');
          }
        });
      }

      if( $(branchLocEl).find('li').length ) {
        $(branchLocEl).find('li').each(function(){
          var termId = $(this).attr('data-id');
          var adminUrl = '<?php echo get_admin_url() ?>term.php?taxonomy=whitewater-location-city&tag_ID='+termId+'&post_type=activity_schedule';
          if( $(this).find('label span').length ) {
            var termName = $(this).find('label span').text();
            $(this).find('label span').replaceWith('<a href="'+adminUrl+'" target="_blank">'+termName+' <i class="dashicons dashicons-edit"></i></a>');
          }
        });
      }
    }
  }



  if( $('body').hasClass('post-type-race') ) {
    if( $('#side-sortables ul.acf-hl.acf-tab-group li').length ) {
      $('#side-sortables ul.acf-hl.acf-tab-group li').each(function(){
        if( $(this).text().trim()!='1:1') {
          $(this).hide();
        }
      });
    }
  }
  

  if ( typeof acf !== 'undefined' ) {
    acf.add_action( 'wysiwyg_tinymce_init', function( ed, id, mceInit, $field ) {
      //console.log(ed);
      // set height of wysiwyg on frontend
      var minHeight = 200;
      // var mceHeight = $( ed.iframeElement ).contents().find( 'html' ).height() || minHeight;
      // if ( mceHeight < minHeight ) {
      //   mceHeight = minHeight;
      // }
      $( ed.iframeElement ).css( 'height', minHeight );
    } );
  }


    /* Go to STORIES TEXT field */
    <?php if( isset($_GET['fsec']) && $_GET['fsec']=='stories-text' ) { ?>
        $('ul.acf-hl.acf-tab-group li').each(function(){
            if( $(this).text()=='Single Post Options' ) {
                $(this).find('a').trigger('click');
                $('html, body').animate(
                    {
                      scrollTop: $('[data-name="stories_text"] .wp-editor-wrap').offset().top,
                    },
                    500,
                    'linear'
                );

            } 
        });
    <?php } ?>

    $('[data-name="call-to-actions"] tr.acf-row').each(function(){
        $(this).find("td.acf-field-link").append('<a href="#" title="Click to copy" class="txtshortcode">[call-to-action]</a>');
    });

    $(document).on("click","a.txtshortcode",function(e){
        e.preventDefault();
        var parent = $(this).parents("tr.acf-row");
        var num = parent[0].rowIndex;
        var code = '[call-to-action id="'+num+'"]';
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(code).select();
        document.execCommand("copy");
        $temp.remove();
        var msg = '<i class="copied">Copied to clipboard!</i>';
        $(msg).insertAfter( parent.find('.txtshortcode') );
        setTimeout(function(){
            parent.find('.copied').hide();
        },500);
    });

    /* Move `Child Menu Pagelink Target` */
    $('[data-name="child_menu_pagelink_target"]').each(function(){
        var parent = $(this).parents('[data-layout="child_menu_data"]');
        $(this).find('.acf-input').appendTo( )
        parent.find('[data-name="child_menu_pagelink_target"] .acf-input').appendTo( parent.find('[data-name="child_menu_pagelink"] .acf-input-wrap') );
    });

    /* ACF Flexible Content For River Jam Bands */
    if( $('[data-layout="schedule"]').length > 0 ) {
        $('[data-layout="schedule"]').each(function(){
            var str = $(this).find('[data-name="day"] .acf-input select').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    // River Jam Options - Rename Link
    if( $("#adminmenu li#menu-posts-music").length>0 ) {
        $("#adminmenu li#menu-posts-music .wp-submenu a").each(function(){
            var txt = $(this).text();
            if(txt=='River Jam Options') {
                $(this).text('Generic Options');
            }
            if(txt=='River Jam Programming') {
                var tabURL = '<?php echo admin_url('edit.php?post_type=jam-programs') ?>';
                $(this).attr('href',tabURL);
                $(this).text('Programming');
                <?php if( isset($_GET['post_type']) && $_GET['post_type']=='jam-programs') { ?>
                $(this).parent().addClass('current');
                $(this).addClass('current');
                <?php } ?>
            }
        });

        <?php if( isset($_GET['post_type']) && $_GET['post_type']=='jam-programs') { ?>
            $("#adminmenu li#menu-posts-music").removeClass("menu-top").addClass("wp-has-submenu wp-has-current-submenu wp-menu-open menu-top menu-icon-music");
            $("#adminmenu li#menu-posts-music a.wp-has-submenu").addClass('wp-has-current-submenu wp-menu-open');
        <?php } ?>
    }



    /* ACF Flexible Content For River Jam Schedules */
    if( $('[data-name="other_activities"]').length > 0 ) {
        $('[data-layout="other_activity"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    /* ACF Flexible Content For Educational Adventure */
    if( $('[data-name="prog_repeater_blocks"]').length > 0 ) {
        $('[data-layout="section"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    /* ACF Flexible Content For Buy Page */
    if( $('[data-name="repeater_blocks"]').length > 0 ) {
        $('[data-name="repeater_blocks"] [data-layout="section"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    if( $('.acf-flexible-content [data-layout="section2"]').length > 0 ) {
        $('.acf-flexible-content [data-layout="section2"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    /* ACF Flexible Content For Group Events Itineraries */
    if( $('[data-name="accordion_content"]').length > 0 ) {
        $('[data-layout="accordion_entry"]').each(function(){
            var str = $(this).find('[data-name="grouptitle"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    /* ACF Flexible Content For Group Events Services */
    if( $('[data-name="groupevents_featuredservices"]').length > 0 ) {
        $('[data-layout="services"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    /* ACF Flexible Content For Camp Activities */
    if( $('[data-name="activities_flexcontent"]').length > 0 ) {
        $('[data-layout="activities"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    /* Catering Services (Weddings) */
    if( $('[data-layout="catering"]').length > 0 ) {
        $('[data-layout="catering"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }

    /* ACF Flexible Content for Menu Options */
    // if( $("#acf-group_5f1912cfb5ecf").length > 0 ) {
    //     $('[data-layout="menu_group"]').each(function(){
    //         var parent = $(this).find('[data-name="parent_menu_name"] .acf-input-wrap input').val();
    //         var parentMenu = ( parent.replace(/\s+/g,'').trim() ) ? parent.replace(/\s+/g,' ').trim() : '(Blank)';
    //         var parentMenu_child = ( parent.replace(/\s+/g,'').trim() ) ? parent.replace(/\s+/g,' ').trim() : '';
    //         $(this).attr("data-parenttext",parentMenu);
    //         $(this).find('[data-layout="child_menu_data"]').attr("data-parenttext","- "+parentMenu_child);
    //     });
    // }

    if( $('.acf-flexible-content [data-name="parent_menu_name"]').length > 0 ) {
        $('.acf-flexible-content [data-name="parent_menu_name"]').each(function(){
            var parent = $(this).parents('.layout');
            var parentMenu = $(this).find('.acf-input-wrap input').val();
            var parentStr = parentMenu.replace(/\s+/g,'').trim();
            if(parentStr) {
                var parentMenuTxt = parentMenu.replace(/\s+/g,' ').trim();
                parent.addClass('show-title');
                parent.find('.acf-fc-layout-handle').attr('data-title',parentMenuTxt);
            }
            parent.find('[data-layout="child_menu_data"]').each(function(){
                var target = $(this);
                var subparentTxt = $(this).find('[data-name="child_menu_name"] .acf-input-wrap input').val();
                var subStr = subparentTxt.replace(/\s+/g,'').trim();
                if(subStr) {
                    var subMenuTxt = subparentTxt.replace(/\s+/g,' ').trim();
                    target.find('.acf-fc-layout-handle').attr('data-title',subMenuTxt);
                    target.addClass('show-title');
                }
            });
            
        });
    }


    if( $('.post-type-race .acf-flexible-content [data-layout="race_type"]').length > 0 ) {
        $('.post-type-race .acf-flexible-content [data-layout="race_type"]').each(function(){
            var parent = $(this);
            var parentMenu = $(this).find('[data-name="name"] .acf-input-wrap input').val();
            var parentStr = parentMenu.replace(/\s+/g,'').trim();
            var parentMenuTxt = (parentStr) ? parentMenu.replace(/\s+/g,' ').trim() : '(Untitled)';
            parent.find('.acf-fc-layout-handle').attr('data-title',parentMenuTxt);
            parent.addClass('show-title');
            
        });
    }

    // if( $('[data-layout="race_type').length > 0 ) {
    //     $('[data-layout="race_type').each(function(e){
    //         var tab = $(this).find('[data-name="name"] .acf-input-wrap input').val();
    //         var tabName = ( tab.replace(/\s+/g,'').trim() ) ? tab.replace(/\s+/g,' ').trim() : '(Untitle)';
    //         $(this).attr("data-parenttext",tabName);
    //     });
    // }


    if( ($('body.post-php div.acf-field[data-name="is_event_completed"]').length > 0) && ($("#expirationdatediv").length > 0) ) {
        $("#expirationdatediv").appendTo('div.acf-field[data-name="is_event_completed"]');
        //var acfLabel = $('body.post-php div.acf-field[data-name="is_event_completed"] .acf-label label').text();
        var acfLabel = 'Enable post expiration if event is completed';
        $('div.acf-field #expirationdatediv label[for="enable-expirationdate"]').html('<strong>'+acfLabel+'</strong>');
    }

    if( $("#acf-group_5f460b222fe52").length > 0 ) {
        $('[data-layout="activity"]').each(function(){
            var parent = $(this).find('[data-name="type"] .acf-input-wrap input').val();
            var tabTitle = ( parent.replace(/\s+/g,'').trim() ) ? parent.replace(/\s+/g,' ').trim() : '(Untitled)';
            //var parentMenu_child = ( parent.replace(/\s+/g,'').trim() ) ? parent.replace(/\s+/g,' ').trim() : '';
            //$(this).find(".acf-fc-layout-handle").attr("data-parenttext",parentMenu);
            $(this).find(".acf-fc-layout-handle").append('<span class="customTabName">'+tabTitle+'</span>');
        });
        $(document).on("keyup",'[data-layout="activity"] [data-name="type"] .acf-input-wrap input',function(){
            var str = $(this).val().replace(/\s+/g,' ').trim();
            var parent = $(this).parents('[data-layout="activity"]');
            parent.find(".customTabName").text(str);
        });
    }

    /* ACF Flexible Content For Festival Activities */
    if( $('.acf-field-flexible-content [data-layout="festivalActivities"]').length > 0 ) {
        $('.acf-field-flexible-content [data-layout="festivalActivities"]').each(function(){
            var str = $(this).find('[data-name="day"] .acf-input select option:selected').text();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }


    if( $("body.wp-admin.post-type-activity_schedule").length > 0 ) {
        //$('[data-name="eventDateSchedule"] input.hasDatepicker').focus();
        $("#titlewrap").append('<span class="cover"></span>');
        $("#titlewrap input").blur();
        $('[data-name="eventDateSchedule"]').focus();
    }

    /* select custom icons */
    if( $('div.acf-field[data-name="custom_icon"]').length>0 ) {
        var customIconDiv = $('div.acf-field[data-name="custom_icon"]');
        //var input = $(this).find('div.acf-field[data-name="custom_icon"] .acf-input-wrap input');
        // var input = $('div.acf-field[data-name="custom_icon"] .acf-input-wrap input');
        // var id = input.attr("id");
        // var icon = input.val();
        // var label = (icon) ? 'Edit' : 'Add Icon';
        // var btn = '<span class="cusIcon"><i class="'+icon+'"></i></span><a href="#" data-icon="'+icon+'" class="iconOptBtn">'+label+'</a>';
        // $(btn).insertAfter(input);
        customIconDiv.each(function(){
            var input = $(this).find('.acf-input-wrap input');
            var id = input.attr("id");
            var icon = input.val();
            var label = (icon) ? 'Edit' : 'Add Icon';
            var btn = '<span class="cusIcon"><i class="'+icon+'"></i></span><a href="#" data-icon="'+icon+'" class="iconOptBtn">'+label+'</a>';
            $(btn).insertAfter(input);
        });
        
        $(document).on("click",".customIconBtn",function(e){
            e.preventDefault();
            $("#customIconsContainer").show();
        });
        $(document).on("click",".iconOptBtn",function(e){
            e.preventDefault();
            var icon = $(this).attr("data-icon");
            //var parent = customIconDiv;
            var parent = $(this).parents('[data-name="custom_icon"]');
            var inputId = parent.find(".acf-input-wrap input").attr("id");
            $("#customIconsContainer").show();
            parent.addClass("selected");
            if(icon) {
                //$("#customIconsContainer").attr("data-assign",inputId);
                $('.iconBox .w[data-icon="'+icon+'"]').parent().addClass("selected");
            }
        });

        $(document).on("click","#closeIconList",function(e){
            e.preventDefault();
            $("#customIconsContainer").hide();
            $("#customIconsContainer").attr("data-assign","");
            $("#customIconsContainer .iconBox").removeClass('selected');
            $('div.acf-field[data-name="custom_icon"]').removeClass('selected');
        });
        $(document).on("click",".iconBox .w",function(e){
            e.preventDefault();
            var icon = $(this).attr("data-icon");
            // var input = customIconDiv.find('.acf-input-wrap input');
            // var inputId = $("#customIconsContainer").attr("data-assign");
            // var parent = customIconDiv;
            var parent = $('div.acf-field[data-name="custom_icon"].selected');
            var input = parent.find('.acf-input-wrap input');
            input.val(icon);
            parent.find("span.cusIcon i").removeAttr("class").addClass(icon);
            parent.find(".iconOptBtn").attr("data-icon",icon);
            parent.find(".iconOptBtn").text('Edit');
            $("#closeIconList").trigger("click");
        });
    }

    if( $('th[data-name="custom_icon"]').length>0 ) {
        // $('th[data-name="custom_icon"]').each(function(){
        //     var iconLink = '<a href="#" class="customIconBtn">Click here to select icons</a>';
        //     $(this).append(iconLink);
        // });

        $('tbody td[data-name="custom_icon"]').each(function(k){
            var ctr = k+1;
            var input = $(this).find(".acf-input-wrap input");
            var id = input.attr("id");
            var icon = input.val();
            var label = (icon) ? 'Edit' : 'Add Icon';
            var btn = '<span class="cusIcon"><i class="'+icon+'"></i></span><a href="#" data-icon="'+icon+'" class="iconOptBtn">'+label+'</a>';
            $(btn).insertAfter(input);
        });
        
        $(document).on("click",".customIconBtn",function(e){
            e.preventDefault();
            $("#customIconsContainer").show();
        });
        $(document).on("click","#closeIconList",function(e){
            e.preventDefault();
            $("#customIconsContainer").hide();
            $("#customIconsContainer").attr("data-assign","");
            $("#customIconsContainer .iconBox").removeClass('selected');
        });
        $(document).on("click",".iconBox .w",function(e){
            e.preventDefault();
            var icon = $(this).attr("data-icon");
            var inputId = $("#customIconsContainer").attr("data-assign");
            var parent = $('td[data-name="custom_icon"]').find("input#"+inputId).parent();
            $(('td[data-name="custom_icon"]')).find("input#"+inputId).val(icon);
            parent.find("span.cusIcon i").removeAttr("class").addClass(icon);
            parent.find(".iconOptBtn").attr("data-icon",icon);
            parent.find(".iconOptBtn").text('Edit');
            $("#closeIconList").trigger("click");
        });

        $(document).on("click",".iconOptBtn",function(e){
            e.preventDefault();
            var icon = $(this).attr("data-icon");
            var parent = $(this).parents('td[data-name="custom_icon"]');
            var inputId = parent.find(".acf-input-wrap input").attr("id");
            $("#customIconsContainer").show();
            $("#customIconsContainer").attr("data-assign",inputId);
            $('.iconBox .w[data-icon="'+icon+'"]').parent().addClass("selected");
        });

        function copyToClipboard(str) {
          var $temp = $("<input>");
          $("body").append($temp);
          $temp.val(str).select();
          document.execCommand("copy");
          $temp.remove();
        }
    }

    /* Flexible Content that has a 'title' field. Title appears on the collapse handle */
    if( $('body').hasClass('post-type-race') ) {
        if( $('.acf-flexible-content [data-layout="text_and_image"] .acf-field-text[data-name="title"]').length > 0 ) {
            $('.acf-flexible-content [data-layout="text_and_image"] .acf-field-text[data-name="title"]').each(function(){
                var parent = $(this).parents(".layout");
                parent.addClass('show-title');
                var str = $(this).find('.acf-input-wrap input').val();
                var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
                parent.find(".acf-fc-layout-handle").attr("data-title",title);
            });
        }
    } else {
        if( $('.acf-flexible-content').length ) {
          $('.acf-flexible-content').each(function(){
            if( $(this).find('[data-layout="pricing_cards"]').length ) {

              $(this).find('[data-layout]').each(function(){
                var layoutDiv = $(this);
                if( $(this).find('.acf-field-text[data-name="title"]').length ) {
                  var titleDiv = $(this).find('.acf-field-text[data-name="title"]').eq(0);
                  var titleStr = titleDiv.find('.acf-input-wrap input').val().trim();
                  if( typeof titleStr!=="undefined" ) {
                    layoutDiv.find('.acf-fc-layout-handle').attr("data-title",titleStr);
                  }
                }
              });
            }
          });
        }

        // if( $('.acf-flexible-content .acf-field-text[data-name="title"]').length > 0 ) {
        //     $('.acf-field-text[data-name="title"]').each(function(){
        //         var parent = $(this).parents(".layout");
        //         parent.addClass('show-title');
        //         var str = $(this).find('.acf-input-wrap input').val();
        //         if( $(this).parents('.acf-repeater.-table').length==0 ) {
        //           var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
        //           parent.find(".acf-fc-layout-handle").attr("data-title",title);
        //         }
        //     });
        // }
    }

    /* Activity Schedule */
    $('.acf-flexible-content [data-name="activities"] td[data-name="types"]').each(function(){
        var parent = $(this).parent();
        var fields = $(this).find('.acf-input');
        var nameCol = parent.find('[data-name="name"]');
        $(fields).prependTo(nameCol);
    });

    $('.acf-flexible-content [data-name="activities"] td[data-name="name"]').each(function(){
        var parent = $(this).parent();
        var name = parent.find('[data-name="customText"] .acf-input-wrap').addClass('acf-input-custom customName');
        //var link = parent.find('[data-name="customLink"] .acf-input-wrap').addClass('acf-input-custom customLink');
        name.appendTo( $(this) );
        //link.appendTo( $(this) );
    });

    $(document).on('click','.acf-flexible-content [data-name="activities"] td[data-name="name"] .acf-radio-list input[type="radio"]',function(){
        var parent = $(this).parents('tr');
        if( this.checked ) {
            if( $(this).val()=='custom' ) {
                parent.addClass('show-custom');
            } else {
                parent.removeClass('show-custom');
            }
        }
    });

    $('.acf-flexible-content [data-name="activities"] td[data-name="name"] .acf-radio-list input[type="radio"]').each(function(){
        var parent = $(this).parents('tr');
        if( this.checked ) {
            if( $(this).val()=='custom' ) {
                parent.addClass('show-custom');
            } else {
                parent.removeClass('show-custom');
            }
        }
    });

    /* Page with flexible content fields */
    if( $('[data-name="flexible_content_page"]').length>0 ) {
        $('[data-name="flexible_content_page"] [data-layout="text_and_image_block"]').each(function(){
            var parentDIV = $(this);
            var columnType = parentDIV.find('[data-name="column_style"]');
            var columnTypeOptions = columnType.find('.acf-radio-list input[type="radio"]');
            var label = '';
            columnTypeOptions.each(function(){
                if( this.checked ) {
                    var opt = $(this).val();
                    label = $(this).parent().text();
                    parentDIV.addClass("type-"+opt);
                }
            });

            parentDIV.find('.acf-fc-layout-handle').eq(0).addClass('parent-handle');
            parentDIV.find('.acf-fc-layout-handle').eq(0).attr("data-title",label+" Section");
            parentDIV.find('[data-layout="flex_content_block"]').each(function(){
                var block_title = $(this).find('[data-name="title"] .acf-input-wrap input').val();
                $(this).find('.acf-fc-layout-handle').attr("data-title",block_title);
            }); 

        });
    }

});
</script>
<div id="customIconsContainer">
<?php echo displayCustomIcons(); ?>
</div>
<?php
}
/*===== END ADMIN CUSTOM SCRIPTS ======*/

/*===== CUSTOM ICONS ======*/
// $test = 'https://wwc.bellaworksdev.com/wp-content/themes/usnwc-2020/assets/sass/_fonts.scss';
// ob_start();
// $content = file_get_contents($test);
// ob_get_contents();
// ob_end_clean();
// print_r($content);


displayCustomIcons();
function displayCustomIcons() {
    $fonts = '';
    $fontsFile = get_template_directory() . '/assets/sass/_fonts.scss';
    ob_start();
    include($fontsFile);
    $fonts = ob_get_contents();
    ob_end_clean();
    $parts = explode("/*==custom icons==*/",$fonts);
    $fontStyle = '';
    if( isset($parts[2]) ) {
        unset($parts[2]);
        $fontStyle = implode("",$parts);
    }
    
    $fontURL = get_stylesheet_directory_uri() . '/fonts/';
    //$path = get_stylesheet_directory_uri() . '/assets/sass/_fonts.scss';
    //$content = file_get_contents($path);
    $content = str_replace("fonts/",$fontURL,$fontStyle);
    $styleSheet = $content;
    $iconList = array();
    $output = '';
    ob_start();
    if( isset($parts[1]) && $parts[1] ) {
        $iconData = str_replace(" ","",$parts[1]);
        $iconsClasses = preg_replace('/\s+/', '', $iconData);
        $icons = explode(";}",$iconsClasses);
        if( $lists = array_filter($icons) ) {
            foreach($lists as $str) {
                $strings = explode(":before",$str);
                $iconList[] = str_replace(".","",$strings[0]);
            }
            if($iconList) { ?>
            <style type="text/css"><?php echo $styleSheet ?></style>
            <div class="customIconList">
                <a href="#" id="closeIconList"><span>x</span></a>
                <div class="iconContainer">
                <?php foreach($iconList as $i) {?>
                <div class="iconBox">
                    <div class="w" data-icon="<?php echo $i ?>">
                        <i class="iconClass <?php echo $i?>"></i>
                        <div class="iconName">.<?php echo $i ?></div>
                    </div>
                </div>
                <?php } ?>
                </div>
            </div>
            <?php }
        }
    }
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

/* ACF CUSTOM OPTIONS TABS */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
}
function be_acf_options_page() {
    if ( ! function_exists( 'acf_add_options_page' ) ) return;
    
    $acf_option_tabs = array(
        array( 
            'title'      => 'Today Options',
            'capability' => 'manage_options',
        ),
        array( 
            'title'      => 'Menu Options',
            'capability' => 'manage_options',
        ),
        array( 
            'title'      => 'Global Options',
            'capability' => 'manage_options',
        )
    );

    foreach($acf_option_tabs as $options) {
        acf_add_options_page($options);
    }
}
add_action( 'acf/init', 'be_acf_options_page' );

/* Options page under Story custom post type */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_sub_page(array(
        'page_title'     => 'Other Activities',
        'menu_title'    => 'Other Activities',
        'parent_slug'    => 'edit.php?post_type=activity'
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'River Jam Programming',
        'menu_title'    => 'River Jam Programming',
        'parent_slug'   => 'edit.php?post_type=music'
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'River Jam Options',
        'menu_title'    => 'River Jam Options',
        'parent_slug'   => 'edit.php?post_type=music'
    ));
}

function get_vimeo_data($vimeoId) {
    if (empty($vimeoId)) return '';
    $obj = @unserialize(file_get_contents("https://vimeo.com/api/v2/video/".$vimeoId.".php"));
    return ($obj) ? $obj[0] : '';
    //$json_url = 'http://vimeo.com/api/v2/video/'.$vimeoId.'.json?callback=showThumb';
    // $json_data = @file_get_contents($json_url);
    // if($json_data) {
    //     $json_parts = str_replace("/**/showThumb(","",$json_data);
    //     $json_parts = str_replace("}])","}]",$json_parts);
    //     $data = json_decode($json_parts);
    //     return ($data) ? $data[0] : '';
    // }
}

function get_pass_type_category($term_id) {
    $taxonomy = 'pass_type';
    $args = array(
        'posts_per_page'=> -1,
        'post_type'     => 'pass',
        'post_status'   => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_id
            )
        )
        
    );
    $posts = get_posts($args);
    return ($posts) ? $posts : '';
}


function get_faqs_by_single_post($post_id) {
    global $wpdb;
    $faq_posts = array();
    $faq_post_types = array();

    $post_type = get_post_type($post_id);

    $query = "SELECT p.ID, m.meta_value as content_type FROM {$wpdb->posts} p, {$wpdb->postmeta} m
              WHERE m.post_id=p.ID AND p.post_type='faqs' AND p.post_status='publish' AND m.meta_key='content'";
    $result = $wpdb->get_results($query);
    if($result) {
        foreach($result as $row) {
            $faq_post_id = $row->ID;
            $type = $row->content_type; /* single or multiple */
            if($type) {
                $metaKey = $type . '_type'; /* This may result `single_type` or `multiple_type` */ 
                $meta = get_content_type_data($faq_post_id,$metaKey);
                if($type=='single') {
                    
                    if($meta) {
                        if( $metaPostIds = $meta->meta_value ) {
                            if( in_array($post_id, $metaPostIds) ) {
                                $faq_posts[] = $faq_post_id;
                            }
                        }
                    }
                } elseif($type=='multiple') {
                    /* get post type assigned */
                    if($post_type) {
                        if( $assigned_posttypes = $meta->meta_value ) {
                            if( in_array($post_type, $assigned_posttypes) ) {
                                $faq_posts[] = $faq_post_id;
                            }
                        }
                    }
                }
            }
        }
    }

    return $faq_posts;
}


function get_content_type_data($post_id,$metaKey) {
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->postmeta} m 
              WHERE m.post_id=".$post_id." AND m.meta_key='".$metaKey."'";
    $result = $wpdb->get_row($query);
    if($result) {
        $metaVal = ($result->meta_value) ? @unserialize($result->meta_value) : '';
        $result->meta_value = $metaVal;
    }
    return ($result) ? $result : '';
}

function get_faqs_by_assigned_page_id($postIds) {
    $faqs = array();
    if( empty($postIds) ) return '';
    foreach($postIds as $id) {
        $parent = get_the_title($id);
        $children = get_field("faqs",$id);
        if($children) {
            $item['ID'] = $id;
            $item['title'] = $parent;
            $item['faqs'] = $children;
            $faqs[] = $item;
        }
    }

    return $faqs;
}


function get_faq_listings($post_id) {
    $faqs = '';
    if( $faqsIds = get_faqs_by_single_post($post_id) ) {
        $faqs = get_faqs_by_assigned_page_id($faqsIds);
    }
    return $faqs;
}

function get_page_id_by_permalink($url) {
    global $wpdb;
    $siteURL = get_site_url();
    if( empty($url) ) return '';

    /* Check if internal or external link */
    $parse = parse_external_url($url);
    $basename = basename($url);
    if( $parse['class']=='internal-link' ) {
        $query = "SELECT p.ID, p.post_type FROM {$wpdb->posts} p 
                  WHERE p.post_name='".$basename."'";
        $result = $wpdb->get_row($query);
        return ($result) ? $result : '';
    } else {
        return '';
    }
}

function getActivityScheduleToday($locationSlug) {
  global $wpdb;
  $wp = $wpdb->prefix;
  $todayDate = date('Ymd');
  $query = "SELECT p.ID, meta.meta_value AS event_schedule, term.term_id, term.name AS term_name FROM {$wp}posts p, {$wp}postmeta meta, {$wp}term_relationships rel, {$wp}terms term  
            WHERE p.ID=meta.post_id AND meta.meta_key='eventDateSchedule' AND meta.meta_value='{$todayDate}' 
            AND p.post_status='publish' AND p.post_type='activity_schedule' AND p.ID=rel.object_id 
            AND rel.term_taxonomy_id=term.term_id AND term.slug='{$locationSlug}'";
  $result = $wpdb->get_row($query);
  return $result;
}

function get_categories_by_page_id($post_id,$taxonomy,$related=null) {
    global $wpdb;
    $related_posts = array();
    $related_terms = ( isset($related['terms']) && $related['terms'] ) ? $related['terms'] : '';
    $related_taxonomy = ( isset($related['taxonomy']) && $related['taxonomy'] ) ? $related['taxonomy'] : '';
    
    $related_post_type = ( isset($related['post_type']) && $related['post_type'] ) ? $related['post_type'] : '';

    $query = "SELECT rel.object_id as post_id, tax.term_id, terms.name, terms.slug, tax.taxonomy FROM {$wpdb->terms} terms, {$wpdb->term_taxonomy} tax, {$wpdb->term_relationships} rel
              WHERE rel.term_taxonomy_id=tax.term_taxonomy_id AND tax.term_id=terms.term_id AND tax.taxonomy='".$taxonomy."' AND rel.object_id=".$post_id;

    $result = $wpdb->get_results($query);
    if($result) {
      foreach($result as $row) {
        $term_id = $row->term_id;
        $query2 = "SELECT p.*, terms.term_id, terms.name, terms.slug, tax.taxonomy FROM {$wpdb->posts} p, {$wpdb->terms} terms, {$wpdb->term_taxonomy} tax, {$wpdb->term_relationships} rel
                  WHERE p.ID=rel.object_id AND p.post_type='".$related_post_type."' AND p.post_status='publish' 
                  AND rel.term_taxonomy_id=tax.term_taxonomy_id AND tax.term_id=".$term_id." AND terms.term_id=tax.term_id GROUP BY p.ID ORDER BY p.post_date DESC";
        $related = $wpdb->get_results($query2);

        $limit = 2;
        if($related) {
          $i=1; foreach($related as $rel) {
            $post_terms = get_the_terms($rel,$related_taxonomy);
            if($post_terms) {
              foreach($post_terms as $p) {
                $p_term_slug = $p->slug;
                
                if( $related_terms && in_array($p_term_slug,$related_terms) ) {

                  $related_term_id = $p->related_terms;
                  
                  if($i<=$limit) {
                    $rel->related_terms = $p;
                    $related_posts[] = $rel; 
                  }

                  $i++;
                }

              }
            }
          }
        }

      }
    }

    return $related_posts;
}


function get_current_activity_schedule($postype) {
    global $wpdb;
    $dateNow = date('Y-m-d  h:i:s A');
    ///$today = date('l, F jS, Y');
    //$today_slug = sanitize_title($today);
    // need to get off Greenwhich meantime
    $dateNowMod = date('Y-m-d', strtotime($dateNow) - 60 * 60 * 5);

    $today_slug = sanitize_title($dateNowMod);

    $query = "SELECT * FROM {$wpdb->posts} p WHERE p.post_type='".$postype."' AND p.post_status='publish' AND p.post_name='".$today_slug."'";
    $result = $wpdb->get_row($query);
    return ($result) ? $result : '';
}

function update_post_status_if_expired() {
   global $wpdb;
   $dateNow = date('Y-m-d');
   $dateNowStr = strtotime( date('Ymd') );
   $query = "SELECT p.ID,p.post_title,p.post_type, date_format(str_to_date(m.meta_value, '%Y%m%d'),'%Y-%m-%d') AS start_date
           FROM {$wpdb->posts} p,{$wpdb->postmeta} m
           WHERE p.ID=m.post_id AND p.post_status='publish' 
           AND m.meta_key='start_date' AND (m.meta_value IS NOT NULL OR m.meta_value <> '') ORDER BY start_date ASC";
   $result = $wpdb->get_results($query); 
   $last_updated = get_last_post_status_updated();

   $exception = array('completed','canceled');
   $is_updated = array();

   if( empty($last_updated) || $last_updated!=$dateNow ) {

      if($result) {
         foreach($result as $row) {
            $post_id = $row->ID;
            $start_date = $row->start_date;
            $end_date = get_field("end_date",$post_id);
            $status = get_field('eventstatus',$post_id);
            $based_date = ($start_date) ? strtotime($start_date) : '';
            if($end_date) {
               $based_date = strtotime($end_date);
            }
            $row->eventstatus = $status;
            if($based_date) {
               if( !in_array($status,$exception) ) {
                  if($based_date<$dateNowStr) {
                     $updated = update_post_meta($post_id,'eventstatus','completed');
                     if($updated) {
                        $is_updated[] = $post_id;
                     }
                  }
               }
            }
         }
      }

   }   
   

   if($is_updated) {
      update_option('last_post_status_updated', $dateNow);
   }

   return $is_updated;
}

function get_last_post_status_updated() {
    global $wpdb;
    $query = "SELECT opt.option_value FROM {$wpdb->options} opt WHERE opt.option_name='last_post_status_updated'";
    $result = $wpdb->get_row($query); 
    $output = '';
    if($result) {
        $output = $result->option_value;
    } else {
        add_option('last_post_status_updated', NULL);
    }
    return $output;
}


function custom_query_posts($posttype,$perpage,$offset,$order='ASC') {
   global $wpdb;
   $dateToday = date('Y-m-d');
   $dateNow = strtotime($dateToday);
   $final = '';
   $total = 0;


   /* Fist option */
   // $query = "SELECT p.*,date_format(str_to_date(m.meta_value, '%Y%m%d'),'%Y-%m-%d') AS start_date
   //         FROM {$wpdb->posts} p,{$wpdb->postmeta} m
   //         WHERE p.ID=m.post_id AND p.post_type='".$posttype."' AND p.post_status='publish'
   //         AND m.meta_key='start_date' AND (m.meta_value IS NOT NULL OR m.meta_value <> '') 
   //         AND DATE(m.meta_value) >= CURDATE()";

  /* Query first the posts with start dates */
  $init_query1 = "SELECT p.*,date_format(str_to_date(m.meta_value, '%Y%m%d'),'%Y-%m-%d') AS start_date FROM {$wpdb->posts} p, {$wpdb->postmeta} m WHERE p.ID=m.post_id AND p.post_type='".$posttype."' AND m.meta_key='start_date' AND m.meta_value<>'' ORDER BY start_date {$order}";

  /* No Start Date */
  $init_query2 = "SELECT p.*,date_format(str_to_date(m.meta_value, '%Y%m%d'),'') AS start_date FROM {$wpdb->posts} p, {$wpdb->postmeta} m WHERE p.ID=m.post_id AND p.post_type='".$posttype."' AND m.meta_key='start_date' AND m.meta_value='' GROUP BY p.ID ORDER BY p.ID DESC";

  // /* Event Status */
  // $init_query3 = "SELECT p.*, m.meta_value AS eventstatus
  //     FROM {$wpdb->posts} p,{$wpdb->postmeta} m
  //     WHERE p.ID=m.post_id AND p.post_type='".$posttype."' AND p.post_status='publish'
  //     AND m.meta_key='eventstatus' AND m.meta_value<>'active'";

  $entries = array();
  $active_entries = array();
  $non_active = array();
  $res1 = $wpdb->get_results($init_query1);
  $res2 = $wpdb->get_results($init_query2);
  $res_merged = array_merge($res1,$res2); 

  if($res_merged && array_filter($res_merged)) {
    foreach($res_merged as $row) {
      $id = $row->ID;
      $status = get_field("eventstatus",$id);
      $row->eventstatus = $status;
      if($status=='active') {
        $active_entries[] = $row;
      } else {
        $non_active[] = $row;
      }
    }
  }

  $merged_entries = array_merge($active_entries,$non_active);
  if( $merged_entries && array_filter($merged_entries) ) {
    $entries = array_filter($merged_entries);
  }
  
  $final = ($entries) ? $entries : '';
  $total = ($final) ? count($final) : 0;
 
  $records = array();

   if($final) {
      $offset = $offset-1;
      if($offset<=0) {
         $offset = 0;
      }

      $start = $offset;
      $end = $perpage;
      if($offset>0) {
         $start = (($offset+1) * $perpage) - $perpage;
         $end = ($offset+1) * $perpage;
      }
      
      for($i=$start; $i<$end; $i++) {
         if( isset($final[$i]) ) {
            $data = $final[$i];
            $records[] = $data;
         }
      }
   }

   $output['total'] = $total;
   $output['records'] = $records;
   return $output;
}


function get_template_by_id($post_id,$template=null) {
    global $wpdb;
    if($template) {
        $result = $wpdb->get_row( "SELECT p.ID FROM {$wpdb->posts} p, {$wpdb->postmeta} m WHERE p.ID=m.post_id AND m.post_id=".$post_id." AND m.meta_key='_wp_page_template' AND m.meta_value='".$template."'" );
    } else {
        $result = $wpdb->get_row( "SELECT ID FROM $wpdb->posts WHERE ID=".$post_id );
    }
    
    return ($result) ? $result->ID : '';
}

// $post = get_post(63946);
// $terms = get_the_terms( $post, 'whitewater-location' );
// echo "<pre>";
// print_r($terms);
// echo "</pre>";


function activity_location_catid($post_id) {
  global $wpdb;
  $query = "SELECT term.* FROM ".$wpdb->prefix."term_relationships rel, ".$wpdb->prefix."terms term 
            WHERE rel.term_taxonomy_id=term.term_id AND rel.object_id=".$post_id;
  $result = $wpdb->get_row($query);
  return $result;
}

add_action('acf/save_post', 'my_custom_acf_save_post');
function my_custom_acf_save_post( $post_id ) {
    $post_type = get_post_type($post_id);
    if($post_type=='activity_schedule') {
        /* Update slug when event date is changed */
        $event_date = get_field("eventDateSchedule");




        if($event_date) {
            $post_title = date('l, F jS, Y',strtotime($event_date));
            $post_slug = sanitize_title($event_date);

            $term = activity_location_catid($post_id);
            if($term) {
              $post_title .= ' - ' . $term->name;
              $post_slug .= '-' . $term->slug;
            }

            $my_post = array(
                'ID'            =>  $post_id,
                'post_title'    => $post_title,
                'post_name'     => $post_slug
            );
            wp_update_post( $my_post );
        }
    }
}




/* Shortcode for Company Name */
function contact_shortcode_company_name( $atts ){
  $company = get_field("company_name","option");
  return ($company) ? '<span class="contact-info-company">'.$company.'</span>':'';
}
add_shortcode( 'company', 'contact_shortcode_company_name' );

/* Shortcode for Address */
function contact_shortcode_address( $atts ){
  $a = shortcode_atts( array(
    'icon' => '',
  ), $atts );
  $icon = $a['icon'];
  $address = get_field("address","option");
  if($icon) {
    $address = '<i class="fa fa-map-marker-alt"></i> ' . $address;
  }
  return ($address) ? '<span class="contact-info-address">'.$address.'</span>':'';
}
add_shortcode( 'address', 'contact_shortcode_address' );

/* Shortcode for Phone */
function contact_shortcode_phone( $atts ){
  $a = shortcode_atts( array(
    'icon' => '',
  ), $atts );
  $icon = $a['icon'];
  $phone = get_field("phone","option");
  if($icon) {
    $phone = '<i class="fa fa-phone"></i> <a href="tel:'.format_phone_number($phone).'">' . $phone . '</a>';
  }
  return ($phone) ? '<span class="contact-info-phone">'.$phone.'</span>':'';
}
add_shortcode( 'phone', 'contact_shortcode_phone' );

/* Shortcode for Email */
function contact_shortcode_email( $atts ){
  $a = shortcode_atts( array(
    'icon' => '',
  ), $atts );
  $icon = $a['icon'];
  $email = get_field("email","option");
  if($icon) {
    $email = '<i class="fa fa-envelope"></i> <a href="mailto:'.antispambot($email,1).'">'.antispambot($email).'</a>';
  }
  return ($email) ? '<span class="contact-info-email">'.$email.'</span>':'';
}
add_shortcode( 'email', 'contact_shortcode_email' );


/* Hide Default WP Richtext Editor */
add_action( 'load-page.php', 'hide_tinyeditor_wp' );
function hide_tinyeditor_wp() {
    global $wpdb;
    if( !isset( $_GET['post'] ) )
        return;
    $pages = array();
    $post_id = $_GET['post'];
    $page_option_id = get_site_page_option_id();
    $templates = array('page-food-beverage');
    if($templates) {
        foreach($templates as $filename) {
            $filename .= ".php";
            $id = get_template_by_id($post_id,$filename);
            if($id) {
                $pages[] = $id;
            }
        }
    }

    if( $pages && in_array($post_id, $pages) ) {
        remove_post_type_support('page', 'editor');
    }

    /* Hide other custom field not related to 'Options' */
    if($post_id==$page_option_id) {
        $elementsToHide[] = 'a.page-title-action'; 
        $elementsToHide[] = '.wp-heading-inline'; 
        $elementsToHide[] = '#edit-slug-box'; 
        $elementsToHide[] = '#acf_after_title-sortables'; 
        $elementsToHide[] = '#pageparentdiv';
        $elementsToHide[] = '#postimagediv';
        $elementsToHide[] = '.misc-pub-revisions';
        $elementsToHide[] = '.misc-pub-curtime';
        $elementsToHide[] = '.misc-pub-post-status';
        $elementsToHide[] = '#minor-publishing-actions';
        $elementsToHide[] = '#screen-meta-links';
        $elements = implode(",",$elementsToHide);
        echo '<style>'.$elements.'{display:none!important;}</style>';
        remove_post_type_support('page','editor','thumbnail');
    } 
}


/* Hide Special Pages */
function wpse_hide_special_pages($query) {
    global $typenow;
    $page_option_id = get_site_page_option_id();

    // Only do this for pages
    if ( 'page' == $typenow) {
        // Don't show the special pages (get the IDs of the pages and replace these)
        $query->set( 'post__not_in', array($page_option_id) );
        return;
    }
}
add_action('pre_get_posts', 'wpse_hide_special_pages');


/* ======= ACF OPTIONS PAGE Save Form fixed =======
* This is the temporary fix for ACF Options issue when saving the form.
* Issue: Redirects to 404 when saving
*/

function get_site_page_option_id() {
    global $wpdb;
    $query_data = "SELECT ID FROM ".$wpdb->prefix."posts WHERE post_name='acf-site-options' AND post_status='private' AND post_type='page'";
    $result = $wpdb->get_row($query_data);
    return ($result) ? $result->ID : '';
}

function _acf_do_save_post102320( $post_id = 0 ) {
  
  $site_option_post_id = get_site_page_option_id(); /* Get the post ID of "Site Options" page */
  $post_id = (isset($_POST['post_ID']) && $_POST['post_ID']) ? $_POST['post_ID'] : 0;
  if($post_id==$site_option_post_id) {

    if( $_POST['acf'] ) {
      acf_update_values( $_POST['acf'], $site_option_post_id ); /* Saves fields under `Site Options` page */
      acf_update_values( $_POST['acf'], "options" ); /* Saves fields under `Options` page */
    }

  } 

}
add_action( 'acf/save_post', '_acf_do_save_post102320' );

/* ======= end of ACF OPTIONS PAGE Save Form fixed ======= */


function extractURLFromString($string) {
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    if(preg_match($reg_exUrl, $string, $url)) {
        return ($url) ? $url[0] : '';
    } else {
        return '';
    }
}

add_action('wp_ajax_nopriv_assignBoxWidth', 'assignBoxWidth');
add_action('wp_ajax_assignBoxWidth', 'assignBoxWidth');
function assignBoxWidth(){
  global $wpdb;
  $prefix = $wpdb->prefix;
  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $postids = ($_POST['postid']) ? $_POST['postid'] : '';
    $boxWidth = ($_POST['width']) ? $_POST['width'] : '';
    $jsonData = "";
    $stories_boxes_width = array();
    if($postids) {
      foreach($postids as $k=>$id) {
        $width = $boxWidth[$k];
        $stories_boxes_width[$id] = array(
          'post_id'=>$id,
          'width'=>$width
        );
      }
    }


    $optionName = 'stories_boxes_width';
    $option_table = $prefix.'options';
    $result_data = [];

    if( $opt = is_option_exists($optionName) ) {
      if($stories_boxes_width){
        $optionValue = ($opt->option_value) ? json_decode($opt->option_value, true) : '';
        if($stories_boxes_width) {
          foreach($stories_boxes_width as $id=>$data) {
            $optionValue[$id] = $data;
          }

          $result_data = $optionValue;
          $jsonData = json_encode($optionValue);
          $wpdb->update(
            $option_table,
            array( 
              'option_value' => $jsonData
            ),
            array(
              'option_name' => $optionName
            )
          );
        }
      }
    } else {
      $result_data = $stories_boxes_width;
      $jsonData = json_encode($stories_boxes_width);
      $wpdb->insert(
        $option_table,
        array(
          'option_name' => $optionName,
          'option_value' => $jsonData,
        )
      );
    }

    $result = is_option_exists($optionName);

    $response['result'] = $result_data;
    echo json_encode($response);

  } else {
    header("Location: ".$_SERVER["HTTP_REFERER"]);
  }
  die();
}


function is_option_exists($optName) {
  global $wpdb;
  $prefix = $wpdb->prefix;
  $db_options = $prefix.'options';
  $sql_query = 'SELECT * FROM ' . $db_options . ' WHERE option_name = "' . $optName . '"';
  $result = $wpdb->get_row($sql_query);
  return $result;
}

function GetOption( $optName ) {
  global $wpdb;
  $prefix = $wpdb->prefix;
  $db_options = $prefix.'options';
  $sql_query = 'SELECT * FROM ' . $db_options . ' WHERE option_name = "' . $optName . '"';
  $result = $wpdb->get_row($sql_query);
  $data = '';
  if($result) {
    $data = ($result->option_value) ? json_decode($result->option_value) : '';
  }
  return $data;

  // $results = $wpdb->get_results( $sql_query, OBJECT );

  // if ( count( $results ) === 0 ) {
  //     return false;
  // } else {
  //     return true;
  // }
}


add_action('wp_ajax_nopriv_posts_load_more', 'posts_load_more');
add_action('wp_ajax_posts_load_more', 'posts_load_more');
function posts_load_more(){
    global $wpdb;
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $posttype = ($_POST['posttype']) ? $_POST['posttype'] : '';
        $perpage = ($_POST['perpage']) ? $_POST['perpage'] : 6;
        $baseurl = ($_POST['baseurl']) ? $_POST['baseurl'] : '';
        $paged = ($_POST['paged']) ? $_POST['paged'] : 1;
        $content = '';
        $placeholder = THEMEURI . 'images/rectangle.png';
        $imageHelper = THEMEURI . 'images/rectangle-narrow.png';

        $args = array(
            'posts_per_page'   => $perpage,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => $posttype,
            'post_status'      => 'publish',
            'paged'            => $paged
        );

        $blogs = new WP_Query($args);
        ob_start();
        if ( $blogs->have_posts() ) {
            $sec=.1; $i=1; while ( $blogs->have_posts() ) : $blogs->the_post();
            $thumbId = get_post_thumbnail_id(); 
            $featImg = wp_get_attachment_image_src($thumbId,'large');
            $featThumb = wp_get_attachment_image_src($thumbId,'thumbnail');
            $content = get_the_content();
            $title = get_the_title();
            $divclass = (($content || $title) && $featImg) ? 'half':'full';
            $pagelink = get_permalink();
            $divclass .= ($i % 2) ? ' odd':' even';
            $divclass .= ($i==1) ? ' first':'';
            include( get_stylesheet_directory() . '/parts/content-post.php' );
            ?>
            <?php
            $sec =  $sec + .1;
            $i++; endwhile; wp_reset_postdata();
        }

        $content = ob_get_contents();
        ob_end_clean();

        $return['result'] = $content;
        echo json_encode($return);

    } else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    die();
}

add_action('wp_ajax_nopriv_ajaxGetPageData', 'ajaxGetPageData');
add_action('wp_ajax_ajaxGetPageData', 'ajaxGetPageData');
function ajaxGetPageData(){
    global $wpdb;
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $postid = ( isset($_REQUEST['ID']) && $_REQUEST['ID'] ) ? $_REQUEST['ID'] : '';
        $content = array();
        if($postid) {
            if( $post = get_post($postid) ) {
                $full_image = get_field("full_image",$postid);
                $thumbnail = get_field("thumbnail_image",$postid);
                $content['featured_image'] = ($full_image) ? $full_image:'';
                $content['thumbnail_image'] = ($thumbnail) ? $thumbnail:'';
                $content['raw'] = $post;
                $content['post_title'] = $post->post_title; 
                $textcontent = '';
                $postlink = get_permalink($postid) . '?show=contentonly';
                $status = get_field("eventstatus",$postid);
                $eventStatus = ($status) ? $status:'upcoming';
                $content['eventstatus'] = $eventStatus;
                $content['postlink'] = $postlink;
                $content['post_content'] = ($post->post_content) ? apply_filters('the_content', $post->post_content):''; 
            }
        }
        echo json_encode($content);

    } else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    die();
}

function fwp_archive_per_page( $query ) {
    if ( is_tax( 'category' ) ) {
        $query->set( 'posts_per_page', 20 );
    }
}
add_filter( 'pre_get_posts', 'fwp_archive_per_page' );


function child_templates($template) {
    global $post;

    if ( isset($post->post_parent) && $post->post_parent ) {
        // get top level parent page
        if( is_array($post->post_parent) ) {
          $parent = get_post(
              reset(array_reverse(get_post_ancestors($post->ID)))
          );

          // find the child template based on parent's slug or ID
          $child_template = locate_template(
              [
                  'child-' . $parent->post_name . '.php',
                  'child-' . $parent->ID . '.php',
                  'child.php',
              ]
          );

          if ($child_template) return $child_template;

        }
    }

    return $template;
}
add_filter( 'page_template', 'child_templates' );

function callToActionButtonFunc( $atts ) {
    $a = shortcode_atts( array(
        'id' => '',
    ), $atts );

    $buttons = get_field("call-to-actions");
    $id = $a['id'];
    $output = '';
    if($buttons) {
        $i=1; foreach($buttons as $b) {
            if( $btn = $b['ctabutton'] ) {
                $btnName = $btn['title'];
                $btnLink = $btn['url'];
                $btnTarget = ( isset($btn['target']) && $btn['target'] ) ? $btn['target'] : '_self';
                if($id==$i) {
                    $output .= '<a href="'.$btnLink.'" target="'.$btnTarget.'" class="btn-sm btn-cta"><span>'.$btnName.'</span></a>';
                }
            }
            $i++;
        }
    }

    return $output;
}
add_shortcode( 'call-to-action', 'callToActionButtonFunc' );


add_action('wp_ajax_nopriv_get_faq_group', 'get_faq_group');
add_action('wp_ajax_get_faq_group', 'get_faq_group');
function get_faq_group(){
    global $wpdb;
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $post_id = ($_POST['post_id']) ? $_POST['post_id'] : '';
        $output = getFaqs($post_id);
        $return['result'] = $output;
        echo json_encode($return);
    } else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    die();
}

function getFaqs($post_id) {
    $output = '';
    $questions = get_field("faqs",$post_id);
    ob_start();
    if($questions) { ?>
    <div class="faqsItems">
        <div class="shead-icon text-center">
            <h2 class="stitle"><?php echo get_the_title($post_id); ?></h2>
        </div>
        <div id="faq-<?php echo $post_id?>" class="faq-group">
            <?php $n=1; foreach ($questions as $f) { 
                $question = $f['question'];
                $answer = $f['answer'];
                $isFirst = ($n==1) ? ' first':'';
                if($question && $answer) { ?>
                <div class="faq-item collapsible<?php echo $isFirst ?>">
                    <h3 class="option-name"><?php echo $question ?><span class="arrow"></span></h3>
                    <div class="option-text"><?php echo $answer ?></div>
                </div>
                <?php } ?>

            <?php $n++; } ?>
        </div>
    </div>
    <?php }
    $output = ob_get_contents();
    ob_end_clean(); 
    return $output;
}

/* Remove ACF Fields on specific post */
$postid = ( isset($_GET['post']) && $_GET['post'] ) ? $_GET['post'] : 0;
if ( ($pagenow == 'post.php' && get_post_type($postid) == 'instructions') ||  ($pagenow == 'post-new.php' && $_GET['post_type'] == 'instructions') ) {
    add_action('admin_head', 'ins_custom_admin_css');
    function ins_custom_admin_css() { ?>
    <style type="text/css">
        /*#instructions-template-tabs,
        #instructions-template-add-toggle{display:none!important;}
        #instructions-template-all.tabs-panel {
            border: none;
        }*/
    </style>
    <?php
    }
    add_action('admin_footer', 'ins_custom_admin_js');
    function ins_custom_admin_js() { ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){

      /* This will add a category as `Default` */
      if( $('#instructions-templatechecklist li input[type="checkbox"]').length>0 ) {
        var hasChecks = [];

        $('#instructions-templatechecklist li input[type="checkbox"]').on("click",function(){
          if( this.checked ) {
            var opt = $(this).val();
            $('#instructions-templatechecklist li input[type="checkbox"]').each(function(){
              if( $(this).val()==opt ) {
                $(this).prop("checked",true);
              } else {
                $(this).prop("checked",false);
              }
            });
          } else {
            $('#instructions-templatechecklist label').each(function(){
              if( $(this).text().trim()=='Default' ) {
                $(this).find("input").prop("checked",true);
              }
            });
          }
        });

        $('#instructions-templatechecklist input[type="checkbox"]').each(function(){
          if(this.checked) {
            hasChecks.push( $(this).val() );
          }
        });
        if(hasChecks.length>0) {

        } else {
          $('#instructions-templatechecklist label').each(function(){
            if( $(this).text().trim()=='Default' ) {
              $(this).find("input").prop("checked",true);
            }
          });
        }
      }

    });
    </script>
    <?php
    }
}

/* Remove Category Meta Box on Instructions Post type */
// add_action( 'admin_menu' , 'remove_instructions_categories');
// function remove_instructions_categories(){
//     remove_meta_box( 'instructions-templatediv' , 'instructions' , 'side' );
// }


// /* Custom FacetWP - Festival Activities Programming */
// add_filter( 'facetwp_facet_types', function( $facet_types ) {
//     $facet_types['fp_slug'] = new FacetWP_Facet_Festival();
//     return $facet_types;
// });

// class FacetWP_Facet_Festival {
//     function __construct() {
//         $this->label = __( 'Festival Programming', 'fwp' );
//     }
//     /**
//      * Load the available choices
//      */
//     function load_values( $params ) {
//         global $wpdb,$post;
//         $id = ( isset($post->ID) && $post->ID ) ? $post->ID:'';
//         if( empty($id) ) return '';

//         $facet = $params['facet'];
//         $terms_table = $wpdb->prefix . 'terms cat';
//         $from_clause = $wpdb->prefix . 'term_relationships rel';
//         //$where_clause = $params['where_clause'];
//         $where_clause = "object_id=".$id;

//         $limit = ctype_digit( $facet['count'] ) ? $facet['count'] : 10;
//         $from_clause = apply_filters( 'facetwp_facet_from', $from_clause, $facet );
//         $where_clause = apply_filters( 'facetwp_facet_where', $where_clause, $facet );

//         $sql = "SELECT rel.object_id AS post_id, cat.term_id, cat.name, cat.slug FROM $from_clause, $terms_table WHERE rel.term_taxonomy_id=cat.term_id AND $where_clause 
//                 GROUP BY cat.term_id ORDER BY cat.name ASC LIMIT $limit";
//         return $wpdb->get_results( $sql, ARRAY_A );
//     }

//     /**
//      * Generate the output HTML
//      */
//     function render( $params ) {

//         $output = '';
//         $facet = $params['facet'];
//         $values = (array) $params['values'];
//         $selected_values = (array) $params['fp_slug'];

//         $key = 0;
//         foreach ( $values as $key => $result ) {
//             $selected = in_array( $result['slug'], $selected_values ) ? ' checked' : '';
//             $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
//             $output .= '<div class="facetwp-link' . $selected . '" data-value="' . esc_attr( $result['slug'] ) . '">';
//             $output .= esc_html( $result['slug'] );
//             $output .= '</div>';
//         }

//         return $output;
//     }

//     /**
//      * Return array of post IDs matching the selected values
//      * using the wp_facetwp_index table
//      */
//     function filter_posts( $params ) {
//         global $wpdb;
//         $id = ( isset($post->ID) && $post->ID ) ? $post->ID:0;
//         $output = [];
//         $facet = $params['facet'];
//         $selected_values = $params['fp_slug'];

//         $sql = $wpdb->prepare( "SELECT DISTINCT term_id,name,slug
//             FROM {$wpdb->prefix}terms term, {$wpdb->prefix}term_relationships rel';
//             WHERE term.term_id=rel. term.slug = %s AND ",
//             $facet['name']
//         );

//         foreach ( $selected_values as $key => $value ) {
//             $results = facetwp_sql( $sql . " AND facet_value IN ('$value')", $facet );
//             $output = ( $key > 0 ) ? array_intersect( $output, $results ) : $results;

//             if ( empty( $output ) ) {
//                 break;
//             }
//         }

//         return $output;
//     }

// }


function get_festival_programming_filter($currentPostId) {
    global $wpdb;
    if( empty($currentPostId) ) return false;
    $selectedIds = array();
    $taxonomy = 'festival_programming';
    $programming = array();
    $options = array();
    //echo "<pre>";

    $activities = get_field("festival_activities",$currentPostId);
    $post_items = array();
    $postIds = array();
    $selected_activities = array();

    if($activities) {
        foreach ($activities as $a) { 
            $schedules = $a['schedule'];
            $day = $a['day'];
            $daySlug = ($day) ? sanitize_title($day) : '';
            if($schedules) {
                foreach ($schedules as $s) { 
                    $time = $s['time'];
                    $altText = ( isset($s['alt_text']) && $s['alt_text'] ) ? $s['alt_text']:'';
                    $is_pop_up = ( isset($s['popup_info'][0]) && $s['popup_info'][0] ) ? true : false;
                    $act = ( isset($s['activity']) && $s['activity'] ) ? $s['activity']:'';
                    if($act) {
                        $id = $act->ID;
                        $act->schedule = $time;
                        $act->popup_info = $is_pop_up;
                        $act->alt_text = $altText;
                        $selected_activities[$day][] = $act;
                        $postIds[] = $id;
                    }
                }
            }
        }
    }

    if($postIds) {
        foreach($postIds as $id) {
            $query = "SELECT cat.term_id,cat.name,cat.slug,p.ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}term_relationships rel, {$wpdb->prefix}terms cat, {$wpdb->prefix}term_taxonomy tax
                      WHERE rel.term_taxonomy_id=cat.term_id AND tax.term_id=cat.term_id AND tax.taxonomy='{$taxonomy}' 
                      AND rel.object_id=".$id. " AND p.ID=".$id." ORDER BY cat.name ASC";
            $result = $wpdb->get_row( $query );
            if($result) {
                $termId = $result->term_id;
                $programming[$termId][] = $result;
            }
        }
    }

    if($programming) {
        foreach($programming as $term_id=>$items) {
            $catName = $items[0]->name;
            $posts = array();
            $arg['term_name'] = $catName;
            $arg['term_id'] = $term_id;
            foreach($items as $row) {
                $posts[] = $row->ID;
            }
            $arg['term_posts'] = $posts;
            $options[] = $arg;
        }
    }
    
    // echo "<pre>";
    // print_r($options);
    // echo "</pre>";
    return $options;
}
function getUpcomingEvents($postTypes,$limit=8) {
    global $wpdb;
    $items = array();
    $output = array();
    foreach($postTypes as $type) {
        $query = "SELECT p.ID,p.post_title,p.post_type FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m 
              WHERE p.ID=m.post_id AND m.meta_key='show_on_homepage' AND m.meta_value='yes' AND p.post_type='".$type."' AND p.post_status='publish' ORDER BY p.post_date DESC";
        $result = $wpdb->get_results($query);
        if($result) {
            foreach($result as $row) {
                $postid = $row->ID;
                $start_date = get_field("start_date",$postid);
                $start = ($start_date) ? strtotime($start_date) : $postid . '_nodates';
                $arg['ID'] = $postid;
                $arg['post_title'] = $row->post_title;
                $arg['post_type'] = $row->post_type;
                $arg['start_date_unix'] = $start;
                $arg['start_date'] =($start_date) ? date('m/d/Y',strtotime($start_date)) : '';
                $items[] = $arg;
            }
        }
    }
    if($items) {
        usort($items, function($a, $b) {
            return $a['start_date_unix'] <=> $b['start_date_unix'];
        });

        for($i=0; $i<$limit; $i++) {
            if( isset($items[$i]) && $items[$i] ) {
                $output[] = $items[$i];
            }
        }
    }
    return $output;
}

/* CRON JOB => Change event status automatically if completed */
function myprefix_custom_cron_schedule( $schedules ) {
$schedules['every_twelve_hours'] = array(
        'interval' => 43200, // Every 12 hours
        'display'  => __( 'Every 12 hours' ),
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'myprefix_custom_cron_schedule' );

//Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'myprefix_cron_hook' ) ) {
    wp_schedule_event( time(), 'every_twelve_hours', 'myprefix_cron_hook' );
}

///Hook into that action that'll fire every six hours
add_action( 'myprefix_cron_hook', 'myprefix_cron_function' );

function myprefix_cron_function() {
    global $wpdb;
    $query = "SELECT p.ID, p.post_title, m.meta_value FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND m.meta_key='start_date' AND p.post_status='publish'";
    $result = $wpdb->get_results($query);
    $datetoday = date('Ymd');
    $now = strtotime($datetoday);
    $updatedPosts = array();
    if($result) {
        foreach($result as $row) {
            $id = $row->ID;
            $start = ($row->meta_value) ? strtotime($row->meta_value) : '';
            $status = get_post_meta($id,"eventstatus","meta_value");
            if($status && $start) {
                if($status=='active') {
                    if($start<$now) {
                        $metaVal = 'completed';
                        update_post_meta($id,"eventstatus",$metaVal);
                        $updatedPosts[] = $id;
                    }
                }
            }
        }
    }
    return $updatedPosts;
}

function get_homepage_id() {
    global $wpdb;
    $query = "SELECT p.ID FROM ".$wpdb->prefix."posts p WHERE  p.post_title='Homepage' AND p.post_status='publish'";
    $result = $wpdb->get_row($query);
    return ($result) ? $result->ID : '';
}


/* Shortcode for Company Name */
function stories_text_shortcode( $atts ){
  $stories_text = get_field("stories_text","option");
  return ($stories_text) ? '<div class="stories-text">'.$stories_text.'</div>':'';
}
add_shortcode( 'stories_text', 'stories_text_shortcode' );


function get_ages_from_camp($selectedAges=null) {
    global $wpdb;
    $ageList = array();
    $records = array();
    $query = "SELECT p.*, m.meta_value AS ages FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND m.meta_key='ages' AND m.meta_value<>'' AND p.post_type='camp' AND p.post_status='publish'";
    $result = $wpdb->get_results($query);
    if($result) {
        foreach($result as $row) {
            $pid = $row->ID;
            $ages = $row->ages;
            if( $selectedAges ) {
                if($ages) {
                    $nums = preg_replace("/[^0-9]/",' ', $ages);
                    if($nums) {
                        $nums = preg_replace('/\s+/', ' ', $nums);
                        $nums = trim($nums);
                        $parts = explode(' ',$nums);
                        
                        if( count($parts)>1 ) {
                            $a = $parts[0];
                            $b = end($parts);
                            $ranges = range($a,$b);
                            foreach($selectedAges as $s) {
                                if( in_array($s,$ranges) ) {
                                    $records[$pid] = $row;
                                }
                            }
                        } else {
                            foreach($parts as $a) {
                                if( in_array($a,$selectedAges) ) {
                                    $records[$pid] = $row;
                                }
                            }
                        } 
                        
                        

                    }       
                }
            } 
        }
    }
    return ($records) ? array_values($records) : '';
}


function get_instruction_discipline() {
    global $wpdb;
    $query = "SELECT m.meta_value AS discipline FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND m.meta_key='discipline' AND m.meta_value<>'' AND p.post_type='instructions' AND p.post_status='publish' GROUP BY m.meta_value ORDER BY m.meta_value ASC";
    $result = $wpdb->get_results($query);
    $list = array();
    if($result) {
        foreach($result as $row) {
            $list[] = $row->discipline;
        }
    }
    return $list;
}
function get_instructions_result($params,$paged=1,$perpage=16) {
    global $wpdb;
    $result = array();
    $output['total'] = '';
    $output['record'] = '';
    $start = 0;
    $end = $perpage-1;
    if($paged>1) {
        $end = (($paged+1) * $perpage) - $perpage;
        $start = $end - $perpage;
        $end = $end - 1;
    }

    // print_r('START: '.$start.'<BR>');
    // print_r('END: '.$end.'<BR>');

    if($params) {
        $merge_result = array();
        $res = array();
        if( isset($params['meta_key']['discipline']) && $params['meta_key']['discipline'] ) {
            $discipline = $params['meta_key']['discipline'];
            $query1 = "SELECT p.* FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND m.meta_key='discipline' AND m.meta_value='".$discipline."' AND p.post_type='instructions' AND p.post_status='publish'";
            if( $wpdb->get_results($query1) ) {
                $res['discipline'] = $wpdb->get_results($query1);
            }
        }

        if( isset($params['taxonomy']) ) {
            $tax_result = array();
            foreach( $params['taxonomy'] as $taxonomy=>$val) {
                if($val) {
                    $tax_query = "SELECT p.*, term.term_id, term.slug, tax.taxonomy FROM ".$wpdb->prefix."posts p,".$wpdb->prefix."term_taxonomy tax,".$wpdb->prefix."terms term,".$wpdb->prefix."term_relationships rel 
                                  WHERE tax.taxonomy='".$taxonomy."' AND tax.term_id=term.term_id AND tax.term_id=rel.term_taxonomy_id AND term.slug='".$val."' AND p.ID=rel.object_id AND p.post_type='instructions' AND p.post_status='publish'";
                    $tax_result = $wpdb->get_results($tax_query);
                    if($tax_result) {
                        foreach($tax_result as $row) {
                            $res['taxonomy'][] = $row;
                        }
                    }
                }
            }
        }

        if($res) {
            foreach($res as $k=>$items) {
                foreach($items as $item) {
                    $id = $item->ID;
                    $merge_result[$id] = $item;
                }
            }
        }

        
        $list = array_values($merge_result);
        $total = count($list);
        for($i=$start; $i<=$end; $i++) {
            if( isset($list[$i]) && $list[$i] ) {
                $data = $list[$i];
                $result[] = $data;
            }
        }

        $output['total'] = $total;
        $output['record'] = $result;

    } else {

        $offset = ($perpage * $paged) - $perpage;
        $limit = $perpage;
        //if($paged>1) {
            // $offset = (($paged+1) * $perpage) - $perpage;
            // $offset = $offset - 1;
            //offset = (limit * pageNumber) - limit;
        //}

        // print_r('START: '.$offset.'<BR>');
        // print_r('LIMIT: '.$limit.'<BR>');

        $query = "SELECT p.* FROM ".$wpdb->prefix."posts p WHERE p.post_type='instructions' AND p.post_status='publish' ORDER BY p.menu_order ASC LIMIT " . $offset . ', ' . $limit;
        $result = $wpdb->get_results($query);

        $query_total = "SELECT count(*) AS total FROM ".$wpdb->prefix."posts p WHERE p.post_type='instructions' AND p.post_status='publish'";
        $result_total = $wpdb->get_row($query_total);
        $total = ($result_total) ? $result_total->total : 0;

        $output['total'] = $total;
        $output['record'] = $result;
    }

    return $output;
}

function get_upcoming_bands() {
    global $wpdb;
    $today = date('Y-m-d', strtotime(date('Y-m-d H:i:s'). '-1 days'));
    //$today = '2021-03-25';
    $today_unix = strtotime($today);
    $daysOfWeek = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
    //$days_selection = get_field("upcoming_music_events_days","option");
    $result = array();
    $days_selection = array();
    $daysList = get_field("rj_schedules","option");
    
    if($daysList) {
        foreach($daysList as $e) {
            $day = $e['day'];
            if($day) {
                $days_selection[] = $day;
            }
        }
    }

    $today_day = date('l'); /* Displays the full name of a day */
    $queryDays = array();
    $dayArrs = array();
    $selectedDays = array();

    $currentYear = date('Y');
    $currentMonth = date('n');
    $calendar = array();
    $query_dates = array();

    if($days_selection) {
        foreach($days_selection as $d) {
            foreach($daysOfWeek as $k=>$v) {
                if($d==$v) {
                    $selectedDays[$k] = $v;
                }
            }
        }
    }

    
    if( $selectedDays ) {
        $countSelectedDays = count($selectedDays);
        $weekly = count($daysOfWeek);
        $totalMonths = 12;
        for($mo=1; $mo<=$totalMonths; $mo++) {
            $totalDaysPerMonth = daysInMonth($currentYear, $mo);
            for($n=1; $n<=$totalDaysPerMonth; $n++) {
                $z_month = str_pad($mo, 2, '0', STR_PAD_LEFT);
                $n_date = $currentYear.$z_month.$n;
                $n_day = date('l',strtotime($n_date));
                $weekList = array();
                if( in_array($n_day,$selectedDays) ) {
                    $finalDate = date('Ymd',strtotime($n_date));
                    $calendar[$mo][] = $n_date;

                    $now = strtotime($today);
                    $qdate = strtotime($finalDate);

                    if( $qdate >= $now ) {
                        $query_dates[] = $n_date;
                    }
                }   
            }
        }

        if($query_dates) {
            $v=1; 
            foreach($query_dates as $date) {
                $query = "SELECT p.ID,p.post_title, CAST(m.meta_value AS DATE) AS start_date, UNIX_TIMESTAMP(CAST(m.meta_value AS DATE)) AS start_date_unix  FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND p.post_type='music' AND p.post_status='publish'
                          AND m.meta_key='start_date' AND m.meta_value<>'' AND UNIX_TIMESTAMP(CAST(m.meta_value AS DATE))>=".$today_unix.' ORDER BY start_date_unix ASC';
                $res = $wpdb->get_results($query);
                if($res) {
                    foreach($res as $row) {
                        if($v<=$countSelectedDays) {
                          $pId = $row->ID;
                          $result[$pId] = $row;
                        }
                        $v++;
                    }
                }
            }
        }
    }

    // $entries = array();
    // if($result) {
    //   foreach($result as $row) {
    //     $id = $row->ID;
    //   }
    // }

    return $result;
}

function daysInMonth($year, $month) {
    return date("t", mktime (0,0,0,$month,1,$year));
}

function upcoming_bands_by_date($offset=0,$limit=12) {
    global $wpdb;
    $today = date('Y-m-d');
    $today_unix = strtotime($today);
    $query = "SELECT p.ID,p.post_title,p.post_content, CAST(m.meta_value AS DATE) AS start_date, UNIX_TIMESTAMP(CAST(m.meta_value AS DATE)) AS start_date_unix, MONTH(CAST(m.meta_value AS DATE)) AS month 
              FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND p.post_type='music' AND p.post_status='publish'
              AND m.meta_key='start_date' AND m.meta_value<>'' AND UNIX_TIMESTAMP(CAST(m.meta_value AS DATE))>=".$today_unix.' ORDER BY start_date_unix ASC LIMIT '.$offset.','.$limit;
    $result = $wpdb->get_results($query);

    $total_query = "SELECT count(*) as total FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND p.post_type='music' AND p.post_status='publish'
              AND m.meta_key='start_date' AND m.meta_value<>'' AND UNIX_TIMESTAMP(CAST(m.meta_value AS DATE))>=".$today_unix;
    $result_total = $wpdb->get_row($total_query);
    $output = array();
    if($result) {
        $output['records'] = $result;
        $output['total'] = $result_total->total;
    }
    return ($output) ? $output : '';
}


// function acf_load_gravity_form_choices( $field ) {
//     // reset choices
//     $field['choices'] = array();
//     $choices = get_race_posts();
//     if( $choices && is_array($choices) ) {       
//         foreach( $choices as $choice ) {
//             $post_id = $choice->ID;
//             $post_title = $choice->post_title;
//             $field['choices'][ $post_id ] = '[ID:'.$post_id.'] ' . $post_title;
//         }
//     }
//     return $field;
// }
// add_filter('acf/load_field/name=linkpostto', 'acf_load_gravity_form_choices');

// function get_race_posts() {
//     global $wpdb;
//     $query = "SELECT p.ID,p.post_title FROM " .$wpdb->prefix."posts p WHERE p.post_type='race' AND p.post_status='publish' ORDER BY p.post_title ASC";
//     $result = $wpdb->get_results($query);
//     return ($result) ? $result : '';
// }
add_action('after_setup_theme', 'show_top_admin_menu_bar', 100);

function show_top_admin_menu_bar() {
  if ( current_user_can('editor') || current_user_can('scheduler') ) {
      show_admin_bar(true);
  }
}
/* Pre-select value FACETWP => See Employment Page */
add_filter( 'facetwp_preload_url_vars', function( $url_vars ) {
    if ( 'who-we-are/employment' == FWP()->helper->get_uri() ) {
        if ( empty( $url_vars['job_locations'] ) ) {
            $location = get_default_job_location();
            $url_vars['job_locations'] = [$location];
        }
    }
    return $url_vars;
} );

function get_images_dir($fileName=null) {
    return get_bloginfo('template_url') . '/images/' . $fileName;
}
function get_default_job_location() {
    return 'charlotte';
}

function exclude_post_types_banner() {
    //$post_types = array('race','dining', 'retreat');
    $post_types = array('dining', 'retreat');
    return $post_types;
}


add_filter( 'get_terms_args', function( $args, $taxonomies ) {
    if ( isset( $args['term_order'] ) ) {
        $args['orderby'] = 'term_order';
    }
    return $args;
}, 10, 2 );

add_filter( 'get_terms_orderby', function( $orderby, $query_vars ) {
    return 'term_order' === $query_vars['orderby'] ? 'term_order' : $orderby;
}, 10, 2 );


function is_faqs_visible($postid=null) {
    $faqVisible = ($postid) ? get_field("faqs_visibility",$postid) : get_field("faqs_visibility");
    return ( isset($faqVisible[0]) && $faqVisible[0]=='hide' ) ? false : true;
}

function getDataBySlug($slug) {
  global $wpdb;
  $query = "SELECT * FROM ".$wpdb->prefix."posts p WHERE p.post_name='".$slug."' AND p.post_status='publish' AND p.post_type='tribe_events'";
  $result = $wpdb->get_row($query);
  return ($result) ? $result : '';
}

function getPageSlugById($postId) {
  global $wpdb;
  $query = "SELECT p.post_name FROM ".$wpdb->prefix."posts p WHERE p.ID=".$postId;
  $result = $wpdb->get_row($query);
  return ($result) ? $result->post_name : '';
}

// getBranchOperationStatus();
function getBranchOperationStatus($slug) {
  $status = '';
  $branches = get_field('whitewaterLocations', 'option');
  if ($branches) {
    foreach ($branches as $b) { 
      //$location = $b['location'];
      $tax = $b['locations_taxonomy'];
      $tax_slug = ( isset($tax->slug) && $tax->slug ) ? $tax->slug : '';
      if($tax_slug==$slug) {
        $status = $b['trail_status_branch'];
      }
    }
  }
  return $status;
}

// remove_filter( 'the_content', 'wpautop' );
// remove_filter( 'the_excerpt', 'wpautop');


// add new buttons
add_filter( 'mce_buttons', 'myplugin_register_buttons' );

function myplugin_register_buttons( $buttons ) {
  array_push( $buttons,'edcolumns2','edbutton1','edbutton2');

  return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
add_filter( 'mce_external_plugins', 'myplugin_register_tinymce_javascript' );
function myplugin_register_tinymce_javascript( $plugin_array ) {
  $plugin_js_url =  get_stylesheet_directory_uri() . '/assets/js/custom-tinymce.js';
  $plugin_array['COLUMNS2'] = $plugin_js_url;
  $plugin_array['BUTTON1'] = $plugin_js_url;
  $plugin_array['BUTTON2'] = $plugin_js_url;
  return $plugin_array;
}

// add_filter( 'tiny_mce_before_init', function( $settings ) {

//     $screen = get_current_screen();

//     if ( $screen->is_block_editor() ) {
//         return $settings;
//     }        

//     // if ( ! in_array( $screen->post_type, ['tools', 'brand', 'company'], true ) ) {
//     //     return $settings;
//     // }        

//     $settings['height'] = '200';
//     $settings['autoresize_max_height'] = '200';

//     return $settings;
//  } );


add_action('wp_ajax_nopriv_film_series_details_popup', 'film_series_details_popup');
add_action('wp_ajax_film_series_details_popup', 'film_series_details_popup');
function film_series_details_popup(){
  global $wpdb;
  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    
    $pid = $_POST['post_id'];
    $post = get_post($pid);
    $output = '';
    if($post) {
      ob_start(); 
      include( locate_template('parts/film-series-popup.php') ); 
      $output = ob_get_contents();
      ob_end_clean();
    }

    $response['result'] = $output;

    echo json_encode($response);

  } else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
  }
  die();
}


function shortenDayName($day) {
  $d['Monday'] = 'Mon';
  $d['Tuesday'] = 'Tue';
  $d['Wednesday'] = 'Wed';
  $d['Thursday'] = 'Thu';
  $d['Friday'] = 'Fri';
  $d['Saturday'] = 'Sat';
  $d['Sunday'] = 'Sun';
  return ( isset($d[$day]) ) ? $d[$day] : '';
}


function extractYoutubeId($videoURL) {
  $youtubeId = '';
  if (strpos( strtolower($videoURL), 'youtube.com') !== false) {
    /* if iframe */
    if (strpos( strtolower($videoURL), 'youtube.com/embed') !== false) {
      $parts = extractURLFromString($videoURL);
      $youtubeId = basename($parts);
    } else {

      $parts = parse_url($videoURL);
      parse_str($parts['query'], $query);
      $youtubeId = (isset($query['v']) && $query['v']) ? $query['v']:''; 
      
    }
  } else if (strpos( strtolower($videoURL), 'youtu.be') !== false) {
    $parts = explode('https://youtu.be/', $videoURL);
    $parts2 = explode('?', $parts[1]);
    $youtubeId = $parts2[0];
  } 
  // if($youtubeId) {
  //   $embed_url = 'https://www.youtube.com/embed/'.$youtubeId.'?version=3&rel=0&loop=0'; 
  //   $mainImage = 'https://i.ytimg.com/vi/'.$youtubeId.'/maxresdefault.jpg';
  // }
  return $youtubeId;
}

function extractVimdeoId($videoURL) {
  $vimeoId = '';
  if( strpos( strtolower($videoURL), 'vimeo.com') !== false ) { 
    $parts = explode("https://vimeo.com/",$videoURL);
    $parts2 = $parts[1];

    if( strpos( strtolower($parts2), '?') !== false ) { 
      $parts3 = explode("?",$parts2);
      $vimeoId = $parts3[0];
    } else {
      $vimeoId = $parts2;
    }
    // $parts = ($vimeo_parts && array_filter($vimeo_parts) ) ? array_filter($vimeo_parts) : '';
    // $vimeoId = ($parts) ?  preg_replace('/\s+/', '', end($parts)) : '';
  }
  return $vimeoId;
}


function isFeaturedEvent($postUrl) {
  global $wpdb;
  $query = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key='_EventURL' AND meta_value='".$postUrl."'";
  $result = $wpdb->get_row($query);
  $is_featured = '';
  if($result) {
    $post_id = $result->post_id;
    
    //Check if this is a Featured Event
    $query2 = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key='_tribe_featured' AND meta_value='1' AND post_id=".$post_id;
    $result2 = $wpdb->get_row($query2);
    $is_featured = ($result2) ? $result2->post_id : '';
  }
  return $is_featured;
}


// function get_activity_schedule_locations() {
//   global $wpdb;
// }

/*===== REMOVE WP EDITOR ON SPECIFIC PAGE =====*/
function remove_pages_editor(){
  //BIKE RANCH TEMPLATE
  if( $template = get_page_template() ) {
    if (strpos($template, 'bike-ranch') !== false) {
      remove_post_type_support( 'page', 'editor' );
    }
  }
  // if(get_the_ID() === 95) {
  //   remove_post_type_support( 'page', 'editor' );
  // }
}
add_action( 'add_meta_boxes', 'remove_pages_editor' );


function queryActivitySchedulePosts($limit=5) {
  global $table_prefix, $wpdb;
  $query = "SELECT p.ID FROM ".$table_prefix."posts p, ".$table_prefix."postmeta m WHERE p.ID=m.post_id 
            AND p.post_type='activity_schedule' AND p.post_status='publish' AND m.meta_key='eventDateSchedule' 
            AND UNIX_TIMESTAMP(m.meta_value) >= ".strtotime(date('Ymd'))." 
            ORDER BY UNIX_TIMESTAMP(m.meta_value) ASC LIMIT ".$limit;
  $result = $wpdb->get_results($query);
  return $result;
}
add_action('wp_ajax_activity_schedule_func', 'activity_schedule_func');
add_action('wp_ajax_activity_schedule_func', 'activity_schedule_func');
function activity_schedule_func(){
  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $index = $_REQUEST['index'];
    $slug = $_REQUEST['slug'];
    $status = $_REQUEST['status'];
    $posts = queryActivitySchedulePosts(6);
    $content = '';
    $post_id = '';
    if($posts) {
      if( isset($posts[$index]) && $posts[$index] ) {
        $data = $posts[$index];
        $post_id = $data->ID;
        $is_navigate = true;
        ob_start();
        include( locate_template('parts-calendar/activity-schedule-popup.php') );
        $content = ob_get_contents();
        ob_end_clean();
      }
    }
    $response['ID'] = $post_id;
    $response['result'] = $content;
    echo json_encode($response);

  } else {
    header("Location: ".$_SERVER["HTTP_REFERER"]);
  }
  die();
}


/* Shortcode for Address */
function acf_image_list_shortcode_func( $atts ){
  // $a = shortcode_atts( array(
  //   'icon' => '',
  // ), $atts );
  return '<div class="biz-image-list"></div>';
}
add_shortcode( 'image_list', 'acf_image_list_shortcode_func' );
