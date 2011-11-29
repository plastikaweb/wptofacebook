<?php
/**
 * @author Carlos Matheu
 * class WpToFb
 *
 * Adds basic functionality to the plugin
 *
 */
class WpToFb{

	/**
	 * @var string
	 */
	const PLUGIN_NAME = 'WpToFb';

	/**
	 * @var string
	 */
	const MIN_PHP_VERSION = '5.2';

	/**
	 * @var string
	 */
	const MIN_MYSQL_VERSION = '5.0';

	/**
	 * @var string
	 */
	const MIN_WP_VERSION = '3.0';

	/**
	 * @var string
	 */
	const TABLE_VERSION = '1.0';
	
	/**
	 * Basic system tests
	 */
	public static function wptofb_tests(){
		PluginTest::test_min_php_version( self::MIN_PHP_VERSION, self::PLUGIN_NAME );
		PluginTest::test_min_mysql_version( self::MIN_MYSQL_VERSION, self::PLUGIN_NAME );
		PluginTest::test_min_wp_version( self::MIN_WP_VERSION, self::PLUGIN_NAME );

	}
	
	/**
	 * The main function for this plugin, similar to __construct()
	 */
	public static function wptofb_init(){
		
		
		if( self::_wptofb_is_searchable_page() ){
			
			load_plugin_textdomain( 'wp-to-fb', PLUGINDIR . '/wp-to-fb/lang', 'wp-to-fb/lang' );
			
			$url = plugins_url( 'js/wp-to-fb.js', dirname( __FILE__ ) );
			wp_enqueue_script( 'wptofb-script', $url, array( 'jquery','jquery-ui-core','jquery-ui-sortable' ) );

			$src = plugins_url( 'css/wp-to-fb.css', dirname( __FILE__ ) );

			wp_register_style( 'wptofb-css', $src );
			wp_enqueue_style( 'wptofb-css' );
			
			

		}


	}

