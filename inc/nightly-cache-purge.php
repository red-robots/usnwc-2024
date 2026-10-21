<?php
/**
 * Purge the SpinupWP page cache and The Events Calendar's month view cache just after
 * midnight (site time).
 *
 * The server caches pages for a full day, so date-driven content went stale for logged-out
 * visitors: the calendar's "View Activity Schedule" buttons and the "Today's Snapshot" date
 * and hours. Editing posts already purges the whole page cache (the SpinupWP plugin does
 * that on every publish/update), so only the date rollover needs help.
 *
 * TEC also caches the month view HTML for a day, keyed without the date, and only expires it
 * when an event or TEC option changes. The theme's calendar templates add the activity
 * schedule buttons, so that cache also has to expire at midnight and when a schedule changes.
 *
 * Runs as a single WP-Cron event that re-schedules itself for the next local midnight,
 * so it stays at 12:01am across daylight-saving changes.
 *
 * @package bellaworks
 */

/**
 * Timestamp of the next 12:01am in the site's timezone.
 *
 * @return int
 */
function bellaworks_next_cache_purge_time() {
	$next = new DateTime( 'tomorrow 00:01', wp_timezone() );
	return $next->getTimestamp();
}

/**
 * Expire The Events Calendar's cached view HTML (month view etc.).
 *
 * Bumping the save_post trigger changes the cache keys, so old copies are ignored and expire.
 */
function bellaworks_expire_calendar_view_cache() {
	if ( class_exists( 'Tribe__Cache_Listener' ) && function_exists( 'tribe' ) ) {
		tribe( 'cache' )->set_last_occurrence( Tribe__Cache_Listener::TRIGGER_SAVE_POST );
	}
}

/**
 * Activity schedules drive the calendar's buttons but aren't TEC posts, so TEC doesn't
 * expire its cache when they change.
 */
function bellaworks_expire_calendar_cache_on_schedule_change( $new_status, $old_status, $post ) {
	if ( 'activity_schedule' === $post->post_type && ( 'publish' === $new_status || 'publish' === $old_status ) ) {
		bellaworks_expire_calendar_view_cache();
	}
}
add_action( 'transition_post_status', 'bellaworks_expire_calendar_cache_on_schedule_change', 10, 3 );

/**
 * One-time expiry after a deploy that changes what the calendar templates output.
 * Bump the version to expire it again.
 */
function bellaworks_expire_calendar_cache_after_deploy() {
	$version = '2026-10-21';
	if ( get_option( 'bellaworks_calendar_cache_version' ) !== $version ) {
		bellaworks_expire_calendar_view_cache();
		update_option( 'bellaworks_calendar_cache_version', $version );
	}
}
add_action( 'init', 'bellaworks_expire_calendar_cache_after_deploy' );

/**
 * Make sure the nightly purge is scheduled (only where SpinupWP is installed).
 */
function bellaworks_schedule_nightly_cache_purge() {
	if ( ! function_exists( 'spinupwp_purge_site' ) ) {
		return;
	}
	if ( ! wp_next_scheduled( 'bellaworks_nightly_cache_purge' ) ) {
		wp_schedule_single_event( bellaworks_next_cache_purge_time(), 'bellaworks_nightly_cache_purge' );
	}
}
add_action( 'init', 'bellaworks_schedule_nightly_cache_purge' );

/**
 * Purge the calendar view cache and the page cache, then queue tomorrow night's run.
 */
function bellaworks_run_nightly_cache_purge() {
	bellaworks_expire_calendar_view_cache();
	if ( function_exists( 'spinupwp_purge_site' ) ) {
		spinupwp_purge_site();
	}
	wp_schedule_single_event( bellaworks_next_cache_purge_time(), 'bellaworks_nightly_cache_purge' );
}
add_action( 'bellaworks_nightly_cache_purge', 'bellaworks_run_nightly_cache_purge' );
