<?php

/**
 * Twitter Integration Library
 */
require_once( "twitteroauth/twitteroauth.php" );

/**
 * Enqueue scripts and styles
 */
function custom_twitter_scripts() {

	wp_enqueue_script( 'twitter-web-intents', '//platform.twitter.com/widgets.js', array(), '', true );

}
add_action( 'wp_enqueue_scripts', 'custom_twitter_scripts' );


function qumulo_link_tweets( $tweet_text ) {
	// change all the urls to links
	$tweet_text = preg_replace( '~(\s|^)(https?://.+?)(\s|$)~im', '$1<a href="$2" target="_blank">$2</a>$3', $tweet_text );
    // @usernames
    $tweet_text = preg_replace ("/@(\\w+)/", "<a href=\"http://twitter.com/$1\" target=\"_blank\">@$1</a>", $tweet_text);
    // #hashtags too
    $tweet_text = preg_replace ("/#(\\w+)/", "<a href=\"http://twitter.com/search?q=$1\" target=\"_blank\">#$1</a>", $tweet_text);

	return $tweet_text;
}

/**
 * Get Tweets for specific user
 *
 * @param string 	$username 	the username account to get data from
 * @param integer   $count		number of tweets to return
 * @return array				Array of tweets or false if no results
 */
function get_tweets( $username, $count ) {
    // Enter API Keys from Twitter APP https://apps.twitter.com
	$consumerkey 		= 'CONSUMER_KEY';
	$consumersecret 	= 'CONSUMER_SECRET';
	$accesstoken 		= 'ACCESS_TOKEN';
	$accesstokensecret 	= 'ACCESS_TOKEN_SECRET';

	$connection = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$count;
	$tweets = $connection->get($url);

	if ( isset( $tweets->errors ) ) {
		return false;
	} else return $tweets;
}

/**
 * Convert time to a relative text string
 *
 * @param string 	$ts			The time the tweet was posted
 * @return string				Returns a string or date if more than a month ago
 */
function time2str($ts) {
    if(!ctype_digit($ts)) {
        $ts = strtotime($ts);
    }
    $diff = time() - $ts;
    if($diff == 0) {
		return 'now';
    } elseif($diff > 0) {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) { return '1 day ago'; }
        if($day_diff < 7) { return $day_diff . ' days ago'; }
        if($day_diff < 31) { return ceil($day_diff / 7) . ' weeks ago'; }
        if($day_diff < 60) { return '1 month ago'; }
        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 120) { return 'in a minute'; }
            if($diff < 3600) { return 'in ' . floor($diff / 60) . ' minutes'; }
            if($diff < 7200) { return 'in an hour'; }
            if($diff < 86400) { return 'in ' . floor($diff / 3600) . ' hours'; }
        }
        if($day_diff == 1) { return 'Tomorrow'; }
        if($day_diff < 4) { return date('l', $ts); }
        if($day_diff < 7 + (7 - date('w'))) { return 'next week'; }
        if(ceil($day_diff / 7) < 4) { return 'in ' . ceil($day_diff / 7) . ' weeks'; }
        if(date('n', $ts) == date('n') + 1) { return 'next month'; }
        return date('F Y', $ts);
    }
}

?>
