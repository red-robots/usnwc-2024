<?php
function get_race_posts() {
  $url = 'https://center.whitewater.org/wp-json/wp/v2/race?per_page=99';
  $response = wp_remote_get($url);
  if ( is_wp_error( $response ) ) {
    return false;
  }
  $posts = json_decode( wp_remote_retrieve_body( $response ), true );
  $race_posts = array();
  foreach ($posts as $post) {
    $race_post_terms = array();
    $terms = wp_get_post_terms( $post['id'], 'activity_location' );
    foreach ( $terms as $term ) {
      if ( $term->name === 'center' ) {
        $race_post_terms[] = $term->slug;
      }
    }
    if ( ! empty( $race_post_terms ) ) {
      $race_post = array(
        'title' => $post['title']['rendered'],
        'pagelink' => $post['link'],
        'start' => $post['acf']['start_date'],
        'end' => $post['acf']['end_date'],
        'hidePostfromMainPage' => $post['acf']['hidePostfromMainPage'],
        'event_date' => $event_date,
        'short_description' => $short_description,
        'eventStatus' => ( $post['acf']['eventstatus'] ) ? $post['acf']['eventstatus']:'upcoming',
        'thumbImage' => $post['acf']['thumbnail_image'],
        'terms' => $race_post_terms,
      );
      if (!empty($post['activity_type'])) {
        $terms = get_terms( array(
          'taxonomy' => 'activity_type',
          'include' => $post['activity_type'],
          'fields' => 'slugs',
        ) );
        $race_post['terms'] = $terms;
      }
      if( empty($post['acf']['hidePostfromMainPage'])) {
        $race_posts[] = $race_post;
      }
    }
  }
  return $race_posts;
}

$races = get_race_posts();



// ONE I  KNOW THAT WORKS
function get_race_posts() {
  $url = 'https://center.whitewater.org/wp-json/wp/v2/race?per_page=99';
  $response = wp_remote_get($url);
  if ( is_wp_error( $response ) ) {
    return false;
  }
  $posts = json_decode( wp_remote_retrieve_body( $response ), true );
  $race_posts = array();
  foreach ($posts as $post) {
    $race_post = array(
		'title' => $post['title']['rendered'],
		'pagelink' => $post['link'],
		'start' => $post['acf']['start_date'],
		'end' => $post['acf']['end_date'],
		'hidePostfromMainPage' => $post['acf']['hidePostfromMainPage'],
		'event_date' => $event_date,
		'short_description' => $short_description,
		'eventStatus' => ( $post['acf']['eventstatus'] ) ? $post['acf']['eventstatus']:'upcoming',
		'thumbImage' => $post['acf']['thumbnail_image'],
		'terms' => array(),
    );
    if (!empty($post['activity_type'])) {
      $terms = get_terms( array(
        'taxonomy' => 'activity_type',
        'include' => $post['activity_type'],
        'fields' => 'slugs',
      ) );
      $race_post['terms'] = $terms;
    }
    if( empty($post['acf']['hidePostfromMainPage'])) {
	    $race_posts[] = $race_post;
	}
 
  }
  return $race_posts;
}

$races = get_race_posts();


// Loc Terms doesn't work for some crazy reason.

function get_race_posts() {
  $url = 'https://center.whitewater.org/wp-json/wp/v2/race?per_page=99';
  $response = wp_remote_get($url);
  if ( is_wp_error( $response ) ) {
    return false;
  }
  $posts = json_decode( wp_remote_retrieve_body( $response ), true );
  $race_posts = array();
  foreach ($posts as $post) {
    $race_post = array(
		'title' => $post['title']['rendered'],
		'pagelink' => $post['link'],
		'start' => $post['acf']['start_date'],
		'end' => $post['acf']['end_date'],
		'hidePostfromMainPage' => $post['acf']['hidePostfromMainPage'],
		'event_date' => $event_date,
		'short_description' => $short_description,
		'eventStatus' => ( $post['acf']['eventstatus'] ) ? $post['acf']['eventstatus']:'upcoming',
		'thumbImage' => $post['acf']['thumbnail_image'],
		// 'tester' => $post['activity_location'],
		'terms' => array(),
		'loc_terms' => array(),
    );
    if (!empty($post['activity_type'])) {
      $terms = get_terms( array(
        'taxonomy' => 'activity_type',
        'include' => $post['activity_type'],
        'fields' => 'slugs',
      ) );
      $race_post['terms'] = $terms;
    }
    if (!empty($post['activity_location'])) {
      $locterms = get_terms( array(
        'taxonomy' => 'activity_location',
        'include' => $post['activity_location'],
        'fields' => 'slugs',
      ) );
      $race_post['loc_terms'] = $locterms;
    }
    if( empty($post['acf']['hidePostfromMainPage'])) {
	    $race_posts[] = $race_post;
	}
 
  }
  return $race_posts;
}

$races = get_race_posts();
