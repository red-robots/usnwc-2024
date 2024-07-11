<?php
function get_wordpress_posts() {
  $url = 'https://center.whitewater.org/wp-json/wp/v2/race?per_page=99'; // Replace "example.com" with your own website URL
  $args = array(
    'headers' => array(
      'Accept' => 'application/json'
    )
  );
  
  $response = wp_remote_get( $url, $args ); // Make the API request using the WP HTTP API
  
  if ( is_wp_error( $response ) ) { // Check for errors
    return false;
  } else {
    $posts = json_decode( wp_remote_retrieve_body( $response ), true ); // Decode the JSON response into an array
    
    // Loop through the posts and get the terms for each one
    foreach ( $posts as &$post ) {
      $terms_url = 'https://example.com/wp-json/wp/v2/posts/' . $post['id'] . '/activity_type'; // Replace "example.com" with your own website URL
      
      $terms_response = wp_remote_get( $terms_url, $args ); // Make the API request for the terms
      
      if ( ! is_wp_error( $terms_response ) ) { // Check for errors
        $terms = json_decode( wp_remote_retrieve_body( $terms_response ), true ); // Decode the JSON response into an array
        $post['activity_type_terms'] = $terms; // Add the terms to the post array
      }
    }
    
    return $posts;
  }
}


$posts = get_wordpress_posts();

if ( $posts ) {
  foreach ( $posts as $post ) {
    echo '<h2>' . $post['title']['rendered'] . '</h2>'; // Display the post title
    echo '<p>' . $post['excerpt']['rendered'] . '</p>'; // Display the post excerpt
    
    if ( isset( $post['activity_type_terms'] ) ) { // Check if terms are available
      echo '<ul>';
      foreach ( $post['activity_type_terms'] as $term ) {
        echo '<li>' . $term['name'] . '</li>'; // Display the term name
      }
      echo '</ul>';
    }
  }
} else {
  echo 'Error retrieving posts.';
}
