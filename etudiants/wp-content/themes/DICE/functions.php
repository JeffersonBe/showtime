<?php

/* Define paths to common folders */
define('BTP_THEME_DIR',    			TEMPLATEPATH.'/theme');
define('BTP_FRAMEWORK_DIR',         TEMPLATEPATH.'/framework');


/* Global variables */
$btp_theme_name = 'DICE';
$btp_theme_slug = 'btp_dice';
$btp_theme_options = array();
$btp_temp_post = null;



$btp_theme_shortcodes = array();



/* -----------------------------------------------------------------------------
*  Always set BTP_CUSTOMIZE_MODE to false!
* ----------------------------------------------------------------------------- */
define('BTP_CUSTOMIZE_MODE', false);
if(BTP_CUSTOMIZE_MODE) {	
	if(!session_id())
		session_start();	
}
//-----------------------------------------------------------------------------



/* Initialize translation mechanism */
load_theme_textdomain( 'btp_theme', get_template_directory().'/languages' );

$locale  = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable($locale_file) )
    require_once($locale_file);

/* EnableWP Auto Feed Links */
add_theme_support('automatic-feed-links');

/* Enable post thumbnails support */
add_theme_support( 'post-thumbnails' );

/* Enable custom backgrounds support */
add_custom_background();

if ( ! isset( $content_width ) ) $content_width = 593;

/* Enable post-thumbnail support */
set_post_thumbnail_size( 581, 326, true ); 		// ratio 16:9 c-8
add_image_size( 'c-12-wide', 967, 400, true);	// used by big sliders
add_image_size( 'c-12', 893, 502, true);		// ratio 16:9
add_image_size( 'c-6', 425, 239, true);			// ratio 16:9
add_image_size( 'c-4', 269, 179, true);			// ratio 3:2
add_image_size( 'c-3', 191, 127, true);			// ratio 3:2
add_image_size( 'c-2', 113, 75, true);  		// ratio 3:2
add_image_size( 'c-1', 35, 23, true);    		// ratio 3:2

/* Register default sidebar */
register_sidebar(array(
		'name'          => 'primary',
		'id'            => 'primary',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>' 
	)
);


/* Include Theme Options specification */
require_once(BTP_THEME_DIR.'/options.php');


/* Include framework files */
require_once(BTP_FRAMEWORK_DIR.'/functions/helpers.php');
require_once(BTP_FRAMEWORK_DIR.'/functions/filters.php');
require_once(BTP_FRAMEWORK_DIR.'/functions/pagination.php');
require_once(BTP_FRAMEWORK_DIR.'/functions/tools.php');
require_once(BTP_FRAMEWORK_DIR.'/functions/btp_admin.php');



/* Include main theme  files */
require_once(BTP_THEME_DIR.'/functions/helpers.php');
require_once(BTP_THEME_DIR.'/functions/tools.php');
require_once(BTP_THEME_DIR.'/functions/precontent.php');
require_once(BTP_THEME_DIR.'/functions/breadcrumbs.php');



require_once(BTP_FRAMEWORK_DIR.'/functions/form_unit.php');

/* Init Shortcode Generator */
require_once(BTP_FRAMEWORK_DIR.'/functions/shortcode_generator.php');
$btp_shortcode_generator = new BTP_Shortcode_Generator('btp-shortcode-generator');
$btp_shortcode_generator->addGroup( 'basic', 'Basic', 10 );
$btp_shortcode_generator->addGroup( 'grid', 'Grid', 40 );
$btp_shortcode_generator->addGroup( 'panels', 'Panels', 70 );
$btp_shortcode_generator->addGroup( 'posts', 'Posts', 200 );
$btp_shortcode_generator->addGroup( 'pages', 'Pages', 300 );
$btp_shortcode_generator->addGroup( 'works', 'Works', 400 );
$btp_shortcode_generator->addGroup( 'products', 'Products', 500 );
$btp_shortcode_generator->addGroup( 'clients', 'Clients', 600 );
$btp_shortcode_generator->addGroup( 'misc', 'Misc', 700 );


/* ----------------------------------------------------------------------------- */
/* ---------->>> page POST TYPE <<<--------------------------------------------- */
/* ----------------------------------------------------------------------------- */
require_once(BTP_THEME_DIR.'/post-types/page.php');
add_action('init', 'btp_init_page_post_type');

