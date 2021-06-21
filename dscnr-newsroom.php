<?php

/*
Plugin Name: DSC Newsroom 
Description: Newsroom Page plugin
Plugin URI: https://github.com/
Version: 1.0b
Text Domain: dscnr
Domain Path: /languages
Copyright (C) 2020 Digital Stacks Corp.
*/

define( 'DSCNR_NEWS_ROOM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DSCNR_NEWS_ROOM_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
define( 'DSCNR_GOOGLE_NEWS_FEED_URL', 'https://news.google.com/news?' );

/**
 * include functions
 */

require_once( 'inc/options-page.php' );
require_once( 'inc/news-section.php' );
require_once( 'inc/twitter.php' );
require_once( 'inc/cron.php' );
