<?php
// This function displays the page content for the My VideoTag Options submenu
function mvt_settings_page() {
?>
<div class="wrap" id="myvideotag">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo MVT_URLPATH;?>admin/_inc/js/mvtsites.js"></script>
<div class="icon32" id="icon-videotag"><br></div>
<h2>My-Videotag <?php echo __('Settings', 'my-videotag')?></h2>

<div class="updated">
<strong><p><?php echo __('From version 1.3.6 you can customize the size of each video,  Example: ', 'my-videotag')?> [video w="pixel size" h="pixel size"] LINK VIDEO [/video]</p></strong>
</div>

<div id="poststuff">
<div class="postbox">
<h3 class="hndle"><span><?php echo __('Are you satisfied with my work?', 'my-videotag')?></span></h3>
<div class="inside" style="line-height: 15px;">
<div style="width: 100%">
 
<div style="width: 49%; float: left;">
<?php echo __('Hi, i hope you enjoy my plugin. It took me a lot of hours to make. A lot of crackers and grape juice was spilled during the creation of this plugin. If you like it, you could help me by giving me a fresh coup of coffee.', 'my-videotag')?><br /><br />
				<?php
				if (function_exists('get_transient')) {
				  require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

				  // First, try to access the data, check the cache.
				  if (false === ($api = get_transient('mvt_info'))) {
					// The cache data doesn't exist or it's expired.

					$api = plugins_api('plugin_information', array('slug' => MVT_SLUG ));

					if ( !is_wp_error($api) ) {
					  // cache isn't up to date, write this fresh information to it now to avoid the query for xx time.
					  $myexpire = 60 * 15; // Cache data for 15 minutes
					  set_transient('mvt_info', $api, $myexpire);
					}
				  }
				  if ( !is_wp_error($api) ) {
					  $plugins_allowedtags = array('a' => array('href' => array(), 'title' => array(), 'target' => array()),
												'abbr' => array('title' => array()), 'acronym' => array('title' => array()),
												'code' => array(), 'pre' => array(), 'em' => array(), 'strong' => array(),
												'div' => array(), 'p' => array(), 'ul' => array(), 'ol' => array(), 'li' => array(),
												'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
												'img' => array('src' => array(), 'class' => array(), 'alt' => array()));
					  //Sanitize HTML
					  foreach ( (array)$api->sections as $section_name => $content )
						$api->sections[$section_name] = wp_kses($content, $plugins_allowedtags);
					  foreach ( array('version', 'author', 'requires', 'tested', 'homepage', 'downloaded', 'slug') as $key )
						$api->$key = wp_kses($api->$key, $plugins_allowedtags);

					  if ( ! empty($api->downloaded) ) {
						echo sprintf(__('Downloaded %s times', 'my-videotag'),number_format_i18n($api->downloaded));
						echo '.';
					  }
				?>
					  <?php if ( ! empty($api->rating) ) : ?>
<div class="mvt_star-holder" title="<?php echo esc_attr(sprintf(__('(Average rating based on %s ratings)', 'my-videotag'),number_format_i18n($api->num_ratings))); ?>">
<div class="mvt_star mvt_star-rating" style="width: <?php echo esc_attr($api->rating) ?>px"></div>				  
<div class="mvt_star mvt_star5"><img src="<?php echo MVT_URLPATH;?>images/star.png" alt="<?php printf(__('%d stars', 'my-videotag'),'5'); ?>" /></div>
<div class="mvt_star mvt_star4"><img src="<?php echo MVT_URLPATH;?>images/star.png" alt="<?php printf(__('%d stars', 'my-videotag'),'4'); ?>" /></div>
<div class="mvt_star mvt_star3"><img src="<?php echo MVT_URLPATH;?>images/star.png" alt="<?php printf(__('%d stars', 'my-videotag'),'3'); ?>" /></div>
<div class="mvt_star mvt_star2"><img src="<?php echo MVT_URLPATH;?>images/star.png" alt="<?php printf(__('%d stars', 'my-videotag'),'2'); ?>" /></div>
<div class="mvt_star mvt_star1"><img src="<?php echo MVT_URLPATH;?>images/star.png" alt="<?php printf(__('%d stars', 'my-videotag'),'1'); ?>" /></div>
</div>
<small><?php echo sprintf(__('(Average rating based on %s ratings)', 'my-videotag'),number_format_i18n($api->num_ratings)); ?> <a target="_blank" href="http://wordpress.org/extend/plugins/<?php echo $api->slug ?>/"> <?php _e('Rate This', 'my-videotag') ?></a></small>
					  <?php endif;
				}// end if (function_exists('get_transient'
				  } // if ( !is_wp_error($api)
				  
				?><br /><br />

<?php require_once(dirname (__FILE__) . '/includes/mvt-sites.php'); ?>

<div style="width: 49%; float: left; border-left: 1px solid #DFDFDF; padding-left: 10px;">
<?php echo __('My name is', 'my-videotag'). ' giObanii fiOri '. __('and I live in Mexico. Yep the country with the best tequila, but is expensive. You could help me out with that if you like my plugin. <br /><br />You can contact me by', 'my-videotag')?> <a target="_blank" href="mailto:3motronik@gmail.com">mail</a>, <a href="https://plus.google.com/u/0/109407223390697336601/" target="_blank">G+</a>, <a href="http://www.twitter.com/giObanii" target="_blank">Twitter</a>. <?php echo __('You can support me by adding', 'my-videotag')?> <a href="http://toxigeek.com" target="_blank"><?php echo __('my link', 'my-videotag') ?></a> <?php echo __('on your blogroll.', "my-videotag") ?><br /><br />
<a href="http://toxigeek.com/donate" target="_blank" >
<img src="<?php echo MVT_URLPATH;?>images/donate.gif" style=""/></a> 
</div>

<div style="clear: both"></div>
</div>
</div>
</div>
</div>

<div style="clear:both;"></div>

<div id="poststuff">
<div class="postbox" id="myvideotag-wrap">
<h3 class="hndle"><span style="font-size:17px;font-weight:bold;"><?php _e('Dimensions of the videos', 'my-videotag') ?></span><span style="float:right"><small><?php echo ''.get_version_mvt().''; ?></small></span></h3>
<div class="inside" style="display: block;">
 <div style="width: 100%">
 
<div style="width: 65%; float: left;">
<p><?php _e('Enter the default video size.', 'my-videotag') ?> <abbr style="border-bottom: 1px dotted black; color:red; font-weight:bold;" title="<?php _e('You can also use:', 'my-videotag') ?> [video w='640' h='390'] URL VIDEO [/video]">?</abbr></p> 

<form  method="post" action="options.php">
<?php
settings_fields('mvt-group');
$mvt = get_option('mvt_options');
?>
<table id="mvtparametros" class="form-table">
<tr valign="top">
<th scope="row" style="font-family: 'Myriad Pro',Arial,Helvetica,sans-serif !important;font-size: 20px;width: 120px;"><label for="mvt_options[width]" style="font-weight: bold;"><?php _e('Width', 'my-videotag') ?></label></th> 
<td><input type="text" name="mvt_options[width]" class="small-text" style="background:#FFFFFF;border: 1px solid #DDDDDD;font-family: 'Myriad Pro',Arial,Helvetica,sans-serif;font-size: 20px;padding: 4px;" value="<?php echo $mvt['width']; ?>" />&nbsp;px</td>
</tr>
<tr valign="top">
<th scope="row" style="font-family: 'Myriad Pro',Arial,Helvetica,sans-serif !important;font-size: 20px;width: 120px;"><label for="mvt_options[height]" style="font-weight: bold;"><?php _e('Height', 'my-videotag') ?></label></th> 
<td><input type="text" name="mvt_options[height]" class="small-text" style="background:#FFFFFF;border: 1px solid #DDDDDD;font-family: 'Myriad Pro',Arial,Helvetica,sans-serif;font-size: 20px;padding: 4px;" value="<?php echo $mvt['height']; ?>" />&nbsp;px</td>
</tr>
<tr valign="top">
<th scope="row" style="font-family: 'Myriad Pro',Arial,Helvetica,sans-serif !important;font-size: 20px;width: 120px;"><label for="mvt_options[width]" style="font-weight: bold;"><?php _e('Style (CSS)', 'my-videotag') ?></label></th> 
<td>
<textarea rows="2" cols="20" name="mvt_options[style]" class="large-text" style="background:#FFFFFF;border: 1px solid #DDDDDD;font-family: 'Myriad Pro',Arial,Helvetica,sans-serif;font-size: 17px;padding: 4px;" ><?php echo $mvt['style']; ?></textarea></td>
</tr>
</table>

<table class="form-table">
<tr valign="top">
<th scope="row" style="font-family: 'Myriad Pro',Arial,Helvetica,sans-serif !important;font-size: 20px;width: 15px;"><input name="mvt_options[comments]" type="checkbox" value="1" <?php if (isset($mvt['comments']) == "1") echo 'checked="checked"'; ?> /></th>
<td style="font-family: 'Myriad Pro',Arial,Helvetica,sans-serif !important;font-size: 20px;"><label for="mvt_options[comments]" style="font-weight: bold;"><?php _e('Enable/Disable display videos in comments', 'my-videotag') ?> <abbr style="border-bottom: 1px dotted black; color:red; font-weight:bold;" title="<?php _e('If you have problems with plugins Disqus Comment System or Intense Debate do not enable this option.', 'my-videotag') ?>">?</abbr></label>
</td>
</tr>         
</table>

<p class="submit">
<input type="submit" class="button" style="font-size: 14px !important;padding:10px;" value="<?php _e('Save Changes', 'my-videotag') ?>" />
</p>  

</form>                   
</div>

<?php require_once(dirname (__FILE__) . '/includes/mvt-share.php');  ?>

<div style="clear: both"></div>
</div>
</div>
</div></div>

<div style="clear:both;"></div>

<p>
<table class="widefat">
<thead>
<tr>
<th>My Videotag</th>
<th><span style="float:right"><small><?php echo ''.get_version_mvt().''; ?></small></span></th>
</tr>
</thead>
<tr class="alternate">
<td>Plugin Name:</td>
<td>My Videotag</td>
</tr>
<tr class="alternate">
<td>Plugin Version:</td>
<td><?php echo ''.get_version_mvt().''; ?></td>
</tr>
<tr class="alternate">
<td>Author:</td>
<td><a href="http://nosoynormal.net" target="_blank">giObanii fiOri</a></td>
</tr>
<tr class="alternate">
<td>Release Date:</td>
<td>23/10/2010</td>
</tr>
<tr class="alternate">
<td>FAQ:</td>
<td><a href="http://wordpress.org/extend/plugins/my-videotag/faq/" target="_blank">FAQ Page</a></td>
</tr>
<tr class="alternate">
<td>Donations:</td>
<td><a href="http://toxigeek.com/donate/" target="_blank">Donations Page</a></td>
</tr>
<tr class="alternate">
<td>Support Forums:</td>
<td><a href="http://wordpress.org/tags/my-videotag?forum_id=10" target="_blank">Support Plugin</a></td>
</tr>
<tr class="alternate">
<td>Wordpress plugin site:</td>
<td><a href="http://wordpress.org/extend/plugins/my-videotag/" target="_blank">Wordpress Page</a></td>
</tr>
<tr class="alternate">
<td colspan="2">
<span class="button" style="float:left"><a href="http://toxigeek.com" target="_blank">#toxiGeek</a></span>
<span class="button" style="float:right"><a href="http://twitter.com/giObanii" target="_blank">giObanii fiOri</a></span>
</td>
</tr>				
</table>
</p>

</div> <!--end of float wrap -->
<?php	
}
?>