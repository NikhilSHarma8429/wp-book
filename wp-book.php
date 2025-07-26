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

    function wp_book_add_meta_box() {
        add_meta_box(
            'wp_book_meta_box',              // ID
            __( 'Book Details', 'wp-book' ),// Title
            'wp_book_meta_box_callback',     // Callback function
            'book',                          // Screen (post type)
            'normal',                        // Context
            'default'                        // Priority
        );
    }
    add_action( 'add_meta_boxes', 'wp_book_add_meta_box' );

    
    function wp_book_meta_box_callback( $post ) {
        
        wp_nonce_field( 'wp_book_save_meta_box', 'wp_book_meta_box_nonce' );

        
        $author    = get_post_meta( $post->ID, '_wp_book_author', true );
        $price     = get_post_meta( $post->ID, '_wp_book_price', true );
        $publisher = get_post_meta( $post->ID, '_wp_book_publisher', true );
        $year      = get_post_meta( $post->ID, '_wp_book_year', true );
        $edition   = get_post_meta( $post->ID, '_wp_book_edition', true );
        $url       = get_post_meta( $post->ID, '_wp_book_url', true );

        ?>
        <p>
            <label><strong>Author Name:</strong></label><br>
            <input type="text" name="wp_book_author" value="<?php echo esc_attr( $author ); ?>" class="widefat">
        </p>
        <p>
            <label><strong>Price:</strong></label><br>
            <input type="number" step="0.01" name="wp_book_price" value="<?php echo esc_attr( $price ); ?>" class="widefat">
        </p>
        <p>
            <label><strong>Publisher:</strong></label><br>
            <input type="text" name="wp_book_publisher" value="<?php echo esc_attr( $publisher ); ?>" class="widefat">
        </p>
        <p>
            <label><strong>Year:</strong></label><br>
            <input type="number" name="wp_book_year" value="<?php echo esc_attr( $year ); ?>" class="widefat">
        </p>
        <p>
            <label><strong>Edition:</strong></label><br>
            <input type="text" name="wp_book_edition" value="<?php echo esc_attr( $edition ); ?>" class="widefat">
        </p>
        <p>
            <label><strong>URL:</strong></label><br>
            <input type="url" name="wp_book_url" value="<?php echo esc_attr( $url ); ?>" class="widefat">
        </p>
        <?php
    }

    function wp_book_save_meta_box_data( $post_id ) {
        if ( ! isset( $_POST['wp_book_meta_box_nonce'] ) || 
            ! wp_verify_nonce( $_POST['wp_book_meta_box_nonce'], 'wp_book_save_meta_box' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        global $wpdb;
        $table = $wpdb->prefix . 'book_meta';

        $data = [
            'author'    => sanitize_text_field( $_POST['wp_book_author'] ?? '' ),
            'price'     => floatval( $_POST['wp_book_price'] ?? 0 ),
            'publisher' => sanitize_text_field( $_POST['wp_book_publisher'] ?? '' ),
            'year'      => intval( $_POST['wp_book_year'] ?? 0 ),
            'edition'   => sanitize_text_field( $_POST['wp_book_edition'] ?? '' ),
            'url'       => esc_url_raw( $_POST['wp_book_url'] ?? '' ),
        ];

        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM $table WHERE post_id = %d", $post_id
        ) );

        if ( $existing ) {
            $wpdb->update(
                $table,
                $data,
                [ 'post_id' => $post_id ]
            );
        } else {
            $wpdb->insert(
                $table,
                array_merge( $data, [ 'post_id' => $post_id ] )
            );
        }
    }


    add_action( 'save_post', 'wp_book_save_meta_box_data' );

    register_activation_hook( __FILE__, 'wp_book_create_meta_table' );

    function wp_book_create_meta_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'book_meta';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT UNSIGNED NOT NULL,
            author VARCHAR(255),
            price DECIMAL(10,2),
            publisher VARCHAR(255),
            year INT,
            edition VARCHAR(100),
            url TEXT,
            PRIMARY KEY (id),
            UNIQUE KEY post_id (post_id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

