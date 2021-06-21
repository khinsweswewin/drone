<?php
$page_name = get_the_title();
if ( 'ニュースルーム' == $page_name || is_front_page() ) {
	$count = 0;
	foreach ( $news_items as $news_item ) {
		if ( $count < 5 ) {
			foreach ( $news_item as $index => $value) {
				if ( $index == 'source' ) {
					?><div class="news-section-source"<h4><?php echo( $value ) ?></h4></div><?php
				} elseif ( $index == 'link' ) {
					?><a class="news-section-link" href='<?php echo esc_attr( $value ) ?>' target='_blank'><?php
				} elseif ( $index == 'title' ) {
					echo( $value ) ?></a><?php
				} elseif ( $index == 'pub_date' ) {
					?><div class="news-section-date"><?php echo( $value ) ?></div><?php
				}
			}
		}
		$count++;
	}
} else {
	?><div class="container"><div class="topnav" id="top-navigation"><ul class="tabs"><?php
		foreach ( $pub_years as $pub_year ) {
			?><li class="tab-link" data-tab="<?php echo $pub_year ?>"><?php echo $pub_year ?></li> <?php
		}
		?><a class="select-year">&#x25BC;</a></ul></div><?php
		foreach ( $pub_years as $pub_year ) {
			?><div id="<?php echo $pub_year ?>" class="tab-content"></div><?php
		}
		foreach ( $news_items as $news_item ) {
			?><div class="<?php echo $news_item[pub_year];?>" style="display: none;"><?php
				foreach ( $news_item as $index => $value) {
					if ( $index == 'source' ) {
						?><div class="news-section-source"<h4><?php echo( $value ) ?></h4></div><?php
					} elseif ( $index == 'link' ) {
						?><a class="news-section-link" href='<?php echo esc_attr( $value ) ?>' target='_blank'><?php
					} elseif ( $index == 'title' ) {
						echo( $value ) ?></a><?php
					} elseif ( $index == 'pub_date' ) {
						?><div class="news-section-date"><?php echo( $value ) ?></div><?php
					}
				}
			?></div><?php
		}
	?></div><?php
}