/* Page shortcodes */
require_once(BTP_THEME_DIR.'/shortcodes/pages.php');
add_shortcode('related_pages', 'btp_related_pages_shortcode');
add_shortcode('custom_pages', 'btp_custom_pages_shortcode');
$btp_shortcode_generator->addItem( btp_custom_pages_shortcode_generator_item(), 'pages');
$btp_shortcode_generator->addItem( btp_related_pages_shortcode_generator_item(), 'pages');



/* Page widgets */
require_once(BTP_THEME_DIR.'/widgets/pages_widgets.php');
add_action( 'widgets_init', 'btp_init_related_pages_widget' );
add_action( 'widgets_init', 'btp_init_custom_pages_widget' );
require_once(BTP_THEME_DIR.'/widgets/subpages.php');
add_action( 'widgets_init', 'btp_init_subpages_widget' );






/* ----------------------------------------------------------------------------- */
/* ---------->>> post POST TYPE <<<--------------------------------------------- */
/* ----------------------------------------------------------------------------- */
require_once(BTP_THEME_DIR.'/post-types/post.php');
add_action('init', 'btp_init_post_post_type');

/* Post shortcodes */
require_once(BTP_THEME_DIR.'/shortcodes/posts.php');
add_shortcode('recent_posts', 'btp_recent_posts_shortcode');
add_shortcode('related_posts', 'btp_related_posts_shortcode');
add_shortcode('custom_posts', 'btp_custom_posts_shortcode');
add_shortcode('popular_posts', 'btp_popular_posts_shortcode');
$btp_shortcode_generator->addItem( btp_custom_posts_shortcode_generator_item(), 'posts');
$btp_shortcode_generator->addItem( btp_popular_posts_shortcode_generator_item(), 'posts');
$btp_shortcode_generator->addItem( btp_recent_posts_shortcode_generator_item(), 'posts');
$btp_shortcode_generator->addItem( btp_related_posts_shortcode_generator_item(), 'posts');


/* Post widgets */
require_once(BTP_THEME_DIR.'/widgets/posts_widgets.php');
add_action( 'widgets_init', 'btp_init_recent_posts_widget' );
add_action( 'widgets_init', 'btp_init_related_posts_widget' );
add_action( 'widgets_init', 'btp_init_popular_posts_widget' );
add_action( 'widgets_init', 'btp_init_custom_posts_widget' );


/* Post filters */
add_filter('single_template', 'btp_post_single_template');





/* ----------------------------------------------------------------------------- */
/* ---------->>> btp_work POST TYPE <<<----------------------------------------- */
/* ----------------------------------------------------------------------------- */
require_once(BTP_THEME_DIR.'/post-types/btp_work.php');
add_action('init', 'btp_init_work_post_type');

/* Work shortcodes */
require_once(BTP_THEME_DIR.'/shortcodes/works.php');
add_shortcode('recent_works', 'btp_recent_works_shortcode');
add_shortcode('related_works', 'btp_related_works_shortcode');
add_shortcode('custom_works', 'btp_custom_works_shortcode');
add_shortcode('popular_works', 'btp_popular_works_shortcode');
$btp_shortcode_generator->addItem( btp_custom_works_shortcode_generator_item(), 'works');
$btp_shortcode_generator->addItem( btp_popular_works_shortcode_generator_item(), 'works');
$btp_shortcode_generator->addItem( btp_recent_works_shortcode_generator_item(), 'works');
$btp_shortcode_generator->addItem( btp_related_works_shortcode_generator_item(), 'works');


/* Work widgets */
require_once(BTP_THEME_DIR.'/widgets/works_widgets.php');
add_action( 'widgets_init', 'btp_init_recent_works_widget' );
add_action( 'widgets_init', 'btp_init_related_works_widget' );
add_action( 'widgets_init', 'btp_init_custom_works_widget' );
add_action( 'widgets_init', 'btp_init_popular_works_widget' );

/* Work filters */
add_filter('single_template', 'btp_work_single_template');
add_filter('nav_menu_css_class', 'btp_fix_work_nav_menu_css_class', 10, 2);

/* Work actions */
add_action('parse_query', 'btp_fix_work_archive_query');





/* ----------------------------------------------------------------------------- */
/* ---------->>> btp_product POST TYPE <<<-------------------------------------- */
/* ----------------------------------------------------------------------------- */
require_once(BTP_THEME_DIR.'/post-types/btp_product.php');
add_action('init', 'btp_init_product_post_type');

/* Product shortcodes */
require_once(BTP_THEME_DIR.'/shortcodes/products.php');
add_shortcode('price', 'btp_price_shortcode');

