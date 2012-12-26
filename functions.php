<?php
/**
 * _s functions and definitions
 *
 * @package _s
 * @since _s 2.0
 */

/*
 * VIP functions, per http://lobby.vip.wordpress.com/getting-started/development-environment/
 */

// Init WP.com VIP environment
require_once( WP_CONTENT_DIR . '/themes/vip/plugins/vip-init.php' );

// VIP Plugins
if ( function_exists( 'wpcom_vip_load_plugin' ) ) {
	//wpcom_vip_load_plugin( 'facebook' );
}

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since _s 2.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( '_s_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since _s 2.0
 */
function _s_setup() {

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
	require( get_template_directory() . '/inc/extras.php' );

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
	require( get_template_directory() . '/inc/class-oomph-custom-post-type.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on _s, use a find and replace
	 * to change '_s' to the name of your theme in all the template files
	 */
	//load_theme_textdomain( '_s', get_template_directory() . '/languages' );

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
		'primary' => __( 'Primary Menu', '_s' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // _s_setup
add_action( 'after_setup_theme', '_s_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since _s 2.0
 */
function _s_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', '_s' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', '_s_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function _s_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'theme', get_template_directory_uri() . '/css/theme.css' );
	wp_enqueue_style( 'archive', get_template_directory_uri() . '/css/archive.css' );
	wp_enqueue_style( 'single', get_template_directory_uri() . '/css/single.css' );
	wp_enqueue_style( 'comments', get_template_directory_uri() . '/css/comments.css' );
	// wp_enqueue_style( '768', get_template_directory_uri() . '/css/768.css' );
	// wp_enqueue_style( '480', get_template_directory_uri() . '/css/480.css' );
	// wp_enqueue_style( '320', get_template_directory_uri() . '/css/320.css' );
	wp_enqueue_style( 'developer', get_template_directory_uri() . '/css/developer.css' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'theme', get_template_directory_uri() . '/js/theme.js', array( 'jquery' ), '20120909', true );
	//wp_enqueue_script( 'developer', get_template_directory_uri() . '/js/developer.js', array( 'jquery' ), '20121129', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', '_s_scripts' );

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );


/** 
 * DO NOT EDIT BELOW THIS LINE ==========================================/
 */

/*
 * Placeholder class for radar_singleton that simulates a real object that does absolutely nothing
 * but prevents any code that depends on a missing class from causing fatal errors
 */
class _s_Placeholder {
	private $placeholder_class = '';
	private $reason = '';

	function __construct( $class, $reason ) {
		$this->placeholder_class = $class;
		$this->reason = $reason;
	}

	function __call( $method, $args ) {
		return null;
	}

	function __get( $name ) {
		return null;
	}

	function __set( $name, $value ) {
		return null;
	}
}

/*
 * Create and return singleton instance of an object class.
 * This should be used whenever a class needs a singleton
 * instance. Mostly for syntax cleanliness. A clean syntax
 * is a happy syntax. If the class is undefined or the singleton
 * is occupied by an object of a different type, then throw a
 * fatal error, because this should never happen.
 *
 * @param string $class - The class name, which will also become
 *		the instance's global name
 * @returns object
 */
function _s_singleton( $class ) {
	if ( ! class_exists( $class ) )
		return new _s_Placeholder( $class, 'Class does not exist' );

	if ( ! isset( $GLOBALS[ $class ] ) )
		$GLOBALS[ $class ] = new $class;

	if ( ! is_a( $GLOBALS[ $class ], $class ) )
		return new _s_Placeholder( $class, "Singleton assertion failed: The global object is not of the type `$class`" );

	return $GLOBALS[ $class ];
}
