<?php

class WpToFbTemplate{

	static function get_wptofb_data( $id ){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . "wptofb";

		$query = "SELECT DISTINCT title, introtext,outrotext, hide_for_nofans, nofans,contents, tpl, fb_app_id, fb_app_secret FROM " . $table_name . " WHERE id = %d AND active = '1'";
		
		$safe_query = $wpdb->prepare( $query, $id );
		$data = $wpdb->get_row( $safe_query );
		
		return $data;

	}

}