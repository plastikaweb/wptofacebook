<!-- new register button-->
<div class="wrap"><?php screen_icon( 'page' );?>
	<h2>WpToFacebook</h2>
	<form method="post" action="<?php echo admin_url( 'admin.php?page=edit-wptofb' );?>">
		<div class="submit">
			<input type="submit" name="new_wptofb" 
				value="<?php _e( 'New Facebook App Connection', 'wp-to-fb' ); ?>" />
		</div>
<?php 
//delete record
if( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] == "delete" ){
	include 'delete_page.php';
}
?>		
		
<?php
//list of existing registers on the table

$records = WpToFb::wptofb_select_all();
$num_records = count( $records );
if( $num_records > 0 ){
	?>
	<h3><?php _e( 'List of Facebook Pages Connections', 'wp-to-fb' );?></h3>
	<table class="widefat post fixed" cellspacing="0">
		<thead>
			<tr>
				<th class="manage-column column-title" id="wptofb_title" scope="col"><?php _e( 'Title' );?></th>
				<th class="manage-column column-url" id="wptofb_url" scope="col"><?php _e( 'Canvas Page', 'wp-to-fb' );?></th>
				<th class="manage-column column-author" id="wptofb_author" scope="col"><?php _e( 'Template' );?></th>
				<th class="manage-column column-author" id="wptofb_date" scope="col"><?php _e( 'Author' );?></th>
				<th class="manage-column column-date" id="wptofb_template" scope="col"><?php _e( 'Date' );?></th>
			</tr>
		</thead>
	<?php

	$count = true;
	foreach( $records as $record ){
		if( $count ){
			$pre = "alternate ";
		}else{
			$pre = "";
		}
		$count = !$count;

		$record_id = $record->id;
		$record_title = $record->title;
		$record_author = get_userdata( $record->created_by );
		$record_date = mysql2date( __( 'Y/m/d' ), $record->created );
		$template = $record->tmpl;
		$delete_url = add_query_arg( array( 'action'=> 'delete','id'=> $record_id ) );
		$deletelink = wp_nonce_url( $delete_url, 'wptofb-delete_connid' . $record_id );
		$edit_url = add_query_arg( array( 'page'=> 'edit-wptofb', 'action'=> 'edit','id'=> $record_id ) );
		$editlink = wp_nonce_url( $edit_url, 'wptofb-edit_connid' . $record_id );
		
		?>
		
		<tbody>
			<tr id="rec-<?php echo $record_id;?>" class="<?php echo $pre;?>iedit">
				<td class="column-title"><strong> <a class="row-title"
					title="<?php echo sprintf( __( 'Edit \'%s\'', 'wp-to-fb' ), $record_title );?>"
					href="<?php echo $editlink;?>"><?php echo $record_title;?></a></strong>
				<div class="row-actions"><span class="edit"><a title="<?php _e( 'Edit this item' );?>"
					href="<?php echo $editlink;?>"><?php _e ( 'Edit' );?></a></span> <span class="trash"><a
					title="<?php _e( 'Delete this item permanently' );?>" class="submitdelete"
					href="<?php echo $deletelink;?>"><?php _e( 'Delete Permanently' );?></a></span></div>
				</td>
				<td class="url column-url"><?php echo site_url() . "/?wptofb=" . $record_id;?></td>
				<td class="column-template"><?php echo $template;?></td>
				<td class="author column-author"><?php echo $record_author->user_login;?></td>
				<td class="date column-date"><?php echo $record_date;?></td>				
			</tr>
		</tbody>
		
	<?php
	};?>

	</table>
	
	<?php
}else{
	echo '<p>';
	_e( 'No records found' , 'wp-to-fb' );
	echo '</p>';
}
?>
	</form>
</div>