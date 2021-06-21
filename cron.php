<?php
/**
 * Unschedule event upon plugin deactivation
 */
function dscnr_cronevent_deactivate() {
	$timestamp = wp_next_scheduled( 'dscnr_update_google_news' );
	wp_unschedule_event( $timestamp, 'dscnr_update_google_news' );
} 
register_deactivation_hook( __FILE__, 'dscnr_cronevent_deactivate' );

/**
 * Create a scheduled event (if it does not exist already)
 */
function dscnr_cronevent_activation() {
	if ( ! wp_next_scheduled( 'dscnr_update_google_news' ) ) {
		wp_schedule_event( time(), 'daily', 'dscnr_update_google_news' );
	}
}
add_action( 'wp', 'dscnr_cronevent_activation' );

/**
 * Hook that function onto scheduled event:
 */
add_action( 'dscnr_update_google_news', 'dscnr_save_google_news_data' ); 
