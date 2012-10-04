<?php


class ZigConnectAdminCallBacks
	{


	public function __construct($zigaction)
		{
		if ($zigaction == 'zigconnect-admin-connections-delete') { $this->DoDeleteConnection(); }
		if ($zigaction == 'zigconnect-admin-connections-update') { $this->DoUpdateConnection(); }
		if ($zigaction == 'zigconnect-admin-fields-delete') { $this->DoDeleteField(); }
		if ($zigaction == 'zigconnect-admin-fields-update') { $this->DoUpdateField(); }
		if ($zigaction == 'zigconnect-admin-options-update') { $this->DoUpdateOptions(); }
		}


		public function DoDeleteConnection()
			{
			global $objZigConnect;
			if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
			$objZigConnect->Result = 'OK|' . __('Selected connections and all related data deleted.', 'zigconnect'); 
			$ids = $objZigConnect->Params['chk'];
			if (is_array($ids)) { $ids = array_keys($ids); }
			$intDeleted = 0;
			foreach ((array) $ids as $id)
				{
				$objZigConnect->DeleteConnection($id);
				$intDeleted++;
				}
			if ($intDeleted == 0)
				{
				$objZigConnect->Result = 'ERR|' . __('No connections were selected.', 'zigconnect'); 
				}
			ob_clean();
			wp_redirect($_SERVER['PHP_SELF'] . '?page=zigconnect-menu&r=' . base64_encode($objZigConnect->Result));
			exit();
			}


		public function DoUpdateConnection()
			{
			global $objZigConnect;
			if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
			$objZigConnect->Result = 'OK|' . __('Connection updated.', 'zigconnect'); 
			$id = (is_numeric(trim($objZigConnect->Params['id']))) ? (int) trim($objZigConnect->Params['id']) : 0 ;
			$zc_conn_slug = $objZigConnect->AllowChars(strtolower($objZigConnect->Params['zc_conn_slug']), 'abcdefghijklmnopqrstuvwxyz0123456789-', false);
			$zc_conn_name = trim($objZigConnect->Params['zc_conn_name']);
			$zc_conn_from = $objZigConnect->Params['zc_conn_from'];
			$zc_conn_to = $objZigConnect->Params['zc_conn_to'];
			$zc_conn_reciprocal = ($objZigConnect->Params['zc_conn_reciprocal'] == '1') ? 1 : 0 ;
			$blnOK = true;
			$intDuplicate = $objZigConnect->GetConnectionIDByName($zc_conn_name);
			if (is_numeric($intDuplicate))
				{
				if ($id != $intDuplicate)
					{
					$blnOK = false;
					$objZigConnect->Result = 'ERR|' . __('Duplicate connection name.', 'zigconnect'); 
					}
				}
			if (($zc_conn_from == '') || ($zc_conn_to == ''))
				{
				$blnOK = false;
				$objZigConnect->Result = 'ERR|' . __('Content type(s) not selected.', 'zigconnect'); 
				}
			if (($zc_conn_name == '') || ($zc_conn_slug == ''))
				{
				$blnOK = false;
				$objZigConnect->Result = 'ERR|' . __('Name and/or slug not entered.', 'zigconnect'); 
				}
			if ($blnOK)
				{
				$objZigConnect->SaveConnection($id, $zc_conn_from, $zc_conn_to, $zc_conn_reciprocal, $zc_conn_slug, $zc_conn_name);
				}
			ob_clean();
			wp_redirect($_SERVER['PHP_SELF'] . '?page=zigconnect-menu&r=' . base64_encode($objZigConnect->Result));
			exit();
			}


		public function DoDeleteField()
			{
			global $objZigConnect;
			if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
			$objZigConnect->Result = 'OK|' . __('Selected fields and all related data deleted.', 'zigconnect'); 
			$ids = $objZigConnect->Params['chk'];
			if (is_array($ids)) { $ids = array_keys($ids); }
			$intDeleted = 0;
			foreach ((array) $ids as $id)
				{
				$objZigConnect->DeleteField($id);
				$intDeleted++;
				}
			if ($intDeleted == 0)
				{
				$objZigConnect->Result = 'ERR|' . __('No fields were selected.', 'zigconnect'); 
				}
			ob_clean();
			wp_redirect($_SERVER['PHP_SELF'] . '?page=zigconnect-menu-fields&r=' . base64_encode($objZigConnect->Result));
			exit();
			}


		public function DoUpdateField()
			{
			global $objZigConnect;
			if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
			$objZigConnect->Result = 'OK|' . __('Field updated.', 'zigconnect'); 
			$id = (is_numeric(trim($objZigConnect->Params['id']))) ? (int) trim($objZigConnect->Params['id']) : 0 ;
			$zc_conn_id = $objZigConnect->Params['zc_conn_id'];
			$zc_field_name = strtolower($objZigConnect->AllowChars($objZigConnect->Params['zc_field_name'], 'abcdefghijklmnopqrstuvwxyz0123456789', false));
			$zc_field_type = $objZigConnect->Params['zc_field_type'];
			$zc_field_prompt = $objZigConnect->Params['zc_field_prompt'];
			$zc_field_size = $objZigConnect->ValidateAsInteger(htmlspecialchars($objZigConnect->Params['zc_field_size']), 10, 1, 100);
			$zc_field_order = $objZigConnect->ValidateAsInteger(htmlspecialchars($objZigConnect->Params['zc_field_order']), 0, 0, 100);
			$blnOK = true;
			$intDuplicate = $objZigConnect->GetFieldIDByName($zc_field_name);
			if (is_numeric($intDuplicate))
				{
				if ($id != $intDuplicate)
					{
					$blnOK = false;
					$objZigConnect->Result = 'ERR|' . __('Field already exists.', 'zigconnect'); 
					}
				}
			if (($zc_field_name == '') || ($zc_field_prompt == ''))
				{
				$blnOK = false;
				$objZigConnect->Result = 'ERR|' . __('Name or prompt not entered.', 'zigconnect'); 
				}
			if ($blnOK)
				{
				$objZigConnect->SaveField($id, $zc_conn_id, $zc_field_type, $zc_field_name, $zc_field_prompt, $zc_field_size, $zc_field_order);
				}
			ob_clean();
			wp_redirect($_SERVER['PHP_SELF'] . '?page=zigconnect-menu-fields&r=' . base64_encode($objZigConnect->Result));
			exit();
			}


		public function DoUpdateOptions()
			{
			global $objZigConnect;
			if (!current_user_can('manage_options')) { wp_die(__('You are not allowed to do this.', 'zigconnect')); }
			check_admin_referer('zigpress_nonce');
			$objZigConnect->Options['FieldsPerRow'] = $objZigConnect->ValidateAsInteger(htmlspecialchars($objZigConnect->Params['FieldsPerRow']), 3, 1, 10);
			$objZigConnect->Options['DeleteOptionsNextDeactivate'] = $objZigConnect->ValidateAsInteger(htmlspecialchars($objZigConnect->Params['DeleteOptionsNextDeactivate']), 0, 0, 1);
			$objZigConnect->Options['DeleteTablesNextDeactivate'] = $objZigConnect->ValidateAsInteger(htmlspecialchars($objZigConnect->Params['DeleteTablesNextDeactivate']), 0, 0, 1);
			# re-save options
			update_option("zigconnect_options", $objZigConnect->Options);
			$objZigConnect->Result = 'OK|' . __('Options saved.', 'zigconnect'); 
			ob_clean();
			wp_redirect($_SERVER['PHP_SELF'] . '?page=zigconnect-menu-settings&r=' . base64_encode($objZigConnect->Result));
			exit();
			}


	}


# EOF
