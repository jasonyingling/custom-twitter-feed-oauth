# custom-twitter-feed-oauth
Add a custom Twitter feed to WordPress with reply, retweet, and like buttons. Also replaces mentions, hashtags, and urls with actual links.

## Getting Started
1. Move all files into theme directory (ex. wp-content/themes/theme-name)
2. Create an app using your Twitter account at https://apps.twitter.com to get your API Keys
3. Include custom-twitter-functions.php in functions.php with `require_once( "custom-twitter-functions.php" );`
4. Use `get_template_part('twitter-feed');` in your theme wherever you want to insert the Twitter feed
