<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/codestickdev/promocodes
 * @since      1.0.1
 *
 * @package    Promocodes
 * @subpackage Promocodes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Promocodes
 * @subpackage Promocodes/admin
 * @author     Piotr Gajewski <piotrdevv@gmail.om>
 */
class Promocodes_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_menu', array($this, 'addPluginAdminMenu'), 9);
		add_action('admin_init', array($this, 'registerAndBuildFields'));

		function sample_admin_notice__promocode($status) {
			if($status){
				$type = 'success';
				$notice = 'Kod został pomyślnie dodany.';
	
				if($status == 'error'){
					$type = 'error';
					$notice = 'Wystąpił błąd. Skontaktuj się z administratorem.';
				}else if($status == 'delete_success'){
					$type = 'success';
					$notice = 'Kod został pomyślnie usunięty';
				}
	
				?>
				<div class="notice notice-<?php echo $type ?> is-dismissible">
					<p><?php echo $notice; ?></p>
				</div>
				<?php
			}
		}
		add_action('admin_notices', 'sample_admin_notice__promocode', 10, 3);

		if(isset($_GET['status'])){
			$status = $_GET['status'];
			sample_admin_notice__promocode($status);
		}

		function get_promocodes_table(){
			global $wpdb;
			$table_name = $wpdb->prefix . 'promocodes';
			$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );
	
			foreach ($retrieve_data as $key=>$data){ ?>
			<tr>
				<td><?php echo $key + 1; ?></td>
				<td class="bold"><?php echo $data->name; ?></td>
				<td><?php echo $data->description; ?></td>
				<!-- <td><?php // echo $data->category; ?></td>
				<td><?php // echo $data->type; ?></td> -->
				<td><button type="submit" name="remove" value="<?php echo $data->id; ?>">Usuń</button></td>
			</tr>
			<?php }
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Promocodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Promocodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/promocodes-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Promocodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Promocodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/promocodes-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function addPluginAdminMenu() {
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(  $this->plugin_name, 'Promocodes', 'administrator', $this->plugin_name, array( $this, 'displayPluginAdminDashboard' ), 'dashicons-chart-area', 26 );
	}
	public function displayPluginAdminDashboard() {
		require_once 'partials/' . $this->plugin_name . '-admin-display.php';
  	}

	public function registerAndBuildFields() {
		/**
		 * First, we add_settings_section. This is necessary since all future settings must belong to one.
		 * Second, add_settings_field
		 * Third, register_setting
		 */
		add_settings_section(
			// ID used to identify this section and with which to register options
			'plugin_name_general_section', 
			// Title to be displayed on the administration page
			'',  
			// Callback used to render the description of the section
			array( $this, 'plugin_name_display_general_account' ),    
			// Page on which to add this section of options
			'plugin_name_general_settings'                   
		);
		unset($args);
		$args = array (
			'type'      => 'input',
			'subtype'   => 'text',
			'id'    	=> 'plugin_name_example_setting',
			'name'      => 'plugin_name_example_setting',
			'required' 	=> 'true',
			'get_options_list' => '',
			'value_type'=>'normal',
			'wp_data' 	=> 'option'
		);
		add_settings_field(
			'plugin_name_example_setting',
			'Example Setting',
			array( $this, 'plugin_name_render_settings_field' ),
			'plugin_name_general_settings',
			'plugin_name_general_section',
			$args
		);
		register_setting(
			'plugin_name_general_settings',
			'plugin_name_example_setting'
		);
	}

	public function plugin_name_display_general_account() {
		echo '<p>These settings apply to all Plugin Name functionality.</p>';
	}
}
