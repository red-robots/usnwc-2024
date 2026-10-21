<?php
/**
 * Purge the SpinupWP page cache just after midnight (site time).
 *
 * The server caches pages for a full day, so date-driven content went stale for logged-out
 * visitors: the calendar's "View Activity Schedule" buttons (today + 3 days) and the
 * "Today's Snapshot" date and hours. Editing posts already purges the whole cache (the
 * SpinupWP plugin does that on every publish/update), so only the date rollover needs help.
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
 * Purge the page cache, then queue tomorrow night's run.
 */
function bellaworks_run_nightly_cache_purge() {
	if ( function_exists( 'spinupwp_purge_site' ) ) {
		spinupwp_purge_site();
	}
	wp_schedule_single_event( bellaworks_next_cache_purge_time(), 'bellaworks_nightly_cache_purge' );
}
add_action( 'bellaworks_nightly_cache_purge', 'bellaworks_run_nightly_cache_purge' );
