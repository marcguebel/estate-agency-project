<?php

/**
 * The plugin 
 *
 * @since             1.0.0
 * @package           Estate-Agency-Project
 *
 * @wordpress-plugin
 * Plugin Name:       Estate Agency Project
 * Description:       Estate Agency Project.
 * Version:           1.0.0
 * Author:            Guebel Marc
 * Author URI:        https://www.guebel-marc.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'PLUGIN_EAP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-estate-agency-project-activator.php';
	Estate_Agency_Project_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_plugin_name' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-estate-agency-project-deactivator.php';
	Estate_Agency_Project_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-estate-agency-project.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_estate_agency_project() {

	$plugin = new Estate_Agency_Project();
	$plugin->run();

}
run_estate_agency_project();