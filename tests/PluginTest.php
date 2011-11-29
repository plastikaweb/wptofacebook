<?php
/**
 * class Test
 *
 * Basic library for run-time tests.
 */
if( !class_exists( 'PluginTest' ) ) :
class PluginTest{

	public static $errors = array(); // Any errors thrown.

	/**
	 * @return unknown_type
	 */
	public static function print_notices(){
		if ( !empty(self::$errors) ){
			$error_items = '';
			foreach ( self::$errors as $e ){
				$error_items .= "<li>$e</li>";
			}
			exit('<div id="my-plugin-error" class="error">'
			."<ul style='margin-left:30px;'>$error_items</ul>"
			.'</div>');
		}
	}

	/**
	 * min_php_version
	 *
	 * Test that your PHP version is at least that of the $min_php_
	 version.
	 * @param $min_php_version string representing the minimum
	 required version of PHP, e.g. '5.3.2'
	 * @param $plugin_name string Name of the plugin for messaging
	 purposes.
	 * @return none Exit with messaging if PHP version is too old.
	 */
	public static function test_min_php_version( $min_php_version, $plugin_name ){
		$exit_msg = sprintf( __( "The %s plugin requires PHP %s or newer. Contact your system administrator about updating your version of PHP.", 'wp-to-fb' ), $plugin_name, $min_php_version );
		if( version_compare( phpversion(), $min_php_version , '<' ) ){
			self::$errors[] = $exit_msg;
		}
	}

	/**
	 * Tests that the current version of WP is greater than $ver.
	 *
	 * @param string $ver the version of WordPress your plugin requires
	 in order to work, e.g. '3.0.1'
	 * @return none Registers an error in the self::$errors array.
	 */
	public static function test_min_wp_version( $ver, $plugin_name ){
		global $wp_version;
		$exit_msg = sprintf( __( "The %s plugin requires WordPress %s or newer. <a href='http://codex.wordpress.org/Upgrading_WordPress'>Please update!</a>", 'wp-to-fb' ), $plugin_name, $ver );
		if (version_compare($wp_version,$ver,'<')){
			self::$errors[] = $exit_msg;
		}
	}

	// INPUT: minimum req'd version of MySQL
	public static function test_min_mysql_version( $ver, $plugin_name ){
		global $wpdb;
		$exit_msg = sprintf( __( "The %s plugin requires MySQL %s or newer. Contact your system administrator about upgrading", 'wp-to-fb' ), $plugin_name, $ver );
		$result = $wpdb->get_results( 'SELECT VERSION() as ver' );
		if ( version_compare( $result[0]->ver, $ver, '<') ){
			self::$errors[] = $exit_msg;
		}
	}
	
	
}
endif;