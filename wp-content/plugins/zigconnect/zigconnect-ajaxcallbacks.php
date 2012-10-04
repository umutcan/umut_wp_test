<?php


class ZigConnectAjaxCallBacks
	{


	public function __construct($zigaction)
		{
		if ($zigaction == 'zigconnect-ajax-search') { $this->DoAjaxSearch(); }
		if ($zigaction == 'zigconnect-ajax-getrow') { $this->DoAjaxGetRow(); }
		if ($zigaction == 'zigconnect-ajax-addall') { $this->DoAjaxAddAll(); }
		}


	public function DoAjaxSearch()
		{
		global $objZigConnect;
		if (!current_user_can('edit_posts')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
		# build a select of all post ids / titles that can be linked from this post but aren't yet, that match the query
		ob_clean();
		$strQuery = trim(esc_attr($objZigConnect->Params['strQuery']));
		$intThisPostID = esc_attr($objZigConnect->Params['intThisPostID']);
		$objThisPost = get_post($intThisPostID);

		$zc_conn_id = esc_attr($objZigConnect->Params['zc_conn_id']);
		$strOtherType = $objZigConnect->GetOtherType($zc_conn_id, $objThisPost->post_type);
		$arrAllPostIDs = $objZigConnect->GetPostIDsByType($strOtherType);
		$arrLinkedPostIDs = $objZigConnect->GetLinkedPostIDs($intThisPostID, $strOtherType, $zc_conn_id);
		$arrPostIDsToCheck = array_diff($arrAllPostIDs, $arrLinkedPostIDs);
		$arrMatchingPostIDs = array();
		foreach($arrPostIDsToCheck as $intPostIDToCheck)
			{
			$objPost = get_post($intPostIDToCheck);
			if ((stripos($objPost->post_title, $strQuery) !== false) || ($strQuery == ''))
				{
				$arrMatchingPostIDs[$intPostIDToCheck] = $objPost->post_title;
				}
			}
		# echo HTML SELECT for search results
		if (count($arrMatchingPostIDs) >= 1)
			{
			?>
			<select name="zigconnect-metabox-results-<?php echo $zc_conn_id?>" class="zigconnect-metabox-results" id="zigconnect-metabox-results-<?php echo $zc_conn_id?>">
			<?php
			foreach ($arrMatchingPostIDs as $intMatchingPostID=>$strMatchingPostTitle)
				{
				?>
				<option value="<?php echo $intMatchingPostID?>"><?php echo $strMatchingPostTitle?></option>
				<?php
				}
			?>
			</select>
			<input class="button-secondary zigconnect-metabox-addresult" type="button" id="zigconnect-metabox-addresult-<?php echo $zc_conn_id?>" value="<?php _e('Add', 'zigconnect')?>" />
			<?php
			}
		exit();
		}


	public function DoAjaxGetRow()
		{
		global $objZigConnect;
		if (!current_user_can('edit_posts')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
		ob_clean();
		$zc_conn_id = $objZigConnect->Params['zc_conn_id'];
		$intThisPostID = $objZigConnect->Params['intThisPostID'];
		$intOtherPostID = $objZigConnect->Params['intOtherPostID'];
		$objThisPost = get_post($intThisPostID);
		$objOtherPost = get_post($intOtherPostID);

		$intFieldsPerRow = $objZigConnect->Options['FieldsPerRow'];
		if (!is_numeric($intFieldsPerRow)) $intFieldsPerRow = 3;
		?>
		<tr class="firstrow">
		<td><input type="checkbox" name="zigconnect_metabox_links[<?php echo $zc_conn_id?>][]" value="<?php echo $intOtherPostID?>" checked="checked" />&nbsp;<?php echo $objOtherPost->post_title?>&nbsp;</td>
		<?php
		$arrFieldIDs = $objZigConnect->GetFieldIDs($zc_conn_id);
		$col = 0;
		$zc_link_id = $objZigConnect->GetLinkIDByPosts($intThisPostID, $intOtherPostID, $zc_conn_id);
		if ($arrFieldIDs)
			{
			foreach ($arrFieldIDs as $zc_field_id)
				{
				$col++;
				$objField = $objZigConnect->GetField($zc_field_id);
				$zc_data_id = $objZigConnect->GetDataIDByFieldAndLink($zc_field_id, $zc_link_id);
				?>
				<td style="text-align:left;">
				<?php
				switch ($objField->zc_field_type)
					{
					case 'CHECKBOX' :
						?>
						<input class="zc_field_<?php echo $objField->zc_field_name?>" type="checkbox" name="zigconnect_metabox_data[<?php echo $zc_field_id?>][<?php echo $intOtherPostID?>]" value="1" <?php if ($objZigConnect->GetData($zc_data_id)->zc_data_value == '1') { echo('checked="checked"'); } ?> />
						<?php 
						echo $objField->zc_field_prompt;
						break;
					default : # TEXT
						echo $objField->zc_field_prompt;
						?>
						<br />
						<input class="zc_field_<?php echo $objField->zc_field_name?>" type="text" size="<?php echo $objField->zc_field_size?>" name="zigconnect_metabox_data[<?php echo $zc_field_id?>][<?php echo $intOtherPostID?>]" value="<?php echo $objZigConnect->GetData($zc_data_id)->zc_data_value?>" />
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
		exit();
		}


	public function DoAjaxAddAll()
		{
		global $objZigConnect;
		if (!current_user_can('edit_posts')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
		ob_clean();
		$intThisPostID = esc_attr($objZigConnect->Params['intThisPostID']);
		$zc_conn_id = esc_attr($objZigConnect->Params['zc_conn_id']);
		# we can do everything with the above two parameters
		$objThisPost = get_post($intThisPostID);
		$strOtherType = $objZigConnect->GetOtherType($zc_conn_id, $objThisPost->post_type);
		$arrAllPostIDs = $objZigConnect->GetPostIDsByType($strOtherType);
		$arrLinkedPostIDs = $objZigConnect->GetLinkedPostIDs($intThisPostID, $strOtherType, $zc_conn_id);
		$arrPostIDsToLink = array_diff($arrAllPostIDs, $arrLinkedPostIDs);

		$intFieldsPerRow = $objZigConnect->Options['FieldsPerRow'];
		if (!is_numeric($intFieldsPerRow)) $intFieldsPerRow = 3;

		foreach ($arrPostIDsToLink as $intOtherPostID)
			{
			$objOtherPost = get_post($intOtherPostID);
			?>
			<tr>
			<td><input type="checkbox" name="zigconnect_metabox_links[<?php echo $zc_conn_id?>][]" value="<?php echo $intOtherPostID?>" checked="checked" />&nbsp;<?php echo $objOtherPost->post_title?>&nbsp;</td>
			<?php
			$arrFieldIDs = $objZigConnect->GetFieldIDs($zc_conn_id);
			$col = 0;
			if ($arrFieldIDs)
				{
				foreach ($arrFieldIDs as $zc_field_id)
					{
					$col++;
					$objField = $objZigConnect->GetField($zc_field_id);
					?>
					<td class="right">
					<?php
					echo $objField->zc_field_prompt;
					?>:
					<input type="text" name="zigconnect_metabox_data[<?php echo $zc_field_id?>][<?php echo $intOtherPostID?>]" />
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
		exit();
		}


	}


# EOF
