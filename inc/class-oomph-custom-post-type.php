<?php

class Oomph_Class {

  var $post_type = 'some_post_type';
  var $taxonomy = 'some_taxonomy';

  var $some_key_value = '_some_key_value';
  
  function __construct() {
    add_action( 'init', array( $this, 'create_post_type' ) );
    add_action( 'init', array( $this, 'create_taxonomy' ) );     
    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );  
    add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );
  }

  /**
   * Creates our Custom Post Type
   * @uses register_post_type()
   * @link http://codex.wordpress.org/Function_Reference/register_post_type
   *
  **/
  function create_post_type() {
    $labels = array(
      'name' => _x('Books', 'post type general name', 'oomph'),
      'singular_name' => _x('Book', 'post type singular name', 'oomph'),
      'add_new' => _x('Add New', 'book', 'oomph'),
      'add_new_item' => __('Add New Book', 'oomph'),
      'edit_item' => __('Edit Book', 'oomph'),
      'new_item' => __('New Book', 'oomph'),
      'all_items' => __('All Books', 'oomph'),
      'view_item' => __('View Book', 'oomph'),
      'search_items' => __('Search Books', 'oomph'),
      'not_found' =>  __('No books found', 'oomph'),
      'not_found_in_trash' => __('No books found in Trash', 'oomph'), 
      'parent_item_colon' => '',
      'menu_name' => __('Books', 'oomph')
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true, 
      'show_in_menu' => true, 
      'query_var' => true,
      'rewrite' => array( 'slug' => _x( 'book', 'URL slug', 'oomph' ) ),
      'capability_type' => 'post',
      'has_archive' => true, 
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    ); 
    register_post_type( $this->post_type, $args );
  }

  /**
   * Creates our Custom Taxonomy
   * @uses register_taxonomy()
   * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
   *
  **/
  function create_taxonomy() {
    $labels = array(
      'name' => _x( 'Genres', 'taxonomy general name' ),
      'singular_name' => _x( 'Genre', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Genres' ),
      'all_items' => __( 'All Genres' ),
      'parent_item' => __( 'Parent Genre' ),
      'parent_item_colon' => __( 'Parent Genre:' ),
      'edit_item' => __( 'Edit Genre' ), 
      'update_item' => __( 'Update Genre' ),
      'add_new_item' => __( 'Add New Genre' ),
      'new_item_name' => __( 'New Genre Name' ),
      'menu_name' => __( 'Genre' ),
    );  

    register_taxonomy( $this->taxonomy, array( $this->post_type ), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'genre' ),
    ));
  }

  /**
   * Add your custom meta boxes
   * @uses add_meta_box()
   * @link http://codex.wordpress.org/Function_Reference/add_meta_box
   *
   **/
  function add_meta_boxes() {
    add_meta_box( 
      'example_metabox',                      // $id
      'Example Information',                  // $title
      array( $this, 'example_add_meta_box' ), // $callback
      $this->post_type,                       // $post_type
      'normal',                               // $context
      'default'                               // $priority
    );
  }
  
  function example_add_meta_box( $post ) { ?>
  <?php $example = get_post_meta( $post->ID, $this->some_key_value, true ); ?>
  <input type="hidden" name="book_noncename" id="book_noncename" value="<?php echo wp_create_nonce( 'book' ); ?>" />
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td>
        <label for=""></label><br/>
        <input type="text" name="<?php echo $this->some_key_value ;?>" id="<?php echo $this->some_key_value ;?>" class="widefat" size="30" value="<?php echo esc_attr_e( $example );?>" />
      </td>
    </tr>
  </table>
  <?php }

  function save_post( $post_id, $post ) { 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( ! array_key_exists( 'book_noncename', $_POST ))
      return;
  
    if ( ! wp_verify_nonce( $_POST['book_noncename'], 'book' ) ) {
      return $post->ID;
    }
  
    // Is the user allowed to edit the post or page?
    if ( ! current_user_can( 'edit_post', $post->ID ) )
      return $post->ID;

    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.

    //textfields
    $oomph_custom_meta[$this->some_key_value] = sanitize_text_field( $_POST[$this->some_key_value] );
    
    
    // Add values of $events_meta as custom fields
    foreach ( $oomph_custom_meta as $key => $value ) { // Cycle through the $oomph_custom_meta array!
      
      if ( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode( ',', ( array )$value ); // If $value is an array, make it a CSV (unlikely)
      
      if ( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
            update_post_meta( $post->ID, $key, $value );
      } else {
        // If the custom field doesn't have a value
        add_post_meta( $post->ID, $key, $value );
      }
      
      if ( ! $value ) delete_post_meta( $post->ID, $key ); // Delete if blank
    }
  }
}

oomph_singleton( 'Oomph_Class' );