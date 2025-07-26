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
        global $wpdb;
        wp_nonce_field( 'wp_book_save_meta_box', 'wp_book_meta_box_nonce' );

        $table = $wpdb->prefix . 'book_meta';

        $meta = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table WHERE post_id = %d", $post->ID),
            ARRAY_A
        );

        $author    = $meta['author'] ?? '';
        $price     = $meta['price'] ?? '';
        $publisher = $meta['publisher'] ?? '';
        $year      = $meta['year'] ?? '';
        $edition   = $meta['edition'] ?? '';
        $url       = $meta['url'] ?? '';
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

    function wp_book_add_settings_submenu() {
        add_submenu_page(
            'edit.php?post_type=book',              // Parent = Books menu
            __( 'Book Settings', 'wp-book' ),       // Page title
            __( 'Settings', 'wp-book' ),            // Menu title
            'manage_options',                       // Capability
            'wp-book-settings',                     // Menu slug
            'wp_book_render_settings_page'          // Callback
        );
    }
    add_action( 'admin_menu', 'wp_book_add_settings_submenu' );

    function wp_book_register_settings() {
        register_setting(
            'wp_book_settings_group',               // Option group (used in settings_fields)
            'wp_book_settings',                     // Option name (stored in wp_options)
            'wp_book_sanitize_settings'             // Sanitize callback
        );

        add_settings_section(
            'wp_book_general_section',
            __( 'General Settings', 'wp-book' ),
            '__return_false',
            'wp_book_settings_page'
        );

        add_settings_field(
            'wp_book_currency',
            __( 'Currency Symbol', 'wp-book' ),
            'wp_book_field_currency_cb',
            'wp_book_settings_page',
            'wp_book_general_section'
        );

        add_settings_field(
            'wp_book_books_per_page',
            __( 'Books Per Page', 'wp-book' ),
            'wp_book_field_books_per_page_cb',
            'wp_book_settings_page',
            'wp_book_general_section'
        );
    }
    add_action( 'admin_init', 'wp_book_register_settings' );

    function wp_book_sanitize_settings( $input ) {
        $output = array();

        $output['currency']        = isset( $input['currency'] ) ? sanitize_text_field( $input['currency'] ) : '';
        $output['books_per_page']  = isset( $input['books_per_page'] ) ? absint( $input['books_per_page'] ) : 10;

        if ( $output['books_per_page'] <= 0 ) {
            $output['books_per_page'] = 10;
        }

        return $output;
    }

    function wp_book_render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $options = get_option( 'wp_book_settings', array(
            'currency'       => '₹',
            'books_per_page' => 10,
        ) );
        ?>
        <div class="wrap">
            <h1><?php _e( 'Book Settings', 'wp-book' ); ?></h1>

            <form method="post" action="options.php">
                <?php
                    settings_fields( 'wp_book_settings_group' );   // nonce + option group
                    do_settings_sections( 'wp_book_settings_page' ); // our fields
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }


    function wp_book_field_currency_cb() {
        $options  = get_option( 'wp_book_settings', array() );
        $currency = isset( $options['currency'] ) ? $options['currency'] : '₹';
        ?>
        <input type="text" name="wp_book_settings[currency]" value="<?php echo esc_attr( $currency ); ?>" class="regular-text" placeholder="₹, $, €, ...">
        <p class="description"><?php _e( 'Symbol used to display book prices.', 'wp-book' ); ?></p>
        <?php
    }

    function wp_book_field_books_per_page_cb() {
        $options        = get_option( 'wp_book_settings', array() );
        $books_per_page = isset( $options['books_per_page'] ) ? absint( $options['books_per_page'] ) : 10;
        ?>
        <input type="number" min="1" name="wp_book_settings[books_per_page]" value="<?php echo esc_attr( $books_per_page ); ?>" class="small-text">
        <p class="description"><?php _e( 'How many books to show per page (shortcode, archives, etc.).', 'wp-book' ); ?></p>
        <?php
    }

    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-book-shortcode.php';

    function wp_book_register_block() {
        // Register the block editor script
        wp_register_script(
            'wp-book-block',
            plugins_url( 'blocks/book-block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'wp-components', 'wp-block-editor' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'blocks/book-block.js' ),
            true
        );

        // Register the block type
        register_block_type( 'wp-book/book-block', array(
            'editor_script' => 'wp-book-block',
            'render_callback' => 'wp_book_render_block',
            'attributes' => array(
                'author' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )
        ) );
    }
    add_action( 'init', 'wp_book_register_block' ); 

    function wp_book_render_block($attributes) {
        $atts = shortcode_atts(
            array(
                'author' => '',
            ),
            $attributes
        );

        if ( function_exists( 'wp_book_shortcode' ) ) {
            return wp_book_shortcode( $atts );
        }

        return '<p>No books found.</p>';
    }

    add_action('wp_dashboard_setup', 'wp_book_register_dashboard_widget');

    function wp_book_register_dashboard_widget() {
        wp_add_dashboard_widget(
            'wp_book_top_categories',
            'Top 5 Book Categories',
            'wp_book_display_top_categories_widget'
        );
    }

    function wp_book_display_top_categories_widget() {
        $terms = get_terms(array(
            'taxonomy'   => 'book_category',
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 5,
            'hide_empty' => false,
        ));

        if (empty($terms) || is_wp_error($terms)) {
            echo '<p>No categories found.</p>';
            return;
        }

        echo '<ul>';
        foreach ($terms as $term) {
            echo '<li>' . esc_html($term->name) . ' (' . intval($term->count) . ' books)</li>';
        }
        echo '</ul>';
    }

