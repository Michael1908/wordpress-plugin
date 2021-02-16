<?php
/**
 * @package Threekit
 */
/*
Plugin Name: Threekit
Plugin URI: https://source360group.com/
Description: Threekit plugin to handle data.
Version: 0.1
Author: Source 360
Author URI: https://source360group.com/
License:
Text Domain:
*/


function threekit_register_post_type() {
     
    // products
    $labels = array( 
        'name' => __( 'Threekit' , 'threekit' ),
        'singular_name' => __( 'Product' , 'threekit' ),
        'add_new' => __( 'New Product' , 'threekit' ),
        'add_new_item' => __( 'Add New Product' , 'threekit' ),
        'edit_item' => __( 'Edit Product' , 'threekit' ),
        'new_item' => __( 'New Product' , 'threekit' ),
        'view_item' => __( 'View Product' , 'threekit' ),
        'search_items' => __( 'Search Products' , 'threekit' ),
        'not_found' =>  __( 'No Products Found' , 'threekit' ),
        'not_found_in_trash' => __( 'No Products found in Trash' , 'threekit' ),
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
        'supports' => array(
            'title', 
            'editor', 
            'excerpt', 
            'custom-fields', 
            'thumbnail',
            'page-attributes'
        ),
        'rewrite'   => array( 'slug' => 'products' ),
        'show_in_rest' => true
 
    );
    register_post_type( 'threekit_product', $args );
         
}

function threekit_product_styles() {
    // wp_enqueue_style( 'products',  plugin_dir_url( __FILE__ ) . ‘/css/threekit.css’ );                      
}

add_action( 'init', 'threekit_register_post_type' );
add_action( 'wp_enqueue_scripts', 'threekit_product_styles' );