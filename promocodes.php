<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/codestickdev/promocodes
 * @since             1.0.1
 * @package           Promocodes
 *
 * @wordpress-plugin
 * Plugin Name:       Promocodes
 * Plugin URI:        https://github.com/codestickdev/promocodes
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.1
 * Author:            Piotr Gajewski
 * Author URI:        https://codestick.pl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       promocodes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PROMOCODES_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-promocodes-activator.php
 */
function activate_promocodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-promocodes-activator.php';
	Promocodes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-promocodes-deactivator.php
 */
function deactivate_promocodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-promocodes-deactivator.php';
	Promocodes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_promocodes' );
register_deactivation_hook( __FILE__, 'deactivate_promocodes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-promocodes.php';

/**
 * Require the WP List Table class
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Customers_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
			'plural' => __( 'Customers', 'sp' ), //plural name of the listed records
			'ajax' => false //should this table support ajax?
		]);
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_promocodes() {

	$plugin = new Promocodes();
	$plugin->run();

}
run_promocodes();

add_action( 'admin_post_promocode_actions', 'prefix_admin_promocode_actions' );
add_action( 'admin_post_nopriv_promocode_actions', 'prefix_admin_add_foobar' );

function prefix_admin_promocode_actions() {
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb;
	$tablename = $wpdb->prefix . 'promocodes'; 
	$main_sql_create = 'CREATE TABLE ' . $tablename . ' (id INT AUTO_INCREMENT, name CHAR (50), description TEXT (100), category TEXT (50), type TEXT (50), PRIMARY KEY (id));'; 
	maybe_create_table($tablename, $main_sql_create);

    $name = $_POST['codeName'];
    $desc = $_POST['codeDesc'];
    // $category = $_POST['codeCategory'];
    // $type = $_POST['codeType'];

	if(isset($_POST['remove'])) {
		$id = $_POST['remove'];

		$wpdb->delete(
			$tablename,
			array( 
				'id' => $id,
			)
		);
		if($wpdb->last_error !== ''){
			$wpdb->print_error();
		}else{
			header('Location: ' . get_admin_url() . 'admin.php?page=promocodes&status=delete_success');
			die();
		}
	}else{
		$wpdb->insert( 
			$tablename, 
			array( 
				'name' => $name, 
				'description' => $desc, 
				// 'category' => $category, 
				// 'type' => $type, 
			)
		);
		if($wpdb->last_error !== ''){
			$wpdb->print_error();
		}else{
			header('Location: ' . get_admin_url() . 'admin.php?page=promocodes&status=success');
			die();
		}
	}
}

function get_code_info($value){
	$code = $value;
	$json = file_get_contents('https://app.psibufet.pl/api/order/couponcode/' . $code);

	return $json;
}

function check_promocode(){
	if(!is_admin()){
		global $wpdb;

    	$get_slug = $_SERVER['REQUEST_URI'];
		$slug = str_replace('/', '', $get_slug);
		$code = '';
		
		$table_name = $wpdb->prefix . 'promocodes';
		$retrieve_data = $wpdb->get_results("SELECT * FROM $table_name");

		$exist = false;

		foreach($retrieve_data as $key => $data){
			if($slug == $data->name){
				$code = $data->name;
				$exist = true;
			}
		}

		if($exist == true){
			$data = json_decode(get_code_info($code));
		
			if ($data->purpose == "CLIENT"){
				header('Location: ' . get_home_url() . '/?code=' . $code . '&utm_source=MGM&utm_medium=referral_link&utm_campaign=' . $code . '&amount=' . $data->amount . '&type=' . $data->type);
			}else if ($data->purpose == "PARTNER"){
				header('Location: ' . get_home_url() . '/?code=' . $code . '&utm_source=partner&utm_medium=referral_link&utm_campaign=' . $code . '&amount=' . $data->amount . '&type=' . $data->type);
			}else if ($data->purpose == "INFLUENCER"){
				header('Location: ' . get_home_url() . '/?code=' . $code . '&utm_source=influencer&utm_medium=referral_link&utm_campaign=' . $code . '&amount=' . $data->amount . '&type=' . $data->type);
			}else if ($data->purpose == "EVENT"){
				header('Location: ' . get_home_url() . '/?code=' . $code . '&utm_source=event&utm_medium=referral_link&utm_campaign=' . $code . '&amount=' . $data->amount . '&type=' . $data->type);
			}else if ($data->purpose == "VET"){
				header('Location: ' . get_home_url() . '/?code=' . $code . '&utm_source=vet&utm_medium=referral_link&utm_campaign=' . $code . '&amount=' . $data->amount . '&type=' . $data->type);
			}else{
				header('Location: ' . get_home_url() . '/?code=' . $code . '&amount=' . $data->amount . '&type=' . $data->type);
			}
			die();
		}else{
			return;
		}
	}
}
add_action('init', 'check_promocode');