<?php
/**
 * Gridlock functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 1200;

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 */
function gridlock_setup() {
  // This theme styles the visual editor with editor-style.css to match the theme style.
  add_editor_style("css/editor-style.css");

  // Adds RSS feed links to <head> for posts and comments.
  add_theme_support( 'automatic-feed-links' );

  // This theme supports a variety of post formats.
  add_theme_support( 'post-formats', array( 'gallery', 'video', 'image', 'link') );

  /*
   * This theme supports custom background color and image, and here
   * we also set up the default background color.
   */
  add_theme_support( 'custom-background', array(
    'default-color' => 'e6e6e6',
  ) );

  // This theme uses a custom image size for featured images, displayed on "standard" posts.
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 400, 9999 ); // Unlimited height, soft crop

  // Add the option
  add_option( "gridlock_all", true);
  add_option( "gridlock_future", true);
  add_option( "gridlock_query", array("posts_per_page" => 10));
  add_option( "gridlock_grid_query", array("posts_per_page" => 10));
}
add_action( 'after_setup_theme', 'gridlock_setup' );
add_theme_support( 'post-thumbnails' ); 



// the gridster drag and drop grid
if ( is_admin() ) {
  function gridlock_settings_text() {
    echo "";
  }
  function sanitize_query($query) {
    $res = array();
    $query = str_replace('"', "", str_replace("'", "", str_replace(" ", "", $query)));
    $query = explode(",", $query);
    foreach ($query as $val) {
      $vals = explode("=>", $val);
      $res[$vals[0]] = $vals[1];
    }
    return $res;
  }
  function gridlock_all_input() {
    $options = get_option('gridlock_all');
    echo ("<input id='gridlock_all' name='gridlock_all' ".checked(1, get_option('gridlock_all'), false)."' type='checkbox' value='1' />");
    echo ("<br/> Note: defaulted articles always are at the top of the page");
  }
  function gridlock_future_input() {
    $options = get_option('gridlock_all');
    echo ("<input id='gridlock_all' name='gridlock_future' ".checked(1, get_option('gridlock_future'), false)."' type='checkbox' value='1' />");
  }
  function gridlock_query_input() {
    $options = get_option('gridlock_query');
    $res = '';
    foreach ($options as $key => $val) {
      $res .= $key . " => " . $val . ", ";
    }
    $res = substr($res, 0, -2);
    echo "<input id='gridlock_query' name='gridlock_query' size='40' type='text' value='{$res}' />";
  }
  function gridlock_grid_query_input() {
    $options = get_option('gridlock_grid_query');
    $res = '';
    foreach ($options as $key => $val) {
      $res .= $key . " => " . $val . ", ";
    }
    $res = substr($res, 0, -2);
    echo "<input id='gridlock_query' name='gridlock_grid_query' size='40' type='text' value='{$res}' />";
  }
  function register_gridlock() {
    register_setting("gridlock_general", "gridlock_all");
    register_setting("gridlock_general", "gridlock_future");
    register_setting("gridlock_general", "gridlock_query", "sanitize_query");
    register_setting("gridlock_general", "gridlock_grid_query", "sanitize_query");
    add_settings_section('gridlock_main', 'General Gridlock Settings', 'gridlock_settings_text', 'gridlock_general');
    add_settings_field("gridlock_all", "Show All Posts By Default", "gridlock_all_input", "gridlock_general", "gridlock_main" );
    add_settings_field("gridlock_future", "Grid Unpublished Posts", "gridlock_future_input", "gridlock_general", "gridlock_main" );
    add_settings_field("gridlock_query", "The Query for the home screen", "gridlock_query_input", "gridlock_general", "gridlock_main" );
    add_settings_field("gridlock_grid_query", "The Query for the Grid screen", "gridlock_grid_query_input", "gridlock_general", "gridlock_main" );
  }
  // deletes the default posts if the box is unchecked
  function remove_all_query() {
    if (!get_option("gridlock_all")) {
      $remove = new WP_Query(get_option("gridlock_query"));
      while ( $remove->have_posts() ) : $remove->the_post(); 
        if (get_post_meta(get_the_ID(), "gridlock", true) < 1) {
          delete_post_meta(get_the_ID(), "gridlock");
        }
      endwhile;
    } else {
      // if gridlock all is set, go through all recent posts and make sure 
      $backorder = new WP_Query(get_option("gridlock_query"));
      while ($backorder->have_posts() ) : $backorder->the_post();
        if (!get_post_meta(get_the_ID(), "gridlock", true)) {
          add_post_meta(get_the_ID(), "gridlock", "0.13", true);
        }
      endwhile;
    }
  }
  add_action( 'admin_init', 'register_gridlock' );
  add_action( 'admin_init', 'remove_all_query' );

  function gridster_head() { ?>
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/jquery-2.0.3.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/bootstrap.min.css" />
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/jquery.gridster.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/gridster.css" />
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/jquery.gridster.min.js" type="text/javascript"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/gridster.js" type="text/javascript"></script>
  <? }
  function gridster_query() {
    if (isset($_GET["query"])) { 
      $query = $_GET["query"];
    } else {
      $query = get_option("gridlock_query");
    }
    return $query;
  }
  function gridster() { 
    $query = gridster_query(); 
    $max_row = 0; ?>
    <div class="gridster">
      <ul>
    <?php
      $gridster_query = new WP_Query(array_merge(get_option("gridlock_query"), array('orderby' => 'meta_value', 'meta_key' => 'gridlock', 'order' => 'ASC' )));
      while ( $gridster_query->have_posts() ) : $gridster_query->the_post(); 
        if (get_post_meta( get_the_ID(), "gridlock", true) > 1) { 
            $gridlock =  explode(".", get_post_meta( get_the_ID(), "gridlock", true)); 
            $index = $gridlock[1][0]; 
            $span = $gridlock[1][1]; 
            $row = $gridlock[0]; ?>
            <li data-row="<?php echo $row ?>" data-col="<?php echo $index ?>" data-sizex="<?php echo $span ?>" data-sizey="1"><? the_title(); ?> </li>
        <?php } 
      endwhile;
      ?>
      </ul>
    </div>

  <?php } 

  function gridlock_menu() {
    add_theme_page("Gridlock", "Gridlock", "edit_others_posts", "gridlock", "gridlock_page");
  }

  add_action('admin_menu', 'gridlock_menu');

  function unassigned_posts() {
      
  }

  ?>
  <?php function gridlock_page() { ?>
    <?php gridster_head(); ?>
    <div class="gridlock-container">
      <div class="row">
        <div class="col-5">
          <div class='wrap'>
          <?php screen_icon(); ?>
          <h2>Gridlock Options</h2>
            <div class="sidebar">
              <form action="options.php" method="post">
                <?php settings_fields( "gridlock_general"); ?>
                <?php do_settings_sections("gridlock_general"); ?>
                <?php submit_button(); ?>
              </form>
            <h3>Unassigned Posts</h3>
              <?php unassigned_posts(); ?> 
            </div>
          </div>
        </div>
        <div class="col-7">
          <div class="gridlocker">
            <?php gridster(); ?>
          </div>
        </div>
      </div>
    </div>
  <?php }

}
function gridlock_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'gridlock' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'gridlock' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
) );
}
add_action( 'widgets_init', 'gridlock_widgets_init' );

function gridlock_stylesheet_directory_uri( $args ) {
  return $args."/css";
}
add_filter( 'stylesheet_directory_uri', 'gridlock_stylesheet_directory_uri', 10, 2 );

function small_author($args) {
  $pattern = "/>([^<]*)</";
  $replacement = "><em class='text-muted'>$1</em><";
  echo preg_replace($pattern, $replacement, $args);
}
add_filter( 'the_author_posts_link', 'small_author', 10, 2 );
// register wp_nav_menu
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
  register_nav_menus( array(
  'primary-menu' => __( 'Primary Menu', 'mytheme' )
  ) );
}

function wp_nav_menu_no_ul()
{
    $options = array(
        'echo' => false,
        'container' => false,
        'theme_location' => 'primary',
        'fallback_cb'=> 'default_page_menu'
    );

    $menu = wp_nav_menu($options);
    echo preg_replace(array(
        '#^<ul[^>]*>#',
        '#</ul>$#'
    ), '', $menu);

}

function default_page_menu() {
   wp_list_pages('title_li=');
} 
function catch_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  if (isset($matches[1][0])) {
    $first_img = $matches[1][0];
  }

  if(empty($first_img)) {
    $first_img = false;
  }
  return $first_img;
}


