<?php


class ZigConnectTableBuilder
	{
	function __construct()
		{
		global $objZigConnect;
		$sql = file_get_contents($objZigConnect->PluginDirectory . 'sql/zigconnect.sql');
		$sql = str_replace('ZIGCONNECT_TABLENAME_ZC_CONN', $objZigConnect->ConnTable, $sql);
		$sql = str_replace('ZIGCONNECT_TABLENAME_ZC_FIELD', $objZigConnect->FieldTable, $sql);
		$sql = str_replace('ZIGCONNECT_TABLENAME_ZC_LINK', $objZigConnect->LinkTable, $sql);
		$sql = str_replace('ZIGCONNECT_TABLENAME_ZC_DATA', $objZigConnect->DataTable, $sql);
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		}


	}


# EOF
