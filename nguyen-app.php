<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://baonguyenyam.github.io/cv
 * @since             1.0.0
 * @package           LIFT_CHAT
 *
 * @wordpress-plugin
 * Plugin Name:       @LIFT Creations - LIFT Chat
 * Plugin URI:        https://baonguyenyam.github.io/cv
 * Description:       A Better Way to Connect With Customers
 * Version:           1.2.0
 * Author:            Nguyen Pham
 * Author URI:        https://baonguyenyam.github.io/cv
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lift-chat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LIFT_CHAT_DOMAIN', 'lift-chat' );
define( 'LIFT_CHAT_NICENAME', 'LIFT Chat' );
define( 'LIFT_CHAT_PREFIX', 'lift_chat' );
define( 'LIFT_CHAT_VERSION', '1.0.6' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function activate_liftChat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	LIFT_Chat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function deactivate_liftChat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	LIFT_Chat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_liftChat' );
register_deactivation_hook( __FILE__, 'deactivate_liftChat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_liftChat() {

	$plugin = new LIFT_Chat();
	$plugin->run();

}
run_liftChat();
