<?php
/* myPluginClass, myCustomPostType, myCustomTaxonomy, myCustomMeta
 *
 *
 */

class MyPluginClass {

  public $some_key_value = '_some_key_value';
  
  public function __construct() {
    add_action( 'init', array( $this, 'myCustomPostType_create' ) );
    add_action( 'init', array( $this, 'myCustomTaxonomy_create' ) );     
    add_action( 'add_meta_boxes', array( $this, 'myCustomPostType_add_meta_boxes' ) );  
    add_action( 'save_post', array( $this, 'myCustomPostType_save_post' ) );
  }

  /**
   * Creates our Custom Post Type
   * @uses register_post_type()
   * @link http://codex.wordpress.org/Function_Reference/register_post_type
   *
  **/
  function myCustomPostType_create() {
    $labels = array(
      'name' => _x('Books', 'post type general name', 'your_text_domain'),
      'singular_name' => _x('Book', 'post type singular name', 'your_text_domain'),
      'add_new' => _x('Add New', 'book', 'your_text_domain'),
      'add_new_item' => __('Add New Book', 'your_text_domain'),
      'edit_item' => __('Edit Book', 'your_text_domain'),
      'new_item' => __('New Book', 'your_text_domain'),
      'all_items' => __('All Books', 'your_text_domain'),
      'view_item' => __('View Book', 'your_text_domain'),
      'search_items' => __('Search Books', 'your_text_domain'),
      'not_found' =>  __('No books found', 'your_text_domain'),
      'not_found_in_trash' => __('No books found in Trash', 'your_text_domain'), 
      'parent_item_colon' => '',
      'menu_name' => __('Books', 'your_text_domain')
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true, 
      'show_in_menu' => true, 
      'query_var' => true,
      'rewrite' => array( 'slug' => _x( 'book', 'URL slug', 'your_text_domain' ) ),
      'capability_type' => 'post',
      'has_archive' => true, 
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    ); 
    register_post_type( 'book', $args );
  }

  /**
   * Creates our Custom Taxonomy
   * @uses register_taxonomy()
   * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
   *
  **/
  function myCustomTaxonomy_create() {
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

    register_taxonomy( 'genre', array( 'book' ), array(
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
  function myCustomPostType_add_meta_boxes() {
    add_meta_box( 
      'example_metabox',                      // $id
      'Example Information',                  // $title
      array( $this, 'example_add_meta_box' ), // $callback
      'example',                              // $post_type
      'normal',                               // $context
      'default'                               // $priority
    );
  }
  
  function example_add_meta_box( $post ) { ?>
  <input type="hidden" name="book_noncename" id="book_noncename" value="<?php echo wp_create_nonce( 'book' ); ?>" />
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td>
        <label for=""></label><br/>
        <input type="text" name="<?php echo $this->some_key_value ;?>" id="<?php echo $this->some_key_value ;?>" class="widefat" size="30" value="" />
      </td>
    </tr>
  </table>
  <?php }

  function myCustomPostType_save_post( $post_id, $post ) { 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( ! array_key_exists( 'book_noncename', $_POST ))
      return;
  
    if ( ! wp_verify_nonce( $_POST['book_noncename'], 'MyPluginClass' ) ) {
      return $post->ID;
    }
  
    // Is the user allowed to edit the post or page?
    if ( ! current_user_can( 'edit_post', $post->ID ) )
      return $post->ID;

    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.

    //textfields
    $myCustomMeta[$this->some_key_value] = sanitize_text_field( $_POST[$this->some_key_value] );
    
    
    // Add values of $events_meta as custom fields
    foreach ( $myCustomMeta as $key => $value ) { // Cycle through the $myCustomMeta array!
      
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

global $MyPluginClass;
if ( !is_a ( $MyPluginClass, 'MyPluginClass' ) )
  $MyPluginClass = new MyPluginClass();