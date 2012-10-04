=== ZigConnect ===
Contributors: ZigPress
Donate link: http://www.zigpress.com/wordpress/plugins/zigconnect/
Tags: post links, posts to posts, post to post, post connection, connect posts, link posts, custom post types, zigpress, zig
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 0.9

Allows you to link post types and posts to each other and attach data directly to the links.

== Description ==

Allows you to link post types (including custom post types) and posts to each other and attach data directly to the links. ZigConnect was inspired by the plugin "Posts 2 Posts" but has been written from scratch.

Minimum versions required: WordPress 3.1, PHP 5.2.4 and MySQL 5.0.15.

For further information and support, please visit [the ZigConnect home page](http://www.zigpress.com/wordpress/plugins/zigconnect/).

ZigConnect uses icons from the [Silk icon set](http://www.famfamfam.com/lab/icons/silk/) and the [Tango Desktop Project](http://tango.freedesktop.org/).

THIS IS THE FINAL PUBLIC RELEASE OF ZIGCONNECT. Anyone interested in forking this plugin and continuing its development under a new name is more than welcome to do so.

== Installation ==

1. Unzip the installer and upload the resulting 'zigconnect' folder to the `/wp-content/plugins/` directory. Alternatively, go to Admin > Plugins > Add New and enter ZigConnect in the search box.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the plugin's main admin page and start creating connections between content types.
4. Edit a post of a connected type and look for the ZigConnect panels below the main content edit panel.

If updating manually, always deactivate before uploading and then reactivate. This will trigger any required database structure updates.

== Frequently Asked Questions ==

= How do I use the template tags? =

Try the following example. It should be inserted into single.php, inside the loop. It will show a list of posts of type 'my_type' which are linked to the current post via a connection.

	$connectedposts = zc_get_linked_posts('my_type');
	if ($connectedposts)
		{
		echo '<ul>';
		foreach ($connectedposts as $otherpostid)
			{
			$otherpost = get_post($otherpostid);
			echo '<li>';
			echo '<a href="' . get_bloginfo('url') . '/my_type/' . $otherpost->post_name . '">' . $otherpost->post_title . '</a>';
			echo '</li>';
			}
		echo '</ul>';
		}

If you have a connection between types that carries one or more data fields, you can also show the content of these data fields for each link in the list, like this enlarged example.

	$connectedposts = zc_get_linked_posts('my_type');
	if ($connectedposts)
		{
		echo '<ul>';
		foreach ($connectedposts as $otherpostid)
			{
			$otherpost = get_post($otherpostid);
			$linkdata = zc_get_linkdata($otherpostid);
			echo '<li>';
			echo '<a href="' . get_bloginfo('url') . '/my_type/' . $otherpost->post_name . '">' . $otherpost->post_title . '</a>';
			if ($linkdata)
				{
				echo ' ( ';
				foreach ($linkdata as $linkdatakey=>$linkdatavalue)
					{
					echo $linkdatakey . '=' . $linkdatavalue . ' ';
					}
				echo ' ) ';
				}
			echo '</li>';
			}
		echo '</ul>';
		}

If you have more than one connection between the same two content types, you can use the new template tags zc_get_connection_by_name or zc_get_connection_by_slug to get the ID of the right connection. You can then pass this ID as a second parameter to the zc_get_linked_posts template tag.

= Is ZigConnect available in my language? =

Not yet (unless your language is English of course!) but it is ready for localization, so would-be translators are encouraged to submit .po and .mo files for their language, in return for a thankyou and backlink here.

= I have a question not shown here! =

For further information and support, please visit [the ZigConnect home page](http://www.zigpress.com/wordpress/plugins/zigconnect/).

== Screenshots ==

1. The main admin page where connections between post types are defined.

== Changelog ==

= 0.9 =
* Final public release, verified compatibility with WordPress 3.3.x.
= 0.8.6 =
* Fixed a path bug which caused problems when the WordPress root is not the site root
* Plugin no longer requires allow_url_fopen to be enabled on the server
= 0.8.5 =
* Fixed a bug where the last checked checkbox could not be unchecked when editing the fields connecting 2 posts
= 0.8.4 =
* Custom field inputs via AJAX selection now match custom field inputs on page load
= 0.8.3 =
* Now allows checkboxes as well as text for custom field inputs
= 0.8.2 =
* Further improved layout of custom field inputs by adding an option to decide how many fields shown per line
= 0.8.1 =
* Added extra template tag
* Allow control of the order in which custom field inputs appear
* Added classes to the custom field inputs to allow JS events to be bound to them (e.g. datepicker)
* Improved layout of custom field inputs to allow more fields to be added before the metabox layout starts breaking
= 0.8 =
* Rewrote database table maintenance code
* Added facility to specify size of link data entry fields
* Updated WordPress minimum version requirement to 3.1
= 0.7 =
* Updated PHP and MySQL version requirements in preparation for the release of WordPress 3.2
* Added an "Add All" button to the connected posts panels shown when editing a post/page (this has been added mainly for ZigPress's own benefit and is NOT designed for use on sites with hundreds of posts of each connected content type - you have been warned!)
= 0.6.1 =
* Verified compatibility with WordPress 3.1.1
= 0.6 =
* Substantial code restructuring for easier maintenance
* Implemented distinct connection names and slugs for better admin usability
* Added more template tags
* Removed undocumented functionality that was added in 0.4 (no longer required by plugin author and never offered to plugin users)
= 0.5 =
* Implemented multiple (named) connections between the same post types, so that different fields can be attached to each connection
= 0.4 =
* Added some template tag usage examples
* Improved template tag code
* Merged in various functionality from an unreleased sub-plugin (this will be enhanced and documented later)
* Completely rebuilt admin form callback system to fix various 'headers already sent' warnings on some installations
* Restricted contextual help to ZigConnect pages only
* Some code refactoring
* Made ready for localization
= 0.3.1 =
* Custom admin columns in admin post editing pages now show connection totals only to avoid rows getting too high
* Fixed bug that may cause connected types to be duplicated in arrays when queried
* Fixed fatal error on activation
* Fixed warning on post update when no fields defined for connections
= 0.3 =
* Added AJAX post search to post edit panels
= 0.2 =
* Substantial code refactoring
* Widget code (under development) removed until I think of a useful widget to add
* Improved admin message system to avoid duplicate database actions
* Added more relevant admin icon
* Tidied readme file and added icon usage credits
* Removed unused icons from images folder
= 0.1 =
* First public release