	/**
	 * @return unknown_type
	 */
	public static function wptofb_activate(){

		//add options for the plugin on register
		$wptofbOptions = get_option( 'wptofb_options_plastikaweb' );

		if( !empty($wptofbOptions ) ){
			update_option( 'wptofb_options_plastikaweb', self::TABLE_VERSION );
		}else{
			add_option( 'wptofb_options_plastikaweb', self::TABLE_VERSION );
		}

		//create table wwptofb
		global $wpdb;

		$table_name = $wpdb->prefix . "wptofb";

		if( $wpdb->get_var( 'SHOW TABLES LIKE '. $table_name ) != $table_name ){

			$sql = "CREATE TABLE " . $table_name . "(
					id int(11) unsigned NOT NULL AUTO_INCREMENT,
			  		title varchar(255) NOT NULL DEFAULT '',
			  		introtext mediumtext,
			  		outrotext mediumtext,
			  		hide_for_nofans smallint(6) NOT NULL DEFAULT '1',
			  		nofans mediumtext,
			  		active smallint(6) NOT NULL DEFAULT '1',
			  		created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  		created_by int(11) unsigned NOT NULL DEFAULT '0',
			  		contents text NOT NULL,
			  		tpl varchar(255) NOT NULL DEFAULT '',
			  		fb_app_id varchar(255) NOT NULL DEFAULT '',
			  		fb_app_secret varchar(255) NOT NULL DEFAULT '',
			  		PRIMARY KEY (id)
			  		) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

		}
	}

	public static function wptofb_deactivate(){
		delete_option( 'wptofb_options_plastikaweb' );
	}



	static function wptofb_editor_admin_init() {


		add_filter("mce_buttons", "WpToFb::base_extended_editor_mce_buttons", 10 );
		add_filter("mce_buttons_2", "WpToFb::base_extended_editor_mce_buttons_2", 20);
		add_action( 'admin_print_footer_scripts', 'wp_tiny_mce_preload_dialogs', 30 );
	}

	static function base_extended_editor_mce_buttons($buttons) {
		// The settings are returned in this array. Customize to suite your needs.
		return array(
			'formatselect', 'bold', 'italic', 'bullist', 'numlist', 'link', 'unlink', 'blockquote', 'outdent', 'indent', 'charmap', 'removeformat', 'underline', 'justifyfull', 'forecolor', 'separator', 'pastetext', 'pasteword', 'separator', 'media', 'undo', 'redo'
			);

	}

	static function base_extended_editor_mce_buttons_2($buttons) {
		return array();
	}



	static function wptofb_editor_admin_head() {

		wp_tiny_mce(false, array(
			"editor_selector" => "wptofb_editor_class",
			"convert_urls" => false
		) );

	}
	/**
	 * _is_searchable_page
	 *
	 * Any page that's not in the WP admin area is considered searchable.
	 * @return boolean Simple true/false as to whether the current
	 page is searchable.
	 */
	private static function _wptofb_is_searchable_page(){
		if( is_admin() ){
			return true;
		}else{
			return false;
		}
	}




	/**
	 * Controller that generates admin page
	 */
	static function wptofb_generate_admin_page(){
		include( 'list_page.php' );
	}

	/**
	 * Controller that generates new wptofb page
	 */
	static function wptofb_generate_new_page(){
		include( 'edit_page.php' );
	}

	/**
	 * Adds a menu item inside the WordPress admin
	 */
	static function wptofb_add_menu_item(){
		add_menu_page(
		'WpToFacebook Plugin', 
		'WpToFacebook', 
		'manage_options', 
		'wptofb' ,
		'WpToFb::wptofb_generate_admin_page', 
		plugins_url( 'images/pw.png', dirname( __FILE__ ) )
		);

		add_submenu_page(
		'wptofb',									//Menu page to attach to
		'New WpToFacebook Connection',		//page title
		__('New Connection', 'wp-to-fb'),			//menu title
		'manage_options',							//permissions
		'edit-wptofb',								//page-name used in the url
		'WpToFb::wptofb_generate_new_page'			//callback function	
		);
	}

	static function wptofb_select_all(){
		global $wpdb;

		$table_name = $wpdb->prefix . "wptofb";
		$query = "SELECT id,title,created,created_by,tpl FROM " . $table_name . " ORDER BY created DESC";

		$records = $wpdb->get_results( $query );
		$wpdb->flush();
		return $records;
	}

	static function wptofb_select_taxonomy( $types ){
		global $wpdb;



		$query = "SELECT DISTINCT wt.name, wt.term_id, wp.post_type, wtt.taxonomy FROM $wpdb->terms wt
					LEFT JOIN $wpdb->term_taxonomy wtt ON wt.term_id  = wtt.term_id
					LEFT JOIN $wpdb->term_relationships wtr ON wtt.
					term_taxonomy_id = wtr.term_taxonomy_id
					LEFT JOIN $wpdb->posts wp ON wtr.object_id = wp.ID 
					WHERE ( wtt.taxonomy <> 'link_category' AND wtt.taxonomy <> 'nav_menu' )";


		if($types && count( $types ) > 0 ){

			$subs_ar = array();

			foreach ($types  as $name => $value ) {
				$subs_ar[] = "wp.post_type = '$name'";
			}

			$subquery = ' AND ( ' . implode( " OR ", $subs_ar ) . ' )';
		}

		$query .= $subquery;

		$results = $wpdb->get_results( $wpdb->prepare( $query ) );

		return  $results ;


	}

	static function wptofb_delete( $id ){
		global $wpdb;

		$table_name = $wpdb->prefix . "wptofb";
		$wpdb->query( "DELETE FROM $table_name WHERE id = '$id'" );
		$wpdb->flush();
	}

	static function wptofb_select_record( $id ){
		//select del registre
		global $wpdb;

		$table_name = $wpdb->prefix . "wptofb";

		$query = "SELECT id, title, introtext, outrotext, hide_for_nofans, nofans, contents, tpl, fb_app_id, fb_app_secret FROM " . $table_name . " WHERE id = %d";
		$safe_query = $wpdb->prepare( $query, $id );
		$data = $wpdb->get_row( $safe_query );


		return $data;
	}

	static function wptofb_insert( $post ){

		global $current_user;
		global $wpdb;

		$table_name = $wpdb->prefix . "wptofb";

		$post_data = self::_wptofb_format_post_data( $post );
		$post_data[ 'created_by' ] = get_current_user_id();

		$inserted = $wpdb->insert($table_name, $post_data, array( '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d' ) );

		$wpdb->flush();

		if( $inserted ){
			$return[ 'exit' ] = 1;
			$return[ 'message' ] = '<div class="updated"><p><strong>Settings saved.</strong></p></div>';
			$return[ 'new_id' ] = $wpdb->insert_id;

		}else{
			$return[ 'exit' ] = 0;
			$return[ 'message '] = '<div class="error"><p><strong>There was a database problem inserting this entry.</strong></p></div>';
		}

		return $return;
	}

	static function wptofb_update( $post ){

		global $wpdb;

		$table_name = $wpdb->prefix . "wptofb";

		$post_data = self::_wptofb_format_post_data( $post );


		$updated = $wpdb->update( $table_name, $post_data , array( 'id' => $post[ 'wptofb_id' ] ), array( '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s' ), array( '%d' ));
		//$wpdb->print_error("Error ");
		$wpdb->flush();

		if( $updated ){
			$str = '<div class="updated"><p><strong>Settings saved.</strong></p></div>';

		}else{
			$str = '<div class="updated"><p><strong>No changes.</strong></p></div>';
		}
			
		return $str;
	}

	private static function in_array_multi( $needle, $haystack ) {
		if (!is_array($haystack)) return false;
		
		while (list($key, $value) = each($haystack)) {	
		if (is_array($value) && self::in_array_multi($needle, $value) || $value === $needle && $key == 'terms' ) {
					
				return true;
					
						
			}			
		}
		return false;
	}

	private static function _wptofb_format_post_data( $post ){

		$post_types_ar = array();
		$contents_ar = array();
		$post_taxonomy_ar = array( 'relation' => 'OR' );

		//$wpdb->show_errors();

		foreach( $post as $name => $value){

			$name = substr( $name, strpos( $name, '_' ) + 1 );
			$value = trim( $value );

			if( strpos( $name, 'types_of_content' ) !== false && strpos( $name, $post[ 'wptofb_custom' ] ) !== false ){
				$post_types_ar[] = $value;

			}else if( strpos( $name, 'taxonomy' ) !== false ){

				$ini = strpos( $name,'_',7 ) + 1;
				$last = strrpos( $name,'_' );
				$taxonomy = substr( $name, $ini, $last - $ini );
					
				$post_taxonomy_ar[] = array( 'taxonomy' => $taxonomy, 'field' => 'id', 'terms' => $value );

			}else if( $name == 'custom' || $name == 'max_posts' || $name == 'order_by' || $name == 'order' ){

				$contents_ar[ $name ] = $value;

			}else if( $name == 'ids_conns' ){

				if( $post[ 'wptofb_custom' ] == 'manual' && $value != '' ){

					$contents_ar[ $name ] = explode(",", $value );


				}else{
					$contents_ar[ $name ] = array();
				}

			}else if( $name == 'title' || $name == 'fb_app_id' || $name == 'fb_app_secret' ){

				$post_data[ $name ] = trim( strip_tags( $value ) );

			}else if( $name == 'introtext' || $name == 'outrotext' || $name == 'hide_for_nofans' || $name == 'nofans' || $name == 'tpl' ){
					
				$post_data[ $name ] = stripslashes( $value );

			}

		}
		$contents_ar[ 'types_of_content' ] = $post_types_ar;
		$contents_ar[ 'taxonomy_ids' ] = $post_taxonomy_ar;
		
		$post_data[ 'contents' ] = serialize( $contents_ar );

		return $post_data;
	}


	static function wptofb_get_available_post_types(){
		$args=array( 'public'   => true );
		$post_types=get_post_types( $args );

		//delete attachment
		if( $post_types[ 'attachment' ] ):
		unset( $post_types[ 'attachment' ] );
		endif;

		return $post_types;
	}

	static function wptofb_get_available_posts( $types ){

		foreach ($types  as $name => $value ) {

			$post_types_shown[] = $name;
		}

		$args = array(
				'numberposts' => -1,
				'post_type' =>  $post_types_shown
		);

		$posts =  get_posts( $args );
		return $posts;
	}

	static function wptofb_get_single_post_data( $id ){
		global $wpdb;

		$sql = "SELECT ID, post_title, post_type FROM $wpdb->posts WHERE ID = %d AND post_status = 'publish'";

		$safesql = $wpdb->prepare( $sql, $id );
		$selected_post = $wpdb->get_row( $safesql );
		return $selected_post;
	}

	static function wptofb_get_maxposts_options(){
		$ar = array( __( 'all', 'wp-to-fb' ) =>-1, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9, '10'=>10, '15'=>15, '20'=>20, '25'=>25 );
		return $ar;
	}

	static function wptofb_get_orderby_options(){
		$ar = array( __( 'date', 'wp-to-fb' )=>'date', __( 'title', 'wp-to-fb' )=>'title', __( 'random', 'wp-to-fb' )=>'rand' );
		return $ar;
	}


	static function wptofb_get_templates() {

		$tmpl_ar = array();

		$directories = scandir( WP_PLUGIN_DIR . "/wp-to-fb/tpls/" );

		foreach( $directories as $dir ){
			if( is_dir( WP_PLUGIN_DIR . "/wp-to-fb/tpls/" . $dir ) && ( !in_array( $dir, array( '.','..' ) ) ) ) {
				$tmpl_ar[] = $dir;
			}
		}

		return $tmpl_ar;
	}

	// Template selection
	static function wptofb_template_redirect(){
		
		if( $_GET[ "wptofb" ] ){
			
			include( WP_PLUGIN_DIR . '/wp-to-fb/includes/tpl_redirect.php' );
			die();
		}
	}
}
