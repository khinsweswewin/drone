<?php

/**
 * ShortCode for twitter section content
 */
function dscnr_get_tweets() {
// Set the Twitter account to get latest tweets
$screen_name = get_option( 'dscnr_twitter_user_name' );

// Get timeline
$url = 'https://twitter.com/'. $screen_name;
$text = 'Tweets by @'. $screen_name;

ob_start();
?>
<div>
	<a class="twitter-timeline" href="<?php echo $url ?>" data-height="468" data-chrome="noheader noborders nofooter" data-theme="light"><?php echo $text ?></a> 
	<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>
<?php
return ob_get_clean();	
}
add_shortcode( 'dscnr_get_tweets', 'dscnr_get_tweets' );
