<?php
/**
 * Oomph functions and definitions
 *
 * @package Oomph
 * @since Oomph 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Oomph 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'oomph_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Oomph 1.0
 */
function oomph_setup() {

	/**
	 * For VIP use.
	 */
	//require( get_template_directory() . '/inc/vip/vip.php' );

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Theme feature functions and definitions
	 */
	//require( get_template_directory() . '/inc/features/class-breadcrumbs.php' );
	//require( get_template_directory() . '/inc/features/dynamic-lead/...' );
	//require( get_template_directory() . '/inc/features/single-dynamic-carousel/...' );

	/**
	 * Theme plugin functions and definitions
	 */
	//require( get_template_directory() . '/inc/plugins/oomph-calendar' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Oomph, use a find and replace
	 * to change 'oomph' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'oomph', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'oomph' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // oomph_setup
add_action( 'after_setup_theme', 'oomph_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Oomph 1.0
 */
function oomph_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'oomph' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'oomph_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function oomph_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'theme', get_template_directory_uri() . '/css/theme.css' );
	wp_enqueue_style( 'archive', get_template_directory_uri() . '/css/archive.css' );
	wp_enqueue_style( 'single', get_template_directory_uri() . '/css/single.css' );
	wp_enqueue_style( 'comments', get_template_directory_uri() . '/css/comments.css' );
	//wp_enqueue_style( '768', get_template_directory_uri() . '/css/768.css' );
	//wp_enqueue_style( '480', get_template_directory_uri() . '/css/480.css' );
	//wp_enqueue_style( '320', get_template_directory_uri() . '/css/320.css' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'theme', get_template_directory_uri() . '/js/theme.js', array( 'jquery' ), '20120909', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'oomph_scripts' );

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );
