=== AjaxChat ===
Contributors: yoyojamfl
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=GMXNW6GGYR5LG&lc=US&item_name=Payden%20Sutherland&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: chat, ajax, instant, messaging
Requires at least: 3.2.1
Tested up to: 3.4
Stable Tag: 0.5.3

This plug-in provides a simple chat system integrated into your blog.

== Description ==

Provides a simple chat system integrated into your blog.  It adds a bar with a height of 20 pixels
across the bottom of the browser's viewport.  The div for the bar is position:fixed.  This means that older browsers,
Internet Explorer 6, for instance, may not display the bar at all.  I may create a work-around for IE6 in the future.
This plug-in is in the early stages of development (as of 2010-06-01).  Any ideas/suggestions/bugs can be submitted to me
via http://paydensutherland.com.

== Installation ==


1. Upload `ajaxchat` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= The bottom bar doesn't show up at all! =

I've run into this problem with people using themes that don't properly call wp_footer(); in their footer.php
Please ensure you have this line in footer.php in your wp-content/themes/whatever/footer.php file:
`<?php wp_footer(); ?>`

= The bottom bar shows up, but I click on the Chat button and nothing happens. =

This is usually some sort of JavaScript error, ensure your theme has `<?php wp_head(); ?>` in it's wp-content/themes/whatever/header.php` file.

= Is Internet Explorer 6 Supported? =

No.

= Such & such doesn't work =

Email me! payden@paydensutherland.com

= It complains that it can't find wp-config.php =

AjaxChat searches for your wp-config.php by looking three directories up.  So, if you put AjaxChat in /var/www/wp/wp-content/plugins/ajaxchat, it will look in /var/www/wp for wp-config.php.  If it's not finding it try to put ajaxchat under wp-content/plugins/ or change any references to wp-config.php by hand.

== Screenshots ==

1. A view of the integrated chat bar with chat window closed.
2. And with chat window open.
3. Version 0.5.0
4. Chat box "popped out"

== Changelog ==

= 0.5.3 =
* Bugfix: Don't assume default timezone.  That's rude.

= 0.5.2 =
* Bugfix: check for weird issue where ajaxchat_ping.php returns "0"

= 0.5.1 =
* Bugfix: remove any assumptions about short_open_tags

= 0.5.0 =
* Bugfix: broken reference to myName variable.

= 0.4.9 =
* Bugfix: lacking brace in JS file anonymous function.

= 0.4.8 =
* Change silly aliases for jQuery.  Map jQuery to $ inside anonymous function.

= 0.4.7 =
* Refactored javascript mess.  It's still pretty messy but I've started cleaning it up.  A lot of this old code is ready for a refactor.

= 0.4.6 =
* Updated to use display name when logged in rather than username as per user suggestion.

= 0.4.5 =
* Added 'pop out' feature to position the chat window anywhere in your viewport.
* Added position tracking so the window stays in the same place even upon new page load.

= 0.4.4 =
* Minor change to fix bottom offset of ac_window when ajaxIM div is larger than expected.
* Change z-index of ac_window to overcome greedy images and such.

= 0.4.3 =
* Couple of minor changes to make it work with the latest version.

= 0.4.2 =
* Previous bugfix for headers already sent did not work, this one should.
* Suppress errors when sending headers, this was causing js/css to be parsed incorrectly.
* Output buffering to send the headers at the appropriate time, IE: the beginning. ;)

= 0.4.1 =
* Bugfix for 'headers already sent' error.
* Reintroduced online count

= 0.4 =
* Did away with wordpress database calls in ajaxchat_ping.php, improved db performance.
* Cut down on tx/rx
* Lots of little efficiency/speed improvements improvements

= 0.3.3 =
* Fixed CSS issue with default theme for WP 3.0

= 0.3.2 =
* Fixed foreign character issues.
* Use POST when sending messages instead of GET

= 0.3.1 =
* Minor bug fixes
* Added options for setting AjaxChat colors

= 0.3 =
* Added a blinking bar notification when the chat window is closed and new messages are received.
* Remember window state when moving around blog, ie (open/closed)
* More efficient use of bandwidth.  Only fetch new messages, not all messages every second.

= 0.2 =
* Changed versioning scheme, apparently wordpress didn't like the previous one.
* Couple of bug fixes.  Going to try to keep new features in trunk/ and keep the releases stable.

= 0.12 =
* Sanitize user input for SQL statements using wpdb::prepare
* Do error reporting on failed name change
* If user is logged into wordpress, use their display name as their nickname

= 0.11 =
* Added <?php tags instead of short_tags '<?' for people with out short_open_tags=on
* Added compatibility for wordpress installations that are not in the root of the domain.

= 0.10 =
* First version, just beginning so take it easy on me :)
