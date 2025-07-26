<?php

function wp_book_shortcode( $atts ) {
    global $wpdb;

    // Set default attributes
    $atts = shortcode_atts( [
        'author'    => '',
        'publisher' => '',
        'year'      => '',
    ], $atts );

    $table = $wpdb->prefix . 'book_meta';

    // Start query
    $query = "SELECT * FROM $table WHERE 1=1";
    $params = [];

    // Add filters only if attributes are provided
    if ( !empty( $atts['author'] ) ) {
        $query .= " AND author = %s";
        $params[] = $atts['author'];
    }
    if ( !empty( $atts['publisher'] ) ) {
        $query .= " AND publisher = %s";
        $params[] = $atts['publisher'];
    }
    if ( !empty( $atts['year'] ) ) {
        $query .= " AND year = %d";
        $params[] = intval( $atts['year'] );
    }

    // Prepare and run the query
    $books = $wpdb->get_results( $wpdb->prepare( $query, ...$params ) );

    // If no books found
    if ( empty( $books ) ) {
        return '<p>No books found.</p>';
    }

    // Output HTML
    $output = '<ul class="wp-book-list">';
    foreach ( $books as $book ) {
        $output .= '<li>';
        $output .= '<strong>' . esc_html( get_the_title( $book->post_id ) ) . '</strong><br>';
        $output .= 'Author: ' . esc_html( $book->author ) . '<br>';
        $output .= 'Publisher: ' . esc_html( $book->publisher ) . '<br>';
        $output .= 'Year: ' . esc_html( $book->year ) . '<br>';
        $output .= 'Price: ' . esc_html( $book->price ) . '<br>';
        $output .= 'URL: <a href="' . esc_url( $book->url ) . '" target="_blank">View</a>';
        $output .= '</li>';
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode( 'book', 'wp_book_shortcode' );


?>