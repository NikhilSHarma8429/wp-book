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
