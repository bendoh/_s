<?php
/**
 * Oomph Custom Taxonomy
 * @uses register_taxonomy()
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 *
**/
function oomph_custom_taxonomy() {
  $labels = array(
    'name' => _x( 'Oomph', 'oomph' ),
    'singular_name' => _x( 'Oomph', 'oomph' ),
    'search_items' =>  __( 'Search Oomph' ),
    'all_items' => __( 'All Oomph' ),
    'parent_item' => __( 'Parent Oomph' ),
    'parent_item_colon' => __( 'Parent Oomph:' ),
    'edit_item' => __( 'Edit Oomph' ),
    'update_item' => __( 'Update Oomph' ),
    'add_new_item' => __( 'Add New Oomph' ),
    'new_item_name' => __( 'New Oomph Name' ),
    'menu_name' => __( 'Oomph' ),
  );

  register_taxonomy( 'oomph', array( 'post' ), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'oomph' ),
  ));
}
add_action( 'init', 'oomph_custom_taxonomy', 0 );