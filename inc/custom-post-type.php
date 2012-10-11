<?php
/* myPluginClass, myCustomPostType, myCustomTaxonomy, myCustomMeta
 *
 *
 */

class MyPluginClass {

  public $some_key_value = '_some_key_value';
  public $boatspec_key = '_lh_listing_boatspec';
  
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
  
  function example_add_meta_box() { ?>
  <input type="hidden" name="*****_noncename" id="*****_noncename" value="<?php wp_create_nonce('*****' ); ?>" />
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td>
        <label for=""></label><br/>
        <input type="text" name="" id="" class="widefat" size="30" value="" />
      </td>
    </tr>
  </table>
  <?php }

  function myCustomPostType_save_post() { 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( ! array_key_exists( '*****_noncename', $_POST ))
      return;
  
    if ( ! wp_verify_nonce( $_POST['*****_noncename'], 'lh_listing' ) ) {
      return $post->ID;
    }
  
    // Is the user allowed to edit the post or page?
    if ( ! current_user_can( 'edit_post', $post->ID ) )
      return $post->ID;

    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.

    //textfields
    $myCustomMeta[$this->boatspec_key] = sanitize_text_field( $_POST[$this->boatspec_key] );
    
    
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













<?php
class lh_listing
{
public $boatspec_key = '_lh_listing_boatspec';
public $location_key = '_lh_listing_location';
public $cost_key = '_lh_listing_cost';
public $reduced_key = '_lh_listing_reduced';
public $sold_key = '_lh_listing_sold';
public $desc_key = '_lh_listing_desc';

public $spec_loa_key = '_lh_listing_spec_loa';
public $spec_beam_key = '_lh_listing_spec_beam';
public $spec_draft_key = '_lh_listing_spec_draft';
public $spec_year_key = '_lh_listing_spec_year';
public $spec_refit_key = '_lh_listing_spec_refit';
public $spec_dislacement_key = '_lh_listing_spec_dislacement';
public $spec_hull_key = '_lh_listing_spec_hull';
public $spec_designer_key = '_lh_listing_spec_designer';
public $spec_builder_key = '_lh_listing_spec_builder';
public $spec_engine_key = '_lh_listing_spec_engine';
public $spec_speed_key = '_lh_listing_spec_speed';
public $spec_slideshow_key = '_lh_listing_spec_slideshow';

function __construct()
{
      add_action( 'init', array($this,'create_ctp') );
      add_action( 'init', array($this,'create_tax') );
      add_action('save_post', array($this,'save_meta_boxes'), 1, 2); // save the custom fields
}


function create_tax() {
 $labels = array(
    'name' => _x( 'Boat Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Boat Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Boat Types' ),
    'all_items' => __( 'All Boats' ),
    'parent_item' => __( 'Parent Boat Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Boat Type' ),
    'update_item' => __( 'Update Boat Type' ),
    'add_new_item' => __( 'Add New Boat Type' ),
    'new_item_name' => __( 'New Boat Type Name' ),
  ); 	

  register_taxonomy('BoatType','listing',array(
    'hierarchical' => true,
    'labels' => $labels
  ));
}


function create_ctp() {
	register_post_type( 'listing',
		array(
			'labels' => array(
				'name' => __( 'Sale Listing' ),
				'all_items' => __( 'All Listings' ),
				'singular_name' => __( 'Sale Listing' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array(
			  'slug' => 'listing'
			),
			'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
			'register_meta_box_cb' => array($this,'add_meta_boxes')
		)
	);
}


// Add the Member Project Meta Boxes
function add_meta_boxes() {
    add_meta_box( 'listing_metabox', 'Listing Information', array($this,'add_meta_boxs'), 'listing', 'normal', 'default' );
    add_meta_box( 'listing_metabox_spec', 'Boat Specifications', array($this,'add_meta_boxs_spec'), 'listing', 'normal', 'default' );
}

function add_meta_boxs( $post ){
    echo '<input type="hidden" name="lh_listing_noncename" id="lh_listing_noncename" value="' . wp_create_nonce('lh_listing' ) . '" />';

    $bspec = get_post_meta($post->ID, $this->boatspec_key, true);
    $location = get_post_meta($post->ID, $this->location_key, true);
    $cost = get_post_meta($post->ID, $this->cost_key, true);
    $reduced = get_post_meta($post->ID, $this->reduced_key, true);
    $sold = get_post_meta($post->ID, $this->sold_key, true);

    echo '
    <table width="100%">
    <tr><td width="25%">Basic Spec: </td><td><input type="text" name="'.$this->boatspec_key.'" value="' . $bspec  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Location: </td><td><input type="text" name="'.$this->location_key.'" value="' . $location  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Cost: </td><td><input type="text" name="'.$this->cost_key.'" value="' . $cost  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Reduced Flag: </td><td><input type="checkbox" name="'.$this->reduced_key.'"';
    if ($reduced == 1)
        echo ' checked="checked" ';
    echo '/></td></tr><tr><td width="25%">Sold Flag: </td><td><input type="checkbox" name="'.$this->sold_key.'"';
    if ($sold == 1)
        echo ' checked="checked" ';
    echo '
      /></td></tr>
    </table>
    ';
}
function add_meta_boxs_spec( $post){
    $spec_slideshow = get_post_meta($post->ID, $this->spec_slideshow_key, true);
    $spec_loa = get_post_meta($post->ID, $this->spec_loa_key, true);
    $spec_beam = get_post_meta($post->ID, $this->spec_beam_key, true);
    $spec_draft = get_post_meta($post->ID, $this->spec_draft_key, true);
    $spec_year = get_post_meta($post->ID, $this->spec_year_key, true);
    $spec_refit = get_post_meta($post->ID, $this->spec_refit_key, true);
    $spec_dislacement = get_post_meta($post->ID, $this->spec_dislacement_key, true);
    $spec_hull = get_post_meta($post->ID, $this->spec_hull_key, true);
    $spec_designer = get_post_meta($post->ID, $this->spec_designer_key, true);
    $spec_builder = get_post_meta($post->ID, $this->spec_builder_key, true);
    $spec_engine = get_post_meta($post->ID, $this->spec_engine_key, true);
    $spec_speed = get_post_meta($post->ID, $this->spec_speed_key, true);
    
    echo '
    <table width="100%">
    <tr><td width="25%">Slideshow ID: </td><td><input type="text" name="'.$this->spec_slideshow_key.'" value="' . $spec_slideshow  . '" class="widefat" /></td></tr>
    <tr><td width="25%">LOA: </td><td><input type="text" name="'.$this->spec_loa_key.'" value="' . $spec_loa  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Beam: </td><td><input type="text" name="'.$this->spec_beam_key.'" value="' . $spec_beam  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Draft: </td><td><input type="text" name="'.$this->spec_draft_key.'" value="' . $spec_draft  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Year: </td><td><input type="text" name="'.$this->spec_year_key.'" value="' . $spec_year  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Refit: </td><td><input type="text" name="'.$this->spec_refit_key.'" value="' . $spec_refit  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Displacement: </td><td><input type="text" name="'.$this->spec_dislacement_key.'" value="' . $spec_dislacement  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Hull: </td><td><input type="text" name="'.$this->spec_hull_key.'" value="' . $spec_hull  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Designer: </td><td><input type="text" name="'.$this->spec_designer_key.'" value="' . $spec_designer  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Builder: </td><td><input type="text" name="'.$this->spec_builder_key.'" value="' . $spec_builder  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Engine: </td><td><input type="text" name="'.$this->spec_engine_key.'" value="' . $spec_engine  . '" class="widefat" /></td></tr>
    <tr><td width="25%">Speed: </td><td><input type="text" name="'.$this->spec_speed_key.'" value="' . $spec_speed  . '" class="widefat" /></td></tr>
    </table>
    ';
}

function save_meta_boxes( $post_id, $post ) {
  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if(! array_key_exists('lh_listing_noncename', $_POST))
    return;
  if ( !wp_verify_nonce( $_POST['lh_listing_noncename'],'lh_listing' )) {
    return $post->ID;
  }
  
  // Is the user allowed to edit the post or page?
  if ( !current_user_can( 'edit_post', $post->ID ))
      return $post->ID;

  // OK, we're authenticated: we need to find and save the data
  // We'll put it into an array to make it easier to loop though.

    $mp_meta[$this->boatspec_key] = sanitize_text_field($_POST[$this->boatspec_key]);
    $mp_meta[$this->location_key] = sanitize_text_field($_POST[$this->location_key]);
    $mp_meta[$this->cost_key] = sanitize_text_field($_POST[$this->cost_key]);
    $mp_meta[$this->reduced_key] = $_POST[$this->reduced_key] == true ? '1' : '';
    $mp_meta[$this->sold_key] = $_POST[$this->sold_key] == true ? '1' : '';
    $mp_meta[$this->spec_slideshow_key] = sanitize_text_field($_POST[$this->spec_slideshow_key]);
    $mp_meta[$this->spec_loa_key] = sanitize_text_field($_POST[$this->spec_loa_key]);
    $mp_meta[$this->spec_beam_key] = sanitize_text_field($_POST[$this->spec_beam_key]);
    $mp_meta[$this->spec_draft_key] = sanitize_text_field($_POST[$this->spec_draft_key]);
    $mp_meta[$this->spec_year_key] = sanitize_text_field($_POST[$this->spec_year_key]);
    $mp_meta[$this->spec_refit_key] = sanitize_text_field($_POST[$this->spec_refit_key]);
    $mp_meta[$this->spec_dislacement_key] = sanitize_text_field($_POST[$this->spec_dislacement_key]);
    $mp_meta[$this->spec_hull_key] = sanitize_text_field($_POST[$this->spec_hull_key]);
    $mp_meta[$this->spec_designer_key] = sanitize_text_field($_POST[$this->spec_designer_key]);
    $mp_meta[$this->spec_builder_key] = sanitize_text_field($_POST[$this->spec_builder_key]);
    $mp_meta[$this->spec_engine_key] = sanitize_text_field($_POST[$this->spec_engine_key]);
    $mp_meta[$this->spec_speed_key] = sanitize_text_field($_POST[$this->spec_speed_key]);
    
    

  // Add values of $events_meta as custom fields

  foreach ($mp_meta as $key => $value) { // Cycle through the $mp_meta array!
      if( $post->post_type == 'revision' ) return; // Don't store custom data twice
      $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
      if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
          update_post_meta($post->ID, $key, $value);
      } else { // If the custom field doesn't have a value
          add_post_meta($post->ID, $key, $value);
      }
      if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
  }
}
}

lh_singleton( 'lh_listing' );
?>