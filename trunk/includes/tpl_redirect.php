<?php
require_once 'WpToFbTemplate.php';
require_once 'facebook.php';

//plugin installed or activated ?
$wptofbOptions = get_option( 'wptofb_options_plastikaweb' );

if( empty($wptofbOptions ) || !isset( $_GET[ 'wptofb' ] ) || empty( $_GET[ 'wptofb' ]) ){
	exit( 'Error' );
}

//entry on wptofb table
$id = $_GET[ 'wptofb' ];
$data = WpToFbTemplate::get_wptofb_data( $id );


//id does not exist
if( empty( $data ) ):
exit( _e( 'No data found', 'wp-to-fb' ) );
endif;

//query the posts with arguments
$contents_data = unserialize( $data->contents );

//print_r($contents_data[ 'taxonomy_ids' ]);
if( $contents_data[ 'custom' ] == 'automatic' ){
	$args = array(
		'post_type' => $contents_data[ 'types_of_content' ],
		'order'		=> $contents_data[ 'order' ],
		'orderby'	=> $contents_data[ 'order_by' ],
		'posts_per_page' => $contents_data[ 'max_posts' ],
		'tax_query'		=> $contents_data[ 'taxonomy_ids' ]
	);
}else if( $contents_data[ 'custom' ] == 'manual' ){
	$args = array(
		'post__in' => $contents_data[ 'ids_conns' ],
		'post_type' => $contents_data[ 'types_of_content' ],
	);
}

$my_query = new WP_Query( $args );

//facebook init
$facebook = new Facebook( array( 'appId' => $data->fb_app_id,
								'secret' => $data->fb_app_secret,
								'cookie' => true
) );


$signed_request = $facebook->getSignedRequest();

$facebook_data[ 'page_id' ] = $signed_request[ "page" ][ "id" ];
$facebook_data[ 'like' ] = $signed_request[ "page" ][ "liked" ];

include_once( WP_PLUGIN_DIR . '/wp-to-fb/tpls/header.php' );

if( !$facebook_data[ 'like' ] ){
	//contents for no fans
	include_once( WP_PLUGIN_DIR . '/wp-to-fb/tpls/nofans.php' );
}

if ( $facebook_data[ 'like' ] || ( !$facebook_data[ 'like' ] && !$data->hide_for_nofans ) ){
	include_once( WP_PLUGIN_DIR . '/wp-to-fb/tpls/introtext.php' );
	include_once( WP_PLUGIN_DIR . '/wp-to-fb/tpls/'.$data->tpl.'/tpl.php' );
	include_once( WP_PLUGIN_DIR . '/wp-to-fb/tpls/footer.php' );
}else{
	include_once( WP_PLUGIN_DIR . '/wp-to-fb/tpls/nofans_footer.php' );
}

