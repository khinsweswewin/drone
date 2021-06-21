<?php

/**
 * Make Google News Feed URL
 *
 * @param string $query
 * @param string $query_lang
 * @return string
 */
function dscnr_get_google_news_feed_url( $query, $query_lang ) {
	switch ( $query_lang ) {
		case 'JP':
			$params = array(
				'cf' => 'all',
				'hl' => 'jp',
				'ned' => 'jp',
				'q' => $query,
				'tbm' => 'nws',
				'output' => 'rss',
			);
			break;
		default:
			$params = array(
				'cf' => 'all',
				'hl' => 'en',
				'ned' => 'us',
				'q' => $query,
				'tbm' => 'nws',
				'output' => 'rss',
			);
	}
	return DSCNR_GOOGLE_NEWS_FEED_URL . http_build_query( $params );
}

/**
 * Translate Google News title
 *
 * @param string $text_to_translate
 * @return string
 */
function dscnr_translate_title( $text_to_translate ) {
	$source_language = 'en';
	$target_language = 'ja';
	$res = file_get_contents( "https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $source_language . "&tl=" . $target_language . "&hl=hl&q=" . urlencode( $text_to_translate ) );
	$res = json_decode( $res );
	$translated_text = $res[0][0][0];
	return $translated_text;
}

/**
 * Get google news feed
 * @return array
 */
function dscnr_get_google_news() {
	$dscnr_search_keywords_eng = ( get_option( 'dscnr_search_keyword_eng' ) ) ? preg_split( '/[\n\r]+/', get_option( 'dscnr_search_keyword_eng' ) ) : null;
	$dscnr_search_keywords_jp = ( get_option( 'dscnr_search_keyword_eng' ) ) ? preg_split( '/[\n\r]+/', get_option( 'dscnr_search_keyword_jp' ) ) : null;
	$query_parameters = array_filter(array_merge( $dscnr_search_keywords_eng, $dscnr_search_keywords_jp ));
	$dscnr_jp_checked = get_option( 'translate_to_jp' );
	$competitors = ( get_option( 'dscnr_exclude_competitors' ) ) ? preg_split( '/[\n\r]+/', get_option( 'dscnr_exclude_competitors' ) ) : null;
	$exclude_domains = ( get_option( 'dscnr_exclude_domains' ) ) ? preg_split( '/[\n\r]+/', get_option( 'dscnr_exclude_domains' ) ) : null;
	$exclude_urls = ( get_option( 'dscnr_exclude_urls' ) ) ? preg_split( '/[\n\r]+/', get_option( 'dscnr_exclude_urls' ) ) : null;
	foreach ( $query_parameters as $query_parameter ) {
		if ( strlen( $query_parameter ) != mb_strlen( $query_parameter, 'utf-8' ) ) {
			$query_lang = 'JP';
		} else {
			$query_lang = 'Eng';
		}
		$url = dscnr_get_google_news_feed_url( $query_parameter, $query_lang );
		$feeds[] = fetch_feed( $url );
	}
	$links = array();
	foreach ( $feeds as $feed ) {
		foreach ( $feed->get_items() as $i => $item ) {
			$title_raw = $item->get_title();
			$date_raw = $item->get_date( '' );
			$link_raw = $item->get_permalink();
			$title_parts = explode( ' - ', $title_raw );
			$source = array_pop( $title_parts );
			if ( strpos( $source, '- ' ) === 0 ) {
				$source = substr( $source, 2 );
			}
			$title = htmlspecialchars_decode( implode( ' - ', $title_parts ), ENT_QUOTES );
			parse_str( htmlspecialchars_decode( $link_raw ), $link_param );
			$link = isset( $link_param['url'] ) ? $link_param['url'] : htmlspecialchars_decode( $link_raw );
			if ( in_array( $link, $links ) ) {
				continue;
			}
			$links[] = $link;
			$domain = str_ireplace( 'www.', '', parse_url( $link, PHP_URL_HOST ) );
			$pub_date = date( 'F d, Y', strtotime( $date_raw ) );
			foreach( $query_parameters as $query_parameter ) {
				if ( ! empty( $query_parameter ) ) {
					$is_keyword_contain = stripos( $title, $query_parameter );
				}
				if ( false !== $is_keyword_contain ) {
					$is_competitor = 'no';
					if ( ! empty( $competitors ) ) {
						foreach ( $competitors as $competitor ) {
							if ( $competitor && false !== strpos( $title, $competitor ) ) {
								$is_competitor = 'yes';
							}
						}
					}
					$is_exclude_domain = 'no';
					if ( ! empty( $exclude_domains ) ) {
						foreach ( $exclude_domains as $exclude_domain ) {
							if ( false !== strpos( $domain, $exclude_domain ) ) {
								$is_exclude_domain = 'yes';
							}
						}
					}
					$is_exclude_url = 'no';
					if ( ! empty( $exclude_urls ) ) {
						foreach ( $exclude_urls as $exclude_url ) {
							if ( $exclude_url && false !== strpos( $link, $exclude_url ) ) {
								$is_exclude_url = 'yes';
							}
						}
					}
					if ( $is_competitor == 'no' && $is_exclude_domain == 'no' && $is_exclude_url == 'no' ) {
						if ( $dscnr_jp_checked == '1' ) {
							$title = dscnr_translate_title( $title );
						}
						$pub_year = explode( ',', $pub_date );
						$pub_year = array_map( 'trim', $pub_year );
						$items[] = array(
							'index' => $i,
							'source' => $source,
							'link' => $link,
							'title' => $title,
							'pub_date' => $pub_date,
							'pub_year' => $pub_year[1]
							);
						$pub_years[] = $pub_year[1];
					}
					
				} 
			}	
		}
	}
	// Sort the array 
	usort( $items, 'date_compare'); 
	$pub_years = array_unique( $pub_years );
	return array( 'news_items' => $items, 'pub_years' => $pub_years );
}

/**
 * Date Compare
 * @return date
 */
function date_compare($element1, $element2) { 
    $datetime1 = strtotime($element1['pub_date']); 
    $datetime2 = strtotime($element2['pub_date']); 
    return $datetime2 - $datetime1; 
}  

/**
 * Get cache file path
 * @return string
 */
function dscnr_get_cache_file_path() {
	$upload_dir = wp_upload_dir();
	return $upload_dir['basedir'] . '/dscnr_tmp/news_section_data.txt';
}

/**
 * Save google news data in cache file
 * @return array
 */
function dscnr_save_google_news_data() {
	$cache_file_path = dscnr_get_cache_file_path();
	$cache_file_dir = dirname( $cache_file_path );
	if ( ! is_dir( $cache_file_dir ) ) {
		mkdir( $cache_file_dir );
	}
	$all_news_data = dscnr_get_google_news();
	file_put_contents( $cache_file_path, serialize( $all_news_data ) );
	return $all_news_data;
}

/**
 * Shortcode for google news feed
 */
function dscnr_google_news_feed() {
	$cache_file_path = dscnr_get_cache_file_path();
	if ( file_exists( $cache_file_path ) ) {
		$all_news_data = unserialize( file_get_contents( $cache_file_path ) );
	} else {
		$all_news_data = dscnr_save_google_news_data();
	}
	$news_items = $all_news_data['news_items'];
	$pub_years = $all_news_data['pub_years'];
	wp_enqueue_style( 'dscnr-google-news-feed', DSCNR_NEWS_ROOM_PLUGIN_URL . 'css/dscnr-newsroom.css' );
	wp_enqueue_script( 'dscnr-google-news-feed', DSCNR_NEWS_ROOM_PLUGIN_URL . 'js/dscnr-newsroom.js', array( 'jquery' ), null, true );
	ob_start();
	require( 'news-section-template.php' );
	return ob_get_clean();
}
add_shortcode( 'dscnr_google_news_feed', 'dscnr_google_news_feed' );
