<?php
/*
Plugin Name: ZigConnect
Plugin URI: http://www.zigpress.com/wordpress/plugins/zigconnect/
Version: 0.9
Requires at least: 3.1
Tested up to: 3.2.1
Description: Allows you to link post types and posts to each other and attach data directly to the links.
Author: ZigPress
Author URI: http://www.zigpress.com/
License: GPLv2
*/


/*
Copyright (c) 2011-2012 ZigPress

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation Inc, 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/


/*
ZigPress PHP code uses Whitesmiths indent style: http://en.wikipedia.org/wiki/Indent_style#Whitesmiths_style
*/


# INCLUDE RELATED FILES


require_once DIRNAME(__FILE__) . '/zigconnect-admincallbacks.php';
require_once DIRNAME(__FILE__) . '/zigconnect-ajaxcallbacks.php';
require_once DIRNAME(__FILE__) . '/zigconnect-optionbuilder.php';
require_once DIRNAME(__FILE__) . '/zigconnect-tablebuilder.php';
require_once DIRNAME(__FILE__) . '/zigconnect-templatetags.php';


# DEFINE PLUGIN


class ZigConnect
	{
	public $DB;
	public $Params;
	public $Options;
	public $PluginFolder;
	public $ConnTable;
	public $FieldTable;
	public $LinkTable;
	public $DataTable;
	public $ListTable;
	public $Result;
	public $ResultType;
	public $ResultMessage;
	public $HelpText;
	public $FieldTypes;


	function __construct()
		{
		load_plugin_textdomain('zigconnect', false, basename(dirname(__FILE__)) . '/languages/');
		global $wp_version, $wpdb;
		if (version_compare(phpversion(), '5.2.4', '<')) $this->AutoDeactivate('ZigConnect requires PHP 5.2.4 or newer and has now deactivated itself. Please update your server before reactivating.'); 
		if (version_compare($wp_version, '3.1', '<')) $this->AutoDeactivate('ZigConnect requires WordPress 3.1 or newer and has now deactivated itself. Please update your installation before reactivating.'); 
		if (version_compare($wpdb->db_version(), '5.0.15', '<')) $this->AutoDeactivate('ZigConnect requires MySQL 5.0.15 or newer and has now deactivated itself. Please update your server before reactivating.'); 
		$this->DB = &$wpdb;
		$this->PluginFolder = get_bloginfo('wpurl') . '/' . PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)) . '/'; # url replaced with wpurl in version 0.8.6
		$this->PluginDirectory = WP_PLUGIN_DIR . '/zigconnect/'; # added in version 0.8.6 for use with sql file