add_shortcode('recent_products', 'btp_recent_products_shortcode');
add_shortcode('related_products', 'btp_related_products_shortcode');
add_shortcode('custom_products', 'btp_custom_products_shortcode');
add_shortcode('popular_products', 'btp_popular_products_shortcode');
$btp_shortcode_generator->addItem( btp_custom_products_shortcode_generator_item(), 'products');
$btp_shortcode_generator->addItem( btp_popular_products_shortcode_generator_item(), 'products');
$btp_shortcode_generator->addItem( btp_recent_products_shortcode_generator_item(), 'products');
$btp_shortcode_generator->addItem( btp_related_products_shortcode_generator_item(), 'products');


/* Product widgets */
require_once(BTP_THEME_DIR.'/widgets/products_widgets.php');
add_action( 'widgets_init', 'btp_init_recent_products_widget' );
add_action( 'widgets_init', 'btp_init_related_products_widget' );
add_action( 'widgets_init', 'btp_init_custom_products_widget' );
add_action( 'widgets_init', 'btp_init_popular_products_widget' );

/* Product filters */
add_filter('single_template', 'btp_product_single_template');
add_filter('nav_menu_css_class', 'btp_fix_product_nav_menu_css_class', 10, 2);

/* Product actions */
add_action('parse_query', 'btp_fix_product_archive_query');




/* ----------------------------------------------------------------------------- */
/* ---------->>> btp_client POST TYPE- <<<-------------------------------------- */
/* ----------------------------------------------------------------------------- */
require_once(BTP_THEME_DIR.'/post-types/btp_client.php');
add_action('init', 'btp_init_client_post_type');

/* Client shortcodes */
require_once(BTP_THEME_DIR.'/shortcodes/clients.php');
add_shortcode('recent_clients', 'btp_recent_clients_shortcode');
add_shortcode('related_clients', 'btp_related_clients_shortcode');
add_shortcode('custom_clients', 'btp_custom_clients_shortcode');
$btp_shortcode_generator->addItem( btp_custom_clients_shortcode_generator_item(), 'clients');
$btp_shortcode_generator->addItem( btp_recent_clients_shortcode_generator_item(), 'clients');
$btp_shortcode_generator->addItem( btp_related_clients_shortcode_generator_item(), 'clients');



/* Client widgets */
require_once(BTP_THEME_DIR.'/widgets/clients_widgets.php');
add_action( 'widgets_init', 'btp_init_recent_clients_widget' );
add_action( 'widgets_init', 'btp_init_custom_clients_widget' );
add_action( 'widgets_init', 'btp_init_related_clients_widget' );

/* Client filters */
add_filter('single_template', 'btp_client_single_template');
add_filter('nav_menu_css_class', 'btp_fix_client_nav_menu_css_class', 10, 2);




/* ----------------------------------------------------------------------------- */
/* ---------->>> btp_slide POST TYPE- <-<<-------------------------------------- */
/* ----------------------------------------------------------------------------- */
require_once(BTP_THEME_DIR.'/post-types/btp_slide.php');
add_action('init', 'btp_init_slide_post_type');




/* ----------------------------------------------------------------------------- */
/* ---------->>> FILTERS <<<---------------------------------------------------- */
/* ----------------------------------------------------------------------------- */
add_filter('comment_form_default_fields', 'btp_comment_form_default_fields');
add_filter('comment_form_field_comment', 'btp_comment_form_field_comment');
add_filter('comment_form_defaults', 'btp_comment_form_defaults');

add_filter('the_password_form', 'btp_get_the_password_form');

add_filter('embed_oembed_html', 'btp_flash_wmode_transparent', 10, 3);

/* Enable shortcodes in widgets */
if (!is_admin()) add_filter('widget_text', 'do_shortcode', 11);

/* ----------------------------------------------------------------------------- */
/* ---------->>> ACTIONS <<<---------------------------------------------------- */
/* ----------------------------------------------------------------------------- */
add_action('init', 'btp_register_theme_nav_menus');
add_action('init', 'btp_sidebar_generator_init');		
add_action('init', 'btp_init_relation_tag_taxonomy');


/* Init Theme Options  */
add_action('init', 'btp_add_theme_options');
add_action('admin_init', 'btp_theme_admin_init');
add_action('admin_menu', 'btp_theme_admin_menu');
add_action('init', 'btp_install_theme_options');

/* Remove WordPress trail for security reasons */
remove_action('wp_head', 'wp_generator');

