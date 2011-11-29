<div class="updated">
<p><strong> <?php
if ( !isset( $_REQUEST[ 'id' ] ) && !isset( $_REQUEST[ '_wpnonce' ] ) 
		&&  !wp_verify_nonce( $_REQUEST[ '_wpnonce' ], 'wptofb-delete_connid' . $_REQUEST[ 'id' ] ) ){
	_e( 'Security error. Try again.', 'wp-to-fb' );
}else{
	WpToFb::wptofb_delete( $_REQUEST[ 'id' ] );
	_e( 'Register deleted.', 'wp-to-fb' );
}
?> </strong></p>
</div>
