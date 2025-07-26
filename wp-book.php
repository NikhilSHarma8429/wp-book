<?php
    /*
        * Plugin Name: WP BOOK
        * Author: Nikhil
        * Version: 1.0.0
        * Description: This is a plugin made for wordpress assignment
        * Author URI: https://example.com
        * Plugin URI: https://example.com
    */

    if(!defined('ABSPATH')) exit;

    function wp_book_register_post_type() {
        $labels = array(
            'name'               => __( 'Books', 'wp-book' ),
            'singular_name'      => __( 'Book', 'wp-book' ),
            'menu_name'          => __( 'Books', 'wp-book' ),
            'name_admin_bar'     => __( 'Book', 'wp-book' ),
            'add_new'            => __( 'Add New', 'wp-book' ),
            'add_new_item'       => __( 'Add New Book', 'wp-book' ),
            'new_item'           => __( 'New Book', 'wp-book' ),
            'edit_item'          => __( 'Edit Book', 'wp-book' ),
            'view_item'          => __( 'View Book', 'wp-book' ),
            'all_items'          => __( 'All Books', 'wp-book' ),
            'search_items'       => __( 'Search Books', 'wp-book' ),
            'not_found'          => __( 'No books found.', 'wp-book' ),
            'not_found_in_trash' => __( 'No books found in Trash.', 'wp-book' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,                          // Make it accessible on the frontend
            'publicly_queryable' => true,                          // Can query via URL
            'show_ui'            => true,                          // Show UI in admin
            'show_in_menu'       => true,                          // Show in admin sidebar
            'query_var'          => true,                          // Enable query var like ?book=my-title
            'rewrite'            => array( 'slug' => 'book' ),     // URL slug: /book/book-title
            'capability_type'    => 'post',                        // Behave like posts
            'has_archive'        => true,                          // Enable archive pages
            'hierarchical'       => false,                         // Flat structure (like posts, not pages)
            'menu_position'      => 5,                             // Position under Posts
            'menu_icon'          => 'dashicons-book',              // Dashicon for Books
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'show_in_rest'       => true                           // Required for Gutenberg support
        );

        register_post_type( 'book', $args );
    }
    add_action( 'init', 'wp_book_register_post_type' );

    function wp_book_register_book_category_taxonomy() {
    $labels = array(
            'name'              => __( 'Book Categories', 'wp-book' ),
            'singular_name'     => __( 'Book Category', 'wp-book' ),
            'search_items'      => __( 'Search Book Categories', 'wp-book' ),
            'all_items'         => __( 'All Book Categories', 'wp-book' ),
            'parent_item'       => __( 'Parent Category', 'wp-book' ),
            'parent_item_colon' => __( 'Parent Category:', 'wp-book' ),
            'edit_item'         => __( 'Edit Book Category', 'wp-book' ),
            'update_item'       => __( 'Update Book Category', 'wp-book' ),
            'add_new_item'      => __( 'Add New Book Category', 'wp-book' ),
            'new_item_name'     => __( 'New Book Category Name', 'wp-book' ),
            'menu_name'         => __( 'Book Categories', 'wp-book' ),
        );

        $args = array(
            'hierarchical'      => true, // Like categories
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true, // Show in Book list table
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'book-category' ),
            'show_in_rest'      => true  // Gutenberg support
        );

        register_taxonomy( 'book_category', array( 'book' ), $args );
    }
    add_action( 'init', 'wp_book_register_book_category_taxonomy' );

    function wp_book_register_book_tag_taxonomy() {
        $labels = array(
            'name'                       => __( 'Book Tags', 'wp-book' ),
            'singular_name'              => __( 'Book Tag', 'wp-book' ),
            'search_items'               => __( 'Search Book Tags', 'wp-book' ),
            'popular_items'              => __( 'Popular Book Tags', 'wp-book' ),
            'all_items'                  => __( 'All Book Tags', 'wp-book' ),
            'edit_item'                  => __( 'Edit Book Tag', 'wp-book' ),
            'update_item'                => __( 'Update Book Tag', 'wp-book' ),
            'add_new_item'               => __( 'Add New Book Tag', 'wp-book' ),
            'new_item_name'              => __( 'New Book Tag Name', 'wp-book' ),
            'separate_items_with_commas' => __( 'Separate tags with commas', 'wp-book' ),
            'add_or_remove_items'        => __( 'Add or remove book tags', 'wp-book' ),
            'choose_from_most_used'      => __( 'Choose from the most used book tags', 'wp-book' ),
            'menu_name'                  => __( 'Book Tags', 'wp-book' ),
        );

        $args = array(
            'hierarchical'          => false, // Like post tags
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'book-tag' ),
            'show_in_rest'          => true
        );

        register_taxonomy( 'book_tag', 'book', $args );
    }
    add_action( 'init', 'wp_book_register_book_tag_taxonomy' );