/* Add tracking code */
add_action('wp_footer', 'btp_render_tracking_code');

/* ----------------------------------------------------------------------------- */
/* ---------->>> SHORTCODES <<<------------------------------------------------- */
/* ----------------------------------------------------------------------------- */

require_once(BTP_THEME_DIR.'/shortcodes/grid.php');
add_shortcode('c_1', 'btp_c_1_shortcode');
add_shortcode('c_2', 'btp_c_2_shortcode');
add_shortcode('c_3', 'btp_c_3_shortcode');
add_shortcode('c_4', 'btp_c_4_shortcode');
add_shortcode('c_5', 'btp_c_5_shortcode');
add_shortcode('c_6', 'btp_c_6_shortcode');
add_shortcode('c_7', 'btp_c_7_shortcode');
add_shortcode('c_8', 'btp_c_8_shortcode');
add_shortcode('c_9', 'btp_c_9_shortcode');
add_shortcode('c_10', 'btp_c_10_shortcode');
add_shortcode('c_11', 'btp_c_11_shortcode');
add_shortcode('c_12', 'btp_c_12_shortcode');
add_shortcode('cc_1', 'btp_c_1_shortcode');
add_shortcode('cc_2', 'btp_c_2_shortcode');
add_shortcode('cc_3', 'btp_c_3_shortcode');
add_shortcode('cc_4', 'btp_c_4_shortcode');
add_shortcode('cc_5', 'btp_c_5_shortcode');
add_shortcode('cc_6', 'btp_c_6_shortcode');
add_shortcode('cc_7', 'btp_c_7_shortcode');
add_shortcode('cc_8', 'btp_c_8_shortcode');
add_shortcode('cc_9', 'btp_c_9_shortcode');
add_shortcode('cc_10', 'btp_c_10_shortcode');
add_shortcode('cc_11', 'btp_c_11_shortcode');
add_shortcode('cc_12', 'btp_c_12_shortcode');

$btp_shortcode_generator->addItem( btp_c_3_c_9_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_3_c_3_c_3_c_3_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_4_c_4_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_4_c_8_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_4_c_4_c_4_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_6_c_6_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_4_c_8_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_9_c_3_shortcode_generator_item(), 'grid');	
	
$btp_shortcode_generator->addItem( btp_c_1_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_2_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_3_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_4_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_5_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_6_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_7_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_8_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_9_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_10_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_11_shortcode_generator_item(), 'grid');
$btp_shortcode_generator->addItem( btp_c_12_shortcode_generator_item(), 'grid');


require_once(BTP_THEME_DIR.'/shortcodes/standard.php');
add_shortcode('pullquote', 'btp_pullquote_shortcode');
$btp_shortcode_generator->addItem( btp_pullquote_shortcode_generator_item(), 'basic');

add_shortcode('message', 'btp_message_shortcode');
$btp_shortcode_generator->addItem( btp_message_shortcode_generator_item(), 'basic');

add_shortcode('dropcap', 'btp_dropcap_shortcode');
$btp_shortcode_generator->addItem( btp_dropcap_shortcode_generator_item(), 'basic');

add_shortcode('mark', 'btp_mark_shortcode');
$btp_shortcode_generator->addItem( btp_mark_shortcode_generator_item(), 'basic');

add_shortcode('checklist', 'btp_checklist_shortcode');
$btp_shortcode_generator->addItem( btp_checklist_shortcode_generator_item(), 'basic');

add_shortcode('crosslist', 'btp_crosslist_shortcode');
$btp_shortcode_generator->addItem( btp_crosslist_shortcode_generator_item(), 'basic');

add_shortcode('divider', 'btp_divider_shortcode');
$btp_shortcode_generator->addItem( btp_divider_shortcode_generator_item(), 'basic');

add_shortcode('divider_top', 'btp_divider_top_shortcode');
$btp_shortcode_generator->addItem( btp_divider_top_shortcode_generator_item(), 'basic');

add_shortcode('space', 'btp_space_shortcode');
$btp_shortcode_generator->addItem( btp_space_shortcode_generator_item(), 'basic');

add_shortcode('clear', 'btp_clear_shortcode');


require_once(BTP_THEME_DIR.'/shortcodes/button.php');
add_shortcode('button', 'btp_button_shortcode');
$btp_shortcode_generator->addItem( btp_button_shortcode_generator_item(), 'basic');


