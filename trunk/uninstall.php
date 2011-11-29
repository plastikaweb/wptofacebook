<?php
// If uninstall/delete not called from WordPress then exit
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
exit ();

$wptofbOptions = get_option( 'wptofb_options_plastikaweb' );
if( !empty($wptofbOptions ) ){
	delete_option( 'wptofb_options_plastikaweb' );
}

global $wpdb;

$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wptofb");