#		echo $this->PluginFolder . '<br />';
#		echo $this->PluginDirectory . '<br />';

		$this->GetParams();
		$this->Options = get_option('zigconnect_options');
		$this->ConnTable = $this->DB->prefix . 'zc_connections';
		$this->FieldTable = $this->DB->prefix . 'zc_fields';
		$this->LinkTable = $this->DB->prefix . 'zc_links';
		$this->DataTable = $this->DB->prefix . 'zc_data';
		$this->ListTable = $this->DB->prefix . 'zc_listitems';
		add_action('admin_init', array($this, 'ActionAdminInit'));
		add_action('add_meta_boxes', array($this, 'ActionMetaBoxes'));
		add_action('save_post', array($this, 'ActionSaveMetaBoxData'));
		add_action('delete_post', array($this, 'ActionDeletePostData'));
		add_action('admin_head', array($this, 'ActionAdminHead'));
		add_action('manage_posts_custom_column', array($this, 'ActionCustomColumns'));
		add_action('admin_menu', array($this, 'ActionAdminMenu'));
		add_action('admin_footer', array($this, 'ActionAdminFooter'));
		add_filter('plugin_row_meta', array($this, 'FilterPluginRowMeta'), 10, 2 );
		$this->HelpText = $this->GetAdminHelp();
		$this->FieldTypes = array('TEXT', 'CHECKBOX');
		}


	# ACTIVATION & DEACTIVATION


	public function Activate()
		{
		new ZigConnectOptionBuilder();
		}


	public function Deactivate()
		{
		if ($this->Options['DeleteTablesNextDeactivate'] == 1)
			{
			$this->DB->query("DROP TABLE IF EXISTS `" . $this->ConnTable. "`");
			$this->DB->query("DROP TABLE IF EXISTS `" . $this->FieldTable. "`");
			$this->DB->query("DROP TABLE IF EXISTS `" . $this->LinkTable. "`");
			$this->DB->query("DROP TABLE IF EXISTS `" . $this->DataTable. "`");
			$this->DB->query("DROP TABLE IF EXISTS `" . $this->ListTable. "`");
			}
		if ($this->Options['DeleteOptionsNextDeactivate'] == 1) delete_option("zigconnect_options");
		}


	public function AutoDeactivate($strMessage)
		{
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
		deactivate_plugins(__FILE__);
		wp_die(__($strMessage, 'zigconnect')); 
		}


	# ACTIONS


	public function ActionAdminInit()
		{
		if ($this->Options['JustActivated'] == 1)
			{
			new ZigConnectTableBuilder();
			$this->Options['JustActivated'] = 0;
			update_option("zigconnect_options", $this->Options);
			}
		foreach ($this->GetConnectedPostTypes() as $type) add_filter('manage_edit-' . $type . '_columns', array($this, 'ActionAddCustomColumn'));
		new ZigConnectAjaxCallBacks($this->Params['zigaction']);
		new ZigConnectAdminCallBacks($this->Params['zigaction']);
		}


	public function ActionAdminHead()
		{
		?>
		<link rel="stylesheet" href="<?php echo $this->PluginFolder?>css/zigconnect-admin.css?<?php echo rand()?>" type="text/css" media="screen" />
		<?php
		}


	public function ActionAddCustomColumn($columns)
		{
		$newcolumn = array('zigconnections'=>__('ZigConnections', 'zigconnect'));
		return array_merge($columns, $newcolumn);
		}


	function ActionCustomColumns($column)
		{
		global $post;
		switch ($column)
			{
			case 'zigconnections':
				$arrConnectedTypes = $this->GetConnectedPostTypes($post->post_type);
				if (count($arrConnectedTypes) >= 1)
					{
					echo '<ul>';
					foreach ($arrConnectedTypes as $intIndex=>$strConnectedType)
						{
						echo '<li>' . count($this->GetLinkedPostIDs($post->ID, $strConnectedType, 0)) . ' ' . $strConnectedType. 's</li>';
						}
					echo '</ul>';
					}
				break;
			}
		}


	public function ActionMetaBoxes()
		{
		global $post;
		$arrTypes = $this->GetConnectedPostTypes($post->post_type);
		foreach ($arrTypes as $strType)
			{
			$objType = get_post_type_object($strType);
			if ($arrConnectionIDs = $this->GetConnectionIDsByTypes($post->post_type, $strType))
				{
				foreach ($arrConnectionIDs as $zc_conn_id)
					{
					$objConnection = $this->GetConnection($zc_conn_id);
					add_meta_box('zigconnect-' . $objConnection->zc_conn_slug, 'ZigConnect: ' . $objType->labels->name . ' (' . $objConnection->zc_conn_name . ')', array($this, 'DoMetaBox'), $post->post_type, 'normal', 'default', array('type'=>$objType->name, 'zc_conn_id'=>$zc_conn_id));
					}
				}
			}
		}


	public function ActionSaveMetaBoxData($post_id)
		{
		if (!wp_verify_nonce($_POST['zigconnect_metaboxes'], plugin_basename(__FILE__))) return $post_id; # crap out if bad nonce
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id; # crap out if autosave
		if (!current_user_can('manage_options')) return $post_id; # crap out if not an admin - CHANGE THIS TO SUITABLE CUSTOM TYPE PERMISSIONS
		global $post;
		$strThisType = $post->post_type;
		$arrConnections = $_POST['zigconnect_metabox_connections'];
		foreach ($arrConnections as $zc_conn_id)
			{
			$strOtherType = $this->GetOtherType($zc_conn_id, $strThisType);
			$arrOtherPostIDsToLink = $_POST['zigconnect_metabox_links'][$zc_conn_id];
			$arrAllOtherPostIDs = $this->GetPostIDsByType($strOtherType);
			foreach ($arrAllOtherPostIDs as $intOtherPostID)
				{
				if (@in_array($intOtherPostID, $arrOtherPostIDsToLink))
					{
					$this->SaveLink($zc_conn_id, $post->ID, $intOtherPostID);
					}
				else
					{
					$this->DeleteLink($this->GetLinkIDByPosts($post->ID, $intOtherPostID, $zc_conn_id));
					}
				}
			}
		$arrFieldIDs = $_POST['zigconnect_metabox_data'];
		if (is_array($arrFieldIDs))
			{
			# here we must see if there are checkbox fields on this connection - if so, we add to the array if not already present
			$arrCheckboxIDs = array();
			foreach ($arrConnections as $zc_conn_id)
				{
				$arrCheckboxIDs = array_merge($arrCheckboxIDs, $this->GetCheckboxIDs($zc_conn_id));
				}
			# now we have checkbox field IDs as values in this array
			# for each one, is it already taken account of?
			if (count($arrCheckboxIDs) >= 1)
				{
				foreach ($arrCheckboxIDs as $intCheckboxID)
					{
					if (!array_key_exists($intCheckboxID, $arrFieldIDs))
						{
						$arrFieldIDs[$intCheckboxID] = $arrOtherPostIDsToLink;
						}
					}
				}
			# now we have added in all relevant checkboxes
			foreach ($arrFieldIDs as $zc_field_id=>$arrOtherPostIDs)
				{
				$objField = $this->GetField($zc_field_id);
#echo $objField->zc_field_name . '<br/>';
				# here, if the field is a CHECKBOX field
				if ($objField->zc_field_type == 'CHECKBOX')
					{
#echo 'checkbox<br />';
					# we must go through ALL POSSIBLE "other" posts and set to zero (because UNCHECKED will be absent from the form post)
					foreach ($arrAllOtherPostIDs as $intOtherPostID)
						{
						$zc_link_id = $this->GetLinkIDByPosts($post->ID, $intOtherPostID);
						$objLink = $this->GetLink($zc_link_id);
						if ($objLink) 
							{
#$tempshop = get_post($objLink->zc_link_to);
#echo 'setting ' . $tempshop->post_title . ' to 0<br/ >';
							$this->SaveData($zc_field_id, $zc_link_id, 0);
							}
						}
					}
				# ok that's done
				foreach ($arrOtherPostIDs as $intOtherPostID=>$zc_data_value)
					{
					$objOtherPost = get_post($intOtherPostID);
					$arrOtherPostIDsToLink = $_POST['zigconnect_metabox_links'][$objField->zc_conn_id];
					if (@in_array($intOtherPostID, $arrOtherPostIDsToLink))
						{
						$this->SaveLink($objField->zc_conn_id, $post->ID, $intOtherPostID);
						$zc_link_id = $this->GetLinkIDByPosts($post->ID, $intOtherPostID);
						$objLink = $this->GetLink($zc_link_id);
						if ($objLink) 
							{
#if ($objField->zc_field_type == 'CHECKBOX')
#{
#$tempshop = get_post($objLink->zc_link_to);
#echo 'setting ' . $tempshop->post_title . ' to ' . $zc_data_value . '<br/ >';
#}
							$this->SaveData($zc_field_id, $zc_link_id, $zc_data_value);
							}
						}
					}
				}
			}
#die();
		}


	public function ActionDeletePostData($intPostID)
		{
		# this is hopelessly inadequate right now - we need to get all links for post so we can delete data items too, and list items that are only linked
		$this->DB->query("DELETE FROM " . $this->ListTable . " WHERE zc_post_id=" . $intPostID . " ");
		$this->DB->query("DELETE FROM " . $this->LinkTable . " WHERE (zc_link_from=" . $intPostID . ") OR (zc_link_to=" . $intPostID . ") ");
		return true;
		}


	public function ActionAdminMenu()
		{
		$arrAdminMenuHooks = array();
		$arrAdminMenuHooks[] = add_menu_page('ZigConnect', 'ZigConnect', 'manage_options', 'zigconnect-menu', array($this, 'DoAdminPageMain'), $this->PluginFolder . 'images/preferences-system-windows.png');
		$arrAdminMenuHooks[] = add_submenu_page('zigconnect-menu', 'Fields &lsaquo; ZigConnect', 'Fields', 'manage_options', 'zigconnect-menu-fields', array($this, 'DoAdminPageFields'));
		$arrAdminMenuHooks[] = add_submenu_page('zigconnect-menu', 'Options &lsaquo; ZigConnect', 'Options', 'manage_options', 'zigconnect-menu-settings', array($this, 'DoAdminPageSettings'));
		foreach ($arrAdminMenuHooks as $strHook) add_contextual_help($strHook, $this->HelpText);
		}


	public function ActionAdminFooter()
		{
		?>
		<script type="text/javascript" src="<?php echo $this->PluginFolder?>js/zigconnect-admin.js?<?php echo rand()?>"></script>
		<?php
		}


	public function FilterPluginRowMeta($links, $file) 
		{
		$plugin = plugin_basename(__FILE__);
		if ($file == $plugin) return array_merge($links, array('<a target="_blank" href="http://www.zigpress.com/donations/">Donate</a>'));
		return $links;
		}


	# FUNCTIONS


	# GET ARRAYS OF IDS


	public function GetConnectionIDs($intPostID = 0)
		{
		$sql = "SELECT zc_conn_id FROM " . $this->ConnTable . " ";
		if ($intPostID > 0)
			{
			$objPost = get_post($intPostID);
			$strPostType = $objPost->post_type;
			$sql .= "WHERE (zc_conn_from='" . $strPostType . "') OR (zc_conn_to='" . $strPostType . "') ";
			}
		$sql .= "ORDER BY zc_conn_id ASC ";
		return $this->DB->get_col($sql);
		}


	public function GetFieldIDs($zc_conn_id=false)
		{
		return $this->DB->get_col("SELECT zc_field_id FROM " . $this->FieldTable . " " . ( $zc_conn_id ? "WHERE (zc_conn_id=" . $zc_conn_id . ") " : "" ) . " ORDER BY zc_field_order ASC ");
		}


	public function GetCheckboxIDs($zc_conn_id=false)
		{
		return $this->DB->get_col("SELECT zc_field_id FROM " . $this->FieldTable . " " . "WHERE (zc_field_type='CHECKBOX') " . ( $zc_conn_id ? "AND (zc_conn_id=" . $zc_conn_id . ") " : "" ) . " ORDER BY zc_field_order ASC ");
		}


	public function GetLinkIDs($zc_conn_id=false)
		{
		return $this->DB->get_col("SELECT zc_link_id FROM " . $this->LinkTable . " " . ( $zc_conn_id ? "WHERE (zc_conn_id=" . $zc_conn_id . ") " : "" ) . " ORDER BY zc_link_id ASC ");
		}


	public function GetDataIDs($zc_conn_id = false, $zc_field_id = false)
		{
		# pass either one or the other, not both
		$sql  = "SELECT zc_data_id FROM " . $this->DataTable . " ";
		if ($zc_conn_id) { $sql .= "WHERE zc_conn_id=" . $zc_conn_id . " "; }
		elseif ($zc_field_id) { $sql .= "WHERE zc_field_id=" . $zc_field_id . " "; }
		return $this->DB->get_col($sql);
		}


	public function GetPostIDsByType($strType)
		{
		return $this->DB->get_col("SELECT ID FROM " . $this->DB->posts . " WHERE (post_type='" . $strType . "') AND (post_status='publish') ");
		}


	public function GetLinkedPostIDs($intPostID, $strType = '', $zc_conn_id = 0)
		{
		# gets linked post ids whether the passed post id is a "from" or a "to"
		$sql  = "SELECT " . $this->LinkTable . ".zc_link_to FROM " . $this->LinkTable . " LEFT JOIN " . $this->DB->posts . " ON (" . $this->LinkTable . ".zc_link_to=" . $this->DB->posts . ".ID) WHERE (zc_link_from=" . $intPostID . ") ";
		if ($strType != '') $sql .= "AND (" . $this->DB->posts . ".post_type='" . $strType . "') ";
		if ($zc_conn_id > 0) $sql .= "AND (" . $this->LinkTable . ".zc_conn_id=" . $zc_conn_id . ") ";
		$arrIDs = $this->DB->get_col($sql);
		$sql  = "SELECT " . $this->LinkTable . ".zc_link_from FROM " . $this->LinkTable . " LEFT JOIN " . $this->DB->posts . " ON (" . $this->LinkTable . ".zc_link_from=" . $this->DB->posts . ".ID) WHERE (zc_link_to=" . $intPostID . ") ";
		if ($strType != '') $sql .= "AND (" . $this->DB->posts . ".post_type='" . $strType . "') ";
		if ($zc_conn_id > 0) $sql .= "AND (" . $this->LinkTable . ".zc_conn_id=" . $zc_conn_id . ") ";
		return array_unique(array_merge($arrIDs, $this->DB->get_col($sql)));
		}


	# GET SINGLE IDS


	public function GetConnectionIDsByTypes($zc_conn_from, $zc_conn_to)
		{
		return $this->DB->get_col("SELECT zc_conn_id FROM " . $this->ConnTable . " WHERE ((zc_conn_from='" . $zc_conn_from . "') AND (zc_conn_to='" . $zc_conn_to . "')) OR ((zc_conn_to='" . $zc_conn_from . "') AND (zc_conn_from='" . $zc_conn_to . "') AND (zc_conn_reciprocal=1)) ");
		}


	public function GetConnectionIDByTypes($zc_conn_from, $zc_conn_to)
		{
		return $this->DB->get_var("SELECT zc_conn_id FROM " . $this->ConnTable . " WHERE ((zc_conn_from='" . $zc_conn_from . "') AND (zc_conn_to='" . $zc_conn_to . "')) OR ((zc_conn_to='" . $zc_conn_from . "') AND (zc_conn_from='" . $zc_conn_to . "') AND (zc_conn_reciprocal=1)) LIMIT 0, 1 ");
		}


	public function GetConnectionIDByLink($zc_link_id)
		{
		return $this->DB->get_var("SELECT zc_conn_id FROM " . $this->LinkTable . " WHERE (zc_link_id=" . $zc_link_id . ") ");
		}


	public function GetConnectionIDBySlug($zc_conn_slug)
		{
		return $this->DB->get_var("SELECT zc_conn_id FROM " . $this->ConnTable . " WHERE (zc_conn_slug='" . $zc_conn_slug . "') LIMIT 0, 1 ");
		}


	public function GetConnectionIDByName($zc_conn_name)
		{
		return $this->DB->get_var("SELECT zc_conn_id FROM " . $this->ConnTable . " WHERE (zc_conn_name='" . $zc_conn_name . "') LIMIT 0, 1 ");
		}


	public function GetFieldIDByName($zc_field_name)
		{
		return $this->DB->get_var("SELECT zc_field_id FROM " . $this->FieldTable . " WHERE (zc_field_name='" . $zc_field_name . "') ");
		}


	public function GetLinkIDByPosts($zc_link_from, $zc_link_to, $zc_conn_id = 0)
		{
		return $this->DB->get_var("SELECT zc_link_id FROM " . $this->LinkTable . " WHERE (((zc_link_from=" . $zc_link_from . ") AND (zc_link_to=" . $zc_link_to . ")) OR ((zc_link_to=" . $zc_link_from . ") AND (zc_link_from=" . $zc_link_to . "))) " . (($zc_conn_id > 0) ? "AND (zc_conn_id=" . $zc_conn_id . ") " : "") . " LIMIT 0, 1 ");
		}


	public function GetDataIDByFieldAndLink($zc_field_id, $zc_link_id)
		{
		return $this->DB->get_var("SELECT zc_data_id FROM " . $this->DataTable . " WHERE (zc_field_id=" . $zc_field_id . ") AND (zc_link_id=" . $zc_link_id . ") ");
		}


	# GET OBJECTS


	public function GetConnection($zc_conn_id)
		{
		return $this->DB->get_row("SELECT * FROM " . $this->ConnTable . " WHERE (zc_conn_id=" . $zc_conn_id . ") ", 'OBJECT');
		}


	public function GetField($zc_field_id)
		{
		return $this->DB->get_row("SELECT * FROM " . $this->FieldTable . " WHERE (zc_field_id=" . $zc_field_id . ") ", 'OBJECT');
		}


	public function GetLink($zc_link_id)
		{
		return $this->DB->get_row("SELECT * FROM " . $this->LinkTable . " WHERE (zc_link_id=" . $zc_link_id . ") ", 'OBJECT');
		}


	public function GetData($zc_data_id)
		{
		return $this->DB->get_row("SELECT * FROM " . $this->DataTable . " WHERE (zc_data_id=" . $zc_data_id . ") ", 'OBJECT');
		}


	# DELETE OBJECTS


	public function DeleteConnection($zc_conn_id)
		{
		$this->DB->query("DELETE FROM " . $this->DataTable . " WHERE zc_conn_id=" . $zc_conn_id . " ");
		$this->DB->query("DELETE FROM " . $this->LinkTable . " WHERE zc_conn_id=" . $zc_conn_id . " ");
		$this->DB->query("DELETE FROM " . $this->FieldTable . " WHERE zc_conn_id=" . $zc_conn_id . " ");
		$this->DB->query("DELETE FROM " . $this->ConnTable . " WHERE zc_conn_id=" . $zc_conn_id . " ");
		}


	public function DeleteField($zc_field_id)
		{
		$this->DB->query("DELETE FROM " . $this->DataTable . " WHERE zc_field_id=" . $zc_field_id . " ");
		$this->DB->query("DELETE FROM " . $this->FieldTable . " WHERE zc_field_id=" . $zc_field_id . " ");
		}


	public function DeleteLink($zc_link_id)
		{
		$this->DB->query("DELETE FROM " . $this->DataTable . " WHERE zc_link_id=" . $zc_link_id . " ");
		$this->DB->query("DELETE FROM " . $this->LinkTable . " WHERE zc_link_id=" . $zc_link_id . " ");
		}


	public function DeleteData($zc_data_id)
		{
		$this->DB->query("DELETE FROM " . $this->DataTable . " WHERE zc_data_id=" . $zc_data_id . " ");
		}


	# SAVE (OR CREATE) OBJECTS


	public function SaveConnection($zc_conn_id, $zc_conn_from, $zc_conn_to, $zc_conn_reciprocal, $zc_conn_slug, $zc_conn_name)
		{
		# if first parameter is -1, create the record before saving it
		if ($zc_conn_id == '-1')
			{
			$this->DB->query("INSERT INTO " . $this->ConnTable . "(zc_conn_from) VALUES('') ");
			$zc_conn_id = $this->DB->insert_id;
			}
		$this->DB->query("UPDATE " . $this->ConnTable . " SET zc_conn_from='" . esc_attr($zc_conn_from) . "', zc_conn_to='" . esc_attr($zc_conn_to) . "', zc_conn_reciprocal=" . $zc_conn_reciprocal . ", zc_conn_slug='" . esc_attr($zc_conn_slug) . "', zc_conn_name='" . esc_attr($zc_conn_name) . "' WHERE zc_conn_id=" . $zc_conn_id . " ");
		}


	public function SaveField($zc_field_id, $zc_conn_id, $zc_field_type, $zc_field_name, $zc_field_prompt, $zc_field_size, $zc_field_order = 0)
		{
		# if first parameter is -1, create the record before saving it
		if ($zc_field_id == '-1')
			{
			$this->DB->query("INSERT INTO " . $this->FieldTable . "(zc_conn_id) VALUES(0) ");
			$zc_field_id = $this->DB->insert_id;
			}
		$this->DB->query("UPDATE " . $this->FieldTable . " SET zc_conn_id=" . esc_attr($zc_conn_id) . ", zc_field_type='" . esc_attr($zc_field_type) . "', zc_field_name='" . esc_attr($zc_field_name) . "', zc_field_prompt='" . esc_attr($zc_field_prompt) . "', zc_field_size=" . esc_attr($zc_field_size) . ", zc_field_order=" . esc_attr($zc_field_order) . " WHERE zc_field_id=" . $zc_field_id . " ");
		}


	public function SaveLink($zc_conn_id, $zc_link_from, $zc_link_to)
		{
#echo 'in SaveLink<br />';
		if (!in_array($zc_link_to, $this->GetLinkedPostIDs($zc_link_from, '', $zc_conn_id)))
			{
			$this->DB->query("INSERT INTO " . $this->LinkTable . "(zc_conn_id, zc_link_from, zc_link_to) VALUES(" . $zc_conn_id . ", " . $zc_link_from . ", " . $zc_link_to . ") ");
#echo 'did insert<br />';
			}
		}


	public function SaveData($zc_field_id, $zc_link_id, $strValue)
		{
		$zc_data_id = $this->GetDataIDByFieldAndLink($zc_field_id, $zc_link_id);
		if (!is_numeric($zc_data_id))
			{
			$zc_conn_id = $this->GetConnectionIDByLink($zc_link_id);
			$this->DB->query("INSERT INTO " . $this->DataTable . "(zc_link_id, zc_field_id, zc_conn_id) VALUES(" . $zc_link_id . ", " . $zc_field_id . ", " . $zc_conn_id . ") ");
			$zc_data_id = $this->DB->insert_id;
			}
		$this->DB->query("UPDATE " . $this->DataTable . " SET zc_data_value='" . $strValue . "' WHERE zc_data_id=" . $zc_data_id . " ");
		}


	# MISCELLANEOUS DATA HANDLING


	public function GetConnectionShorthand($zc_conn_id)
		{
		$objConnection = $this->GetConnection($zc_conn_id);
		return $objConnection->zc_conn_from . (($objConnection->zc_conn_reciprocal == 1) ? ' &lt;=&gt; ' : ' =&gt; ') . $objConnection->zc_conn_to;
		}


	public function PostsAreLinked($intThisPostID, $intOtherPostID, $zc_conn_id = 0)
		{
		return (in_array($intOtherPostID, $this->GetLinkedPostIDs($intThisPostID, '', $zc_conn_id))) ? true : false;
		}


	public function GetConnectedPostTypes($strType = '')
		{
		$sql  = "SELECT zc_conn_to FROM " . $this->ConnTable . " ";
		if ($strType != '') { $sql .= "WHERE (zc_conn_from='" . $strType . "') "; }
		$arrTypes = $this->DB->get_col($sql);
		$sql = "SELECT zc_conn_from FROM " . $this->ConnTable . " ";
		if ($strType != '') { $sql .= "WHERE (zc_conn_reciprocal=1) AND (zc_conn_to='" . $strType . "') "; }
		return array_unique(array_merge($arrTypes, $this->DB->get_col($sql)));
		}


	public function GetOtherType($zc_conn_id, $strThisType)
		{
		$objConnection = $this->GetConnection($zc_conn_id);
		return ($objConnection->zc_conn_from == $strThisType) ? $objConnection->zc_conn_to : $objConnection->zc_conn_from;
		}


	# GENERIC FUNCTIONS INCLUDED IN ALL ZIGPRESS PLUGINS


	public function GetParams()
		{
		$this->Params = array();
		foreach ($_REQUEST as $key=>$value)
			{
			$this->Params[$key] = $value;
			if (!is_array($this->Params[$key])) { $this->Params[$key] = strip_tags(stripslashes(trim($this->Params[$key]))); }
			# need to sanitise arrays as well really
			}
		if (!is_numeric($this->Params['zigpage'])) { $this->Params['zigpage'] = 1; }
		if ($this->Params['zigaction'] == '') { $this->Params['zigaction'] = $this->Params['zigaction2']; }
		$this->Result = '';
		$this->ResultType = '';
		$this->ResultMessage = '';
		if ($this->Result = base64_decode($this->Params['r'])) list($this->ResultType, $this->ResultMessage) = explode('|', $this->Result); # base64 for ease of encoding
		}


	public function AllowChars($strValue, $strChars, $blnCaseSense = false)
		{
		$strResult = "";
		if ($blnCaseSense)
			{
			for ($i = 0; $i < strlen($strValue); $i++)
				{
				$strChar = substr($strValue, $i, 1);
				if (is_numeric(strpos($strChars, $strChar))) { $strResult .= $strChar; }
				}
			}
		else
			{
			$strChars = strtoupper($strChars);
			for ($i = 0; $i < strlen($strValue); $i++)
				{
				$strChar = substr($strValue, $i, 1);
				if (is_numeric(strpos($strChars, strtoupper($strChar)))) { $strResult .= $strChar; }
				}
			}
		return $strResult;
		}


	public function DenyChars($strValue, $strChars, $blnCaseSense = false)
		{
		$strResult = "";
		if ($blnCaseSense)
			{
			for ($i = 0; $i < strlen($strValue); $i++)
				{
				$strChar = substr($strValue, $i, 1);
				if (!is_numeric(strpos($strChars, $strChar))) { $strResult .= $strChar; }
				}
			}
		else
			{
			$strChars = strtoupper($strChars);
			for ($i = 0; $i < strlen($strValue); $i++)
				{
				$strChar = substr($strValue, $i, 1);
				if (!is_numeric(strpos($strChars, strtoupper($strChar)))) { $strResult .= $strChar; }
				}
			}
		return $strResult;
		}


	function ValidateAsInteger($param, $default = 0, $min = -1, $max = -1)
		{
		if (!is_numeric($param)) { $param = $default; }
		$param = (int) $param;
		if ($min != -1) { if ($param < $min) { $param = $min; } }
		if ($max != -1) { if ($param > $max) { $param = $max; } }
		return $param;
		}


	public function ShowResult($strType, $strMessage)
		{
		$strOutput = '';
		if ($strMessage != '')
			{
			$strClass = '';
			switch (strtoupper($strType))
				{
				case 'OK' : $strClass = 'updated'; break;
				case 'INFO' : $strClass = 'updated highlight'; break;
				case 'ERR' : $strClass = 'error'; break;
				case 'WARN' : $strClass = 'error'; break;
				}
			if ($strClass != '') $strOutput .= '<div class="msg ' . $strClass . '" title="' . __('Click to hide', 'zigconnect') . '"><p>' . $strMessage . '</p></div>';
			}
		return $strOutput;
		}


	function GetAllPostMeta($id = 0)
		{
		if ($id == 0)
			{
			global $wp_query;
			$content_array = $wp_query->get_queried_object();
			$id = $content_array->ID;
			}
		$data = array();
		$this->DB->query("SELECT meta_key, meta_value FROM {$this->DB->postmeta} WHERE post_id = {$id} ");
		foreach($this->DB->last_result as $k => $v)
			{
			$data[$v->meta_key] = $v->meta_value;
			}
		return $data;
		}


	# ADMIN CONTENT


	public function DoMetaBox($formpost, $args)
		{
		global $post;
		$args = $args['args'];
		$intThisPostID = $post->ID;
		$strThisType = $post->post_type;
		$strOtherType = $args['type'];
		$zc_conn_id = $args['zc_conn_id'];
		wp_nonce_field( plugin_basename(__FILE__), 'zigconnect_metaboxes' );
		$objConn = $this->GetConnection($zc_conn_id);
		$arrOtherPostIDs = $this->GetPostIDsByType($strOtherType);

		$intFieldsPerRow = $this->Options['FieldsPerRow'];
		if (!is_numeric($intFieldsPerRow)) $intFieldsPerRow = 3;

		if ($arrOtherPostIDs)
			{
			$arrFieldIDs = $this->GetFieldIDs($objConn->zc_conn_id);
			?>
			<input type="hidden" name="zigconnect_metabox_connections[]" value="<?php echo $zc_conn_id?>" />
			<table class="zigconnect-metabox-table" id="zigconnect-metabox-table-<?php echo $zc_conn_id?>">
			<?php
			$intLinks = 0;
			foreach ($arrOtherPostIDs as $intOtherPostID)
				{
				$objOtherPost = get_post($intOtherPostID);
				if ($this->PostsAreLinked($intThisPostID, $intOtherPostID, $zc_conn_id))
					{
					$intLinks++;
					?>
					<tr class="firstrow">
					<td><input type="checkbox" name="zigconnect_metabox_links[<?php echo $zc_conn_id?>][]" value="<?php echo $intOtherPostID?>" <?php echo $this->PostsAreLinked($intThisPostID, $intOtherPostID, $zc_conn_id) ? 'checked="checked"' : '' ?> />&nbsp;<?php echo $objOtherPost->post_title?>&nbsp;</td>
					<?php
					$zc_link_id = $this->GetLinkIDByPosts($intThisPostID, $intOtherPostID, $zc_conn_id);
					if ($arrFieldIDs)
						{
						$col = 0;
						foreach ($arrFieldIDs as $zc_field_id)
							{
							$col++;
							$objField = $this->GetField($zc_field_id);
							$zc_data_id = $this->GetDataIDByFieldAndLink($zc_field_id, $zc_link_id);
							?>
							<td style="text-align:left;">
							<?php
							switch ($objField->zc_field_type)
								{
								case 'CHECKBOX' :
									?>
									<input class="zc_field_<?php echo $objField->zc_field_name?>" type="checkbox" name="zigconnect_metabox_data[<?php echo $zc_field_id?>][<?php echo $intOtherPostID?>]" value="1" <?php if ($this->GetData($zc_data_id)->zc_data_value == '1') { echo('checked="checked"'); } ?> />
									<?php 
									echo $objField->zc_field_prompt;
									break;
								default : # TEXT
									echo $objField->zc_field_prompt;
									?>
									<br />
									<input class="zc_field_<?php echo $objField->zc_field_name?>" type="text" size="<?php echo $objField->zc_field_size?>" name="zigconnect_metabox_data[<?php echo $zc_field_id?>][<?php echo $intOtherPostID?>]" value="<?php echo $this->GetData($zc_data_id)->zc_data_value?>" />
									<?php
									break;
								}
							?>
							</td>
							<?php
							if ($col % $intFieldsPerRow == 0) echo '</tr><tr><td></td>';
							}
						if ($col % $intFieldsPerRow != 0) echo str_repeat('<td></td>', $intFieldsPerRow - ($col % $intFieldsPerRow));
						}
					?>
					</tr>
					<?php
					}
				}
			$objOtherType = get_post_type_object($strOtherType);
			?>
			</table>
			<div class="zigconnect-metabox-searchwrapper" id="zigconnect-metabox-searchwrapper-<?php echo $zc_conn_id?>">
			<div class="zigconnect-metabox-searchform" id="zigconnect-metabox-searchform-<?php echo $zc_conn_id?>">

			<input class="button-secondary zigconnect-metabox-addallbutton" type="button" id="zigconnect-metabox-addallbutton-<?php echo $zc_conn_id?>" value="<?php _e('Add All', 'zigconnect')?> <?php echo $objOtherType->labels->name?>!" />

			<input type="hidden" name="zigconnect-metabox-search-postid-<?php echo $zc_conn_id?>" id="zigconnect-metabox-search-postid-<?php echo $zc_conn_id?>" value="<?php echo $post->ID?>" />
			<input name="zigconnect-metabox-search-<?php echo $zc_conn_id?>" type="text" id="zigconnect-metabox-search-<?php echo $zc_conn_id?>" value="" class="medium-text" /> 
			<input class="button-secondary zigconnect-metabox-searchbutton" type="button" id="zigconnect-metabox-searchbutton-<?php echo $zc_conn_id?>" value="<?php _e('Search', 'zigconnect')?> <?php echo $objOtherType->labels->name?>" />
			<div class="zigconnect-metabox-searchresults" id="zigconnect-metabox-searchresults-<?php echo $zc_conn_id?>">
			</div><!--/zigconnect-metabox-searchresults-->
			<img class="zigconnect-metabox-loader" id="zigconnect-metabox-loader-<?php echo $zc_conn_id?>" style="display:none;" src="<?php echo $this->PluginFolder?>images/ajax-loader.gif" alt="" />
			</div><!--/zigconnect-metabox-searchform-->
			</div><!--/zigconnect-metabox-searchwrapper-->
			<?php
			}
		else
			{
			?>
			<?php _e('No posts of type', 'zigconnect')?> '<?php echo $strOtherType?>' <?php _e('found.', 'zigconnect')?>
			<?php
			}
		}


	public function GetAdminHelp()
		{
		$help = '<h5>' . __('ZigConnect General Help', 'zigconnect') . '</h5>';
		$help .= '<ul>';
		$help .= '<li>' . __('A <strong>connection</strong> defines a link between two post <strong>types</strong>.', 'zigconnect') . '</li>';
		$help .= '<li>' . __('A <strong>link</strong> is between two actual posts whose types are connected.', 'zigconnect') . '</li>';
		$help .= '<li>' . __('A <strong>field</strong> is a definition for a piece of data that can be stored with any link belonging to a specific connection.', 'zigconnect') . '</li>';
		$help .= '<li>' . __('A <strong>data item</strong> is a piece of data stored with a link according to a defined field.', 'zigconnect') . '</li>';
		$help .= '<li>' . __('For now all data fields are free text fields.', 'zigconnect') . '</li>';
		$help .= '</ul>';
		$help .= '<h5>' . __('ZigConnect Template Tags', 'zigconnect') . '</h5>';
		$help .= '<ul>';
		$help .= '<li>array <strong>zc_get_connections</strong> ( [int <em>$post_id</em>] )<br />' . __('Returns an array of connection IDs, optionally limited to connections that involve the post type of the specified post.', 'zigconnect') . '</li>';
		$help .= '<li>array <strong>zc_get_linked_posts</strong> ( string <em>$type</em> )<br />' . __('Returns an array of post IDs of posts that are of the specified type and are linked from the current post.', 'zigconnect') . '</li>';
		$help .= '<li>array <strong>zc_get_linkdata</strong> ( int <em>$post_id</em> )<br />' . __('Returns an associative array of key+value pairs for the data attached to the link between the current post and the specified post.', 'zigconnect') . '</li>';
		$help .= '<li>array <strong>zc_get_linkid</strong> ( int <em>$post_id</em> )<br />' . __('Returns the ID of the link between the current post and the specified post.', 'zigconnect') . '</li>';
		$help .= '</ul>';
		return $help;
		}


	public function DoAdminSidebar()
		{
		?>
		<table class="widefat donate" cellspacing="0">
		<thead>
		<tr><th><?php _e('Support this plugin!', 'zigconnect')?></th></tr>
		</thead>
		<tr><td>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="GT252NPAFY8NN">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		<p><?php _e('If you find ZigConnect useful, please keep it free by making a donation.', 'zigconnect')?></p>
		<p><?php _e('Suggested donation: &euro;20 - &euro;40 or an amount of your choice. Thanks!', 'zigconnect')?></p>
		</td></tr>
		</table>
		<table class="widefat donate" cellspacing="0">
		<thead>
		<tr><th><img class="icon floatRight" src="<?php echo $this->PluginFolder?>images/favicon.3.ico" alt="Yes" title="Yes" /><?php _e('Brought to you by ZigPress', 'zigconnect')?></th></tr>
		</thead>
		<tr><td>
		<p><a href="http://www.zigpress.com/">ZigPress</a> <?php _e('is a web agency specialising in WordPress-based solutions. We have also released a number of free plugins to support the WordPress community.', 'zigconnect')?></p>
		<p><a target="_blank" href="http://www.zigpress.com/wordpress/plugins/zigconnect/"><img class="icon" src="<?php echo $this->PluginFolder?>images/preferences-system-windows.png" alt="ZigConnect WordPress plugin ZigPress" title="ZigConnect WordPress plugin by ZigPress" /> <?php _e('ZigConnect page', 'zigconnect')?></a></p>
		<p><a target="_blank" href="http://www.zigpress.com/wordpress/plugins/"><img class="icon" src="<?php echo $this->PluginFolder?>images/plugin.png" alt="WordPress plugins by ZigPress" title="WordPress plugins by ZigPress" /> <?php _e('Other ZigPress plugins', 'zigconnect')?></a></p>
		<p><a target="_blank" href="http://www.facebook.com/pages/ZigPress/171766958751"><img class="icon" src="<?php echo $this->PluginFolder?>images/facebook.png" alt="ZigPress on Facebook" title="ZigPress on Facebook" /> <?php _e('ZigPress on Facebook', 'zigconnect')?></a></p>
		<p><a target="_blank" href="http://twitter.com/ZigPress"><img class="icon" src="<?php echo $this->PluginFolder?>images/twitter.png" alt="ZigPress on Twitter" title="ZigPress on Twitter" /> <?php _e('ZigPress on Twitter', 'zigconnect')?></a></p>
		</td></tr>
		</table>
		<?php
		}


	public function DoAdminPageMain()
		{
		if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
		if ($this->ResultType != '') echo $this->ShowResult($this->ResultType, $this->ResultMessage);
		# just in case the user upgraded the plugin by overwriting the old one - this saves them deactivating and reactivating to update the DB schema
		if ($this->DB->get_var("SHOW TABLES LIKE '" . $this->ListTable . "'") == $this->ListTable)
			{
			new ZigConnectTableBuilder();
			echo $this->ShowResult('INFO', 'Tables updated.');
			}
		if ($this->Params['zigaction'] == 'edit')
			{
			$id = $this->Params['chk'];
			$objConnection = $this->GetConnection($id);
			?>
			<div class="wrap zigconnect-admin">
			<div id="icon-zigconnect" class="icon32"><br /></div>
			<h2>ZigConnect - <?php _e('Edit Connection', 'zigconnect')?> <a href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu" class="button add-new-h2"><?php _e('Back without saving', 'zigconnect')?></a></h2>
			<div class="wrap-left">
			<div class="col-pad">
			<form action="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu" method="post">
			<input type="hidden" name="zigaction" value="zigconnect-admin-connections-update" />
			<input type="hidden" name="id" value="<?php echo $id?>" />
			<table class="form-table">
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Name:', 'zigconnect')?></th>
			<td><input type="text" name="zc_conn_name" id="zc_conn_name" value="<?php echo $objConnection->zc_conn_name?>"  /> <span class="description"><?php _e('For example: Stock Items, Historical Links, etc.', 'zigconnect')?></span></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Slug:', 'zigconnect')?></th>
			<td><input type="text" name="zc_conn_slug" id="zc_conn_slug" value="<?php echo $objConnection->zc_conn_slug?>"  /> <span class="description"><?php _e('Unique alphanumeric, will be converted to lower case', 'zigconnect')?></span></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('From:', 'zigconnect')?></th>
			<td><select name="zc_conn_from">
			<option value="">[<?php _e('please select', 'zigconnect')?>]</option>
			<?php
			foreach (get_post_types('', 'names') as $type)
				{
				?><option value="<?php echo $type?>" <?php if ($objConnection->zc_conn_from == $type) { echo('selected="selected"'); } ?> ><?php echo $type?></option><?php
				}
			?>
			</select></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('To:', 'zigconnect')?></th>
			<td><select name="zc_conn_to">
			<option value="">[<?php _e('please select', 'zigconnect')?>]</option>
			<?php
			foreach (get_post_types('', 'names') as $type)
				{
				?><option value="<?php echo $type?>" <?php if ($objConnection->zc_conn_to == $type) { echo('selected="selected"'); } ?> ><?php echo $type?></option><?php
				}
			?>
			</select></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Reciprocal?', 'zigconnect')?></th>
			<td><input class="checkbox" type="checkbox" name="zc_conn_reciprocal" id="zc_conn_reciprocal" value="1" <?php if ($objConnection->zc_conn_reciprocal == 1) { echo('checked="checked"'); } ?> /> <span class="description"><?php _e('means connection is bidirectional', 'zigconnect')?></span></td>
			</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'zigconnect')?>" /></p> 
			</form>
			</div><!--col-pad-->
			</div><!--wrap-left-->
			<div class="wrap-right">
			<?php
			$this->DoAdminSidebar();
			?>
			</div><!--wrap-right-->
			<div class="clearer">&nbsp;</div>
			</div><!--/wrap-->
			<?php
			}
		else
			{
			?>
			<div class="wrap zigconnect-admin">
			<div id="icon-zigconnect" class="icon32"><br /></div>
			<h2>ZigConnect <a href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu&amp;zigaction=edit&amp;chk=-1" class="button add-new-h2"><?php _e('Add New Connection', 'zigconnect')?></a></h2>
			<div class="wrap-left">
			<div class="col-pad">
			<?php
			$sql  = "SELECT * ";
			$sql .= "FROM " . $this->ConnTable . " ";
			$sql .= "ORDER BY zc_conn_from ASC ";
			$result = $this->DB->get_results($sql);
			if ($result)
				{
				?>
				<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="get">
				<input type="hidden" name="page" value="<?php echo $this->Params['page']?>" />
				<div class="tablenav">
				<div class="alignleft actions">
				<select name="zigaction">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'zigconnect')?></option>
				<option value="zigconnect-admin-connections-delete"><?php _e('Delete', 'zigconnect')?></option>
				</select>
				<input type="submit" value="<?php _e('Apply', 'zigconnect')?>" class="button-secondary apply" />
				</div><!--/actions-->
				<br class="clear" />
				</div><!--/tablenav-->
				<table class="widefat " cellspacing="0">
				<thead>
				<tr>
				<th class="check-column"><input type="checkbox" name="chkall" /></th>
				<th><?php _e('ID', 'zigconnect')?></th>
				<th><?php _e('Name', 'zigconnect')?></th>
				<th><?php _e('Details', 'zigconnect')?></th>
				<th><?php _e('Reciprocal?', 'zigconnect')?></th>
				<th><?php _e('Links', 'zigconnect')?></th>
				<th><?php _e('Data Fields', 'zigconnect')?></th>
				<th><?php _e('Data Items', 'zigconnect')?></th>
				</tr>
				</thead>
				<tfoot>
				<tr>
				<th class="check-column"><input type="checkbox" name="chkall" /></th>
				<th><?php _e('ID', 'zigconnect')?></th>
				<th><?php _e('Name', 'zigconnect')?></th>
				<th><?php _e('Details', 'zigconnect')?></th>
				<th><?php _e('Reciprocal?', 'zigconnect')?></th>
				<th><?php _e('Links', 'zigconnect')?></th>
				<th><?php _e('Data Fields', 'zigconnect')?></th>
				<th><?php _e('Data Items', 'zigconnect')?></th>
				</tr>
				</tfoot>
				<tbody>
				<?php
				foreach ($result as $row)
					{
					?>
					<tr>
					<th nowrap="nowrap" class="check-column"><input type="checkbox" name="chk[<?php echo $row->zc_conn_id?>] value="on" /></th>
					<td><?php echo $row->zc_conn_id?></td>
					<td><a class="row-title" title="Edit connection" href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu&amp;zigaction=edit&amp;chk=<?php echo $row->zc_conn_id?>"><?php echo $row->zc_conn_name?></a><div class="row-actions">
						<a href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu&amp;zigaction=edit&amp;chk=<?php echo $row->zc_conn_id?>"><?php _e('Edit', 'zigconnect')?></a> 
						| <a onclick="return confirm('<?php _e('Are you sure? Deleting a connection cannot be undone!', 'zigconnect')?>')" href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu&amp;zigaction=zigconnect-admin-connections-delete&amp;chk=<?php echo $row->zc_conn_id?>"><?php _e('Delete', 'zigconnect')?></a>
					</div></td>
					<td><?php echo $this->GetConnectionShorthand($row->zc_conn_id)?></td>
					<td nowrap="nowrap"><?php echo ($row->zc_conn_reciprocal == 1) ? '<img class="zigconnect-icon" src="' . $this->PluginFolder . 'images/tick.png" />' : '<img class="zigconnect-icon" src="' . $this->PluginFolder . 'images/bullet_cross.png" />'?></td>
					<td><?php echo count($this->GetLinkIDs($row->zc_conn_id))?></td>
					<td><?php echo count($this->GetFieldIDs($row->zc_conn_id))?></td>
					<td><?php echo count($this->GetDataIDs($row->zc_conn_id, false))?></td>
					</tr>
					<?php
					}
				?>
				</tbody>
				</table>
				<div class="tablenav">
				<div class="alignleft actions">
				<select name="zigaction2">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'zigconnect')?></option>
				<option value="zigconnect-admin-connections-delete"><?php _e('Delete', 'zigconnect')?></option>
				</select>
				<input type="submit" value="<?php _e('Apply', 'zigconnect')?>" class="button-secondary apply" />
				</div><!--/actions-->
				<br class="clear" />
				</div><!--/tablenav-->
				</form>
				<?php
				}
			else
				{
				?><p><?php _e('No connections found.', 'zigconnect')?></p><?php
				}
			?>
			<p><?php _e('Pull down this page\'s Help tab (top right) for assistance.', 'zigconnect')?></p>
			</div><!--col-pad-->
			</div><!--wrap-left-->
			<div class="wrap-right">
			<?php
			$this->DoAdminSidebar();
			?>
			</div><!--wrap-right-->
			<div class="clearer">&nbsp;</div>
			</div><!--/wrap-->
			<?php
			}
		}


	public function DoAdminPageFields()
		{
		if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
		if ($this->ResultType != '') echo $this->ShowResult($this->ResultType, $this->ResultMessage);
		if ($this->Params['zigaction'] == 'edit')
			{
			$id = $this->Params['chk'];
			$objField = $this->GetField($id);
			?>
			<div class="wrap zigconnect-admin">
			<div id="icon-zigconnect" class="icon32"><br /></div>
			<h2>ZigConnect - <?php _e('Edit Field', 'zigconnect')?> <a href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu-fields" class="button add-new-h2"><?php _e('Back without saving', 'zigconnect')?></a></h2>
			<div class="wrap-left">
			<div class="col-pad">
			<form action="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu-fields" method="post">
			<input type="hidden" name="zigaction" value="zigconnect-admin-fields-update" />
			<input type="hidden" name="id" value="<?php echo $id?>" />
			<table class="form-table">
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Connection:', 'zigconnect')?></th>
			<td><select name="zc_conn_id">
			<option value="">[<?php _e('please select', 'zigconnect')?>]&nbsp;</option>
			<?php
			$arrConnectionIDs = $this->GetConnectionIDs();
			foreach ($arrConnectionIDs as $zc_conn_id)
				{
				$objConnection = $this->GetConnection($zc_conn_id);
				?>
				<option value="<?php echo $zc_conn_id?>" <?php if ($objField->zc_conn_id == $zc_conn_id) { echo('selected="selected"'); } ?> ><?php echo $objConnection->zc_conn_name?> (<?php echo $this->GetConnectionShorthand($zc_conn_id)?>)&nbsp;</option>
				<?php
				}
			?>
			</select></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Slug:', 'zigconnect')?></th>
			<td><input type="text" name="zc_field_name" id="zc_field_name" value="<?php echo $objField->zc_field_name?>"  /> <span class="description"><?php _e('Unique alphanumeric, will be converted to lower case', 'zigconnect')?></span></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Name:', 'zigconnect')?></th>
			<td><input type="text" name="zc_field_prompt" id="zc_field_prompt" value="<?php echo $objField->zc_field_prompt?>"  /> <span class="description"><?php _e('Used as prompt on edit screens')?></span></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Type:', 'zigconnect')?></th>
			<td><select name="zc_field_type">
			<option value="">[<?php _e('please select', 'zigconnect')?>]&nbsp;</option>
			<?php
			foreach ($this->FieldTypes as $zc_field_type)
				{
				?>
				<option value="<?php echo $zc_field_type?>" <?php if ($objField->zc_field_type == $zc_field_type) { echo('selected="selected"'); } ?> ><?php echo ucwords(strtolower($zc_field_type))?>&nbsp;</option>
				<?php
				}
			?>
			</select></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Box size:', 'zigconnect')?></th>
			<td><input type="text" name="zc_field_size" id="zc_field_size" value="<?php echo $objField->zc_field_size?>"  /> <span class="description">As in &lt;input type=&quot;text&quot; size=&quot;n&quot;&gt; (if text)</span></td>
			</tr>
			<tr valign="top">
			<th class="right" scope="row"><?php _e('Order:', 'zigconnect')?></th>
			<td><input type="text" name="zc_field_order" id="zc_field_order" value="<?php echo $objField->zc_field_order?>"  /> <span class="description">The order in which fields are shown in the metaboxes</span></td>
			</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'zigconnect')?>" /></p> 
			</form>
			</div><!--col-pad-->
			</div><!--wrap-left-->
			<div class="wrap-right">
			<?php
			$this->DoAdminSidebar();
			?>
			</div><!--wrap-right-->
			<div class="clearer">&nbsp;</div>
			</div><!--/wrap-->
			<?php
			}
		else
			{
			?>
			<div class="wrap zigconnect-admin">
			<div id="icon-zigconnect" class="icon32"><br /></div>
			<h2>ZigConnect - <?php _e('Fields', 'zigconnect')?> <a href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu-fields&amp;zigaction=edit&amp;chk=-1" class="button add-new-h2"><?php _e('Add New Field', 'zigconnect')?></a></h2>
			<div class="wrap-left">
			<div class="col-pad">
			<?php
			$sql = "SELECT ";
			$sql .= $this->FieldTable . ".*, ";
			$sql .= $this->ConnTable . ".zc_conn_name ";
			$sql .= "FROM " . $this->FieldTable . " ";
			$sql .= "LEFT JOIN " . $this->ConnTable . " ";
			$sql .= "ON (" . $this->ConnTable . ".zc_conn_id=" . $this->FieldTable . ".zc_conn_id) ";
			$sql .= "ORDER BY " . $this->ConnTable . ".zc_conn_name ASC, zc_field_order ASC, zc_field_name ASC ";
			$result = $this->DB->get_results($sql);
			if ($result)
				{
				?>
				<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="get">
				<input type="hidden" name="page" value="<?php echo $this->Params['page']?>" />
				<div class="tablenav">
				<div class="alignleft actions">
				<select name="zigaction">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'zigconnect')?></option>
				<option value="zigconnect-admin-fields-delete"><?php _e('Delete', 'zigconnect')?></option>
				</select>
				<input type="submit" value="<?php _e('Apply', 'zigconnect')?>" class="button-secondary apply" />
				</div><!--/actions-->
				<br class="clear" />
				</div><!--/tablenav-->
				<table class="widefat " cellspacing="0">
				<thead>
				<tr>
				<th class="check-column"><input type="checkbox" name="chkall" /></th>
				<th><?php _e('ID', 'zigconnect')?></th>
				<th><?php _e('Slug', 'zigconnect')?></th>
				<th><?php _e('Name', 'zigconnect')?></th>
				<th><?php _e('Type', 'zigconnect')?></th>
				<th><?php _e('Connection', 'zigconnect')?></th>
				<th><?php _e('Order', 'zigconnect')?></th>
				<th><?php _e('Data Items', 'zigconnect')?></th>
				</tr>
				</thead>
				<tfoot>
				<tr>
				<th class="check-column"><input type="checkbox" name="chkall" /></th>
				<th><?php _e('ID', 'zigconnect')?></th>
				<th><?php _e('Slug', 'zigconnect')?></th>
				<th><?php _e('Name', 'zigconnect')?></th>
				<th><?php _e('Type', 'zigconnect')?></th>
				<th><?php _e('Connection', 'zigconnect')?></th>
				<th><?php _e('Order', 'zigconnect')?></th>
				<th><?php _e('Data Items', 'zigconnect')?></th>
				</tr>
				</tfoot>
				<tbody>
				<?php
				foreach ($result as $row)
					{
					?>
					<tr>
					<th nowrap="nowrap" class="check-column"><input type="checkbox" name="chk[<?php echo $row->zc_field_id?>] value="on" /></th>
					<td><?php echo $row->zc_field_id?></td>
					<td><a class="row-title" href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu-fields&amp;zigaction=edit&amp;chk=<?php echo $row->zc_field_id?>"><?php echo $row->zc_field_name?></a><div class="row-actions">
						<a href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu-fields&amp;zigaction=edit&amp;chk=<?php echo $row->zc_field_id?>"><?php _e('Edit', 'zigconnect')?></a> 
						| <a onclick="return confirm('<?php _e('Are you sure? Deleting a field cannot be undone!', 'zigconnect')?>')" href="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu-fields&amp;zigaction=zigconnect-admin-fields-delete&amp;chk=<?php echo $row->zc_field_id?>"><?php _e('Delete', 'zigconnect')?></a>
					</div></td>
					<td><?php echo $row->zc_field_prompt?></td>
					<td><?php echo ucwords(strtolower($row->zc_field_type))?></td>
					<td><?php echo $row->zc_conn_name?></td>
					<td><?php echo $row->zc_field_order?></td>
					<td><?php echo count($this->GetDataIDs(false, $row->zc_field_id))?></td>
					</tr>
					<?php
					}
				?>
				</tbody>
				</table>
				<div class="tablenav">
				<div class="alignleft actions">
				<select name="zigaction2">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'zigconnect')?></option>
				<option value="zigconnect-admin-fields-delete"><?php _e('Delete', 'zigconnect')?></option>
				</select>
				<input type="submit" value="<?php _e('Apply', 'zigconnect')?>" class="button-secondary apply" />
				</div><!--/actions-->
				<br class="clear" />
				</div><!--/tablenav-->
				</form>
				<?php
				}
			else
				{
				?><p><?php _e('No fields found.', 'zigconnect')?></p><?php
				}
			?>
			<p><?php _e('Pull down this page\'s Help tab (top right) for assistance.', 'zigconnect')?></p>
			</div><!--col-pad-->
			</div><!--wrap-left-->
			<div class="wrap-right">
			<?php
			$this->DoAdminSidebar();
			?>
			</div><!--wrap-right-->
			<div class="clearer">&nbsp;</div>
			</div><!--/wrap-->
			<?php
			}
		}


	public function DoAdminPageSettings()
		{
		if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
		if ($this->ResultType != '') echo $this->ShowResult($this->ResultType, $this->ResultMessage);
		?>
		<div class="wrap zigconnect-admin">
		<div id="icon-zigconnect" class="icon32"><br /></div>
		<h2>ZigConnect - <?php _e('Options', 'zigconnect')?></h2>
		<div class="wrap-left">
		<div class="col-pad">
		<p><?php _e('The options below allow you to uninstall cleanly if you need to. Use with care!', 'zigconnect')?></p>
		<form action="<?php echo $_SERVER['PHP_SELF']?>?page=zigconnect-menu-settings" method="post">
		<input type="hidden" name="zigaction" value="zigconnect-admin-options-update" />
		<?php wp_nonce_field('zigpress_nonce'); ?>
		<table class="form-table">
		<tr valign="top">
		<th scope="row" class="right">Fields per row on links:</th>
		<td><input name="FieldsPerRow" type="text" id="FieldsPerRow" value="<?php echo esc_attr($this->Options['FieldsPerRow']) ?>" class="small-text" /> <span class="description">Suggest between 3 and 6</span></td>
		</tr>
		<tr valign="top">
		<th scope="row" class="right"><?php _e('Next deactivation removes:', 'zigconnect')?></th>
		<td><input class="checkbox" type="checkbox" name="DeleteOptionsNextDeactivate" id="DeleteOptionsNextDeactivate" value="1" <?php if ($this->Options['DeleteOptionsNextDeactivate'] == 1) { echo('checked="checked"'); } ?> /> <?php _e('Settings', 'zigconnect')?> &nbsp; &nbsp; <input class="checkbox" type="checkbox" name="DeleteTablesNextDeactivate" id="DeleteTablesNextDeactivate" value="1" <?php if ($this->Options['DeleteTablesNextDeactivate'] == 1) { echo('checked="checked"'); } ?> /> <?php _e('Tables', 'zigconnect')?></td>
		</tr>
		</table>
		<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'zigconnect')?>" /></p> 
		</form>
		</div><!--col-pad-->
		</div><!--wrap-left-->
		<div class="wrap-right">
		<?php
		$this->DoAdminSidebar();
		?>
		</div><!--wrap-right-->
		<div class="clearer">&nbsp;</div>
		</div><!--/wrap-->
		<?php
		}


	} # end of class


# INSTANTIATE PLUGIN


$objZigConnect = new ZigConnect();
register_activation_hook(__FILE__, array(&$objZigConnect, 'Activate'));
register_deactivation_hook(__FILE__, array(&$objZigConnect, 'Deactivate'));


# EOF