require_once(BTP_THEME_DIR.'/shortcodes/contact_form.php');
add_shortcode('contact_form', 'btp_contact_form_shortcode');
$btp_shortcode_generator->addItem( btp_contact_form_shortcode_generator_item(), 'misc');


require_once(BTP_THEME_DIR.'/shortcodes/google_map.php');
add_shortcode('google_map', 'btp_google_map_shortcode');
$btp_shortcode_generator->addItem( btp_google_map_shortcode_generator_item(), 'misc');

require_once(BTP_THEME_DIR.'/shortcodes/twitter.php');
add_shortcode('twitter', 'btp_twitter_shortcode');
$btp_shortcode_generator->addItem( btp_twitter_shortcode_generator_item(), 'misc');


require_once(BTP_THEME_DIR.'/shortcodes/feeds.php');
add_shortcode('feeds', 'btp_feeds_shortcode');
$btp_shortcode_generator->addItem( btp_feeds_shortcode_generator_item(), 'misc');


require_once(BTP_THEME_DIR.'/shortcodes/progress_bar.php');
add_shortcode('progress_bar', 'btp_progress_bar_shortcode');
$btp_shortcode_generator->addItem( btp_progress_bar_shortcode_generator_item(), 'misc');

add_shortcode('countdown', 'btp_countdown_shortcode');
$btp_shortcode_generator->addItem( btp_countdown_shortcode_generator_item(), 'misc');


require_once(BTP_THEME_DIR.'/shortcodes/interactive.php');
add_shortcode('toggle', 'btp_toggle_shortcode');
$btp_shortcode_generator->addItem( btp_toggle_shortcode_generator_item(), 'panels');

add_shortcode('accordion', 'btp_accordion_shortcode');
$btp_shortcode_generator->addItem( btp_2_accordions_shortcode_generator_item(), 'panels');
$btp_shortcode_generator->addItem( btp_3_accordions_shortcode_generator_item(), 'panels');
$btp_shortcode_generator->addItem( btp_4_accordions_shortcode_generator_item(), 'panels');
$btp_shortcode_generator->addItem( btp_accordion_shortcode_generator_item(), 'panels');

add_shortcode('tab', 'btp_tab_shortcode');
$btp_shortcode_generator->addItem( btp_2_tabs_shortcode_generator_item(), 'panels');
$btp_shortcode_generator->addItem( btp_3_tabs_shortcode_generator_item(), 'panels');
$btp_shortcode_generator->addItem( btp_4_tabs_shortcode_generator_item(), 'panels');
$btp_shortcode_generator->addItem( btp_tab_shortcode_generator_item(), 'panels');


require_once(BTP_THEME_DIR.'/shortcodes/slider.php');
add_shortcode('slider', 'btp_slider_shortcode');
$btp_shortcode_generator->addItem( btp_slider_shortcode_generator_item(), 'misc');

add_shortcode('dice_slider', 'btp_dice_slider_shortcode');
$btp_shortcode_generator->addItem( btp_dice_slider_shortcode_generator_item(), 'misc');


require_once(BTP_THEME_DIR.'/shortcodes/misc.php');
add_shortcode('icon', 'btp_icon_shortcode');
$btp_shortcode_generator->addItem( btp_icon_shortcode_generator_item(), 'basic');

add_shortcode('precontent', 'btp_precontent_shortcode');
$btp_shortcode_generator->addItem( btp_precontent_shortcode_generator_item(), 'misc');

add_shortcode('wp_caption', 'btp_img_caption_shortcode');
add_shortcode('caption', 'btp_img_caption_shortcode');


require_once(BTP_THEME_DIR.'/shortcodes/frame.php');
add_shortcode('frame', 'btp_frame_shortcode');
$btp_shortcode_generator->addItem( btp_frame_shortcode_generator_item(), 'basic');


/* ----------------------------------------------------------------------------- */
/* ---------->>> WIDGETS <<<---------------------------------------------------- */
/* ----------------------------------------------------------------------------- */
require_once(BTP_THEME_DIR.'/widgets/contact_form.php');
add_action( 'widgets_init', 'btp_init_contact_form_widget' );

require_once(BTP_THEME_DIR.'/widgets/twitter.php');
add_action( 'widgets_init', 'btp_init_twitter_widget' );

require_once(BTP_THEME_DIR.'/widgets/feeds.php');
add_action( 'widgets_init', 'btp_init_feeds_widget' );

require_once(BTP_THEME_DIR.'/widgets/about_author.php');
add_action( 'widgets_init', 'btp_init_about_author_widget' );

?>