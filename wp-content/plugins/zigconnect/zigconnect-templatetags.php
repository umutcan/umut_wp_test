<?php


function zc_get_connections($intPostID = 0)
	{
	global $objZigConnect;
	if (!is_object($objZigConnect)) return false;
	return $objZigConnect->GetConnectionIDs($intPostID);
	}


function zc_get_connection_by_name($strName)
	{
	global $objZigConnect;
	if (!is_object($objZigConnect)) return false;
	return $objZigConnect->GetConnectionIDByName($strName);
	}


function zc_get_connection_by_slug($strSlug)
	{
	global $objZigConnect;
	if (!is_object($objZigConnect)) return false;
	return $objZigConnect->GetConnectionIDBySlug($strSlug);
	}


function zc_get_linked_posts($strOtherType, $zc_conn_id = 0)
	{
	global $objZigConnect, $post;
	if (!is_object($objZigConnect)) return false;
	return $objZigConnect->GetLinkedPostIDs($post->ID, $strOtherType, $zc_conn_id);
	}


function zc_get_linked_posts_of_post($intThisPostID, $strOtherType, $zc_conn_id = 0)
	{
	global $objZigConnect, $post;
	if (!is_object($objZigConnect)) return false;
	return $objZigConnect->GetLinkedPostIDs($intThisPostID, $strOtherType, $zc_conn_id);
	}


function zc_get_linkdata($intOtherPostID, $zc_conn_id = 0, $intThisPostOverriddenID = 0)
	{
	global $objZigConnect, $post;
	$intThisPostID = $post->ID;
	if ($intThisPostOverriddenID > 0) $intThisPostID = $intThisPostOverriddenID;
	if (!is_object($objZigConnect)) return false;
	$zc_link_id = $objZigConnect->GetLinkIDByPosts($intThisPostID, $intOtherPostID, $zc_conn_id);
	if ($zc_conn_id == 0) $zc_conn_id = $objZigConnect->GetLink($zc_link_id)->zc_conn_id;
	$arrFieldIDs = $objZigConnect->GetFieldIDs($zc_conn_id);
	$arrDataPairs = array();
	foreach ($arrFieldIDs as $zc_field_id)
		{
		$zc_data_id = $objZigConnect->GetDataIDByFieldAndLink($zc_field_id, $zc_link_id);
		$arrDataPairs[$objZigConnect->GetField($zc_field_id)->zc_field_name] = $objZigConnect->GetData($zc_data_id)->zc_data_value;
		}
	return $arrDataPairs;
	}


function zc_get_linkid($intOtherPostID, $zc_conn_id = 0)
	{
	global $objZigConnect, $post;
	if (!is_object($objZigConnect)) return false;
	return $objZigConnect->GetLinkIDByPosts($post->ID, $intOtherPostID, $zc_conn_id);
	}


# EOF
