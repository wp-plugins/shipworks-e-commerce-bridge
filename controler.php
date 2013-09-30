<?php
/*
Plugin Name: ShipWorks Bridge
Plugin URI: http://www.advanced-creation.com 
Description: ShipWorks for Wordpress build a bridge between your E-Commerce sites on Wordpress (such as WooCommerce) and ShipWorks.
Version: 2.4.3
Author: Advanced Creation
Author URI: http://www.advanced-creation.com
License: GPL2
*/

if (!defined('SHIPWORKSWORDPRESS_VERSION')) define('SHIPWORKSWORDPRESS_VERSION','1.0.0');
if (!defined('SHIPWORKSWORDPRESS_HOME')) define('SHIPWORKSWORDPRESS_HOME','http://www.advanced-creation.com');
if (!defined('PLUGIN_PATH_SHIPWORKSWORDPRESS')) define('PLUGIN_PATH_SHIPWORKSWORDPRESS', plugin_dir_path( __FILE__ )); 
if (!defined('PLUGIN_URL_SHIPWORKSWORDPRESS')) define('PLUGIN_URL_SHIPWORKSWORDPRESS', plugin_dir_url( __FILE__ ));
// Attention ce chemin renvoi au dossier de tous les plugins
$i = strlen( PLUGIN_PATH_SHIPWORKSWORDPRESS ) - 2 ;
while ( substr( PLUGIN_PATH_SHIPWORKSWORDPRESS, $i, 1 ) != '/' ) {
	$i--;
}
define('PLUGINS_PATH', substr( PLUGIN_PATH_SHIPWORKSWORDPRESS, 0, $i ) );
if (!defined('THEMES_PATH')) define('THEMES_PATH', get_theme_root());
if (!defined('ROOT_URL')) define('ROOT_URL', get_option('siteurl') . '/');
if (!defined('SHIPWORKSWORDPRESS_URL')) define('SHIPWORKSWORDPRESS_URL',ROOT_URL . 'wp-admin/admin.php?page=shipworks-wordpress');


/* Les hook pour l'activation du plugin */

register_activation_hook( __FILE__, 'db_shipworks_install' );
register_activation_hook( __FILE__, 'db_shipworks_init' );

/* Création de la base de donnée */

function db_shipworks_install() {
   global $wpdb;

   $table = $wpdb->prefix . "shipworks_bridge";
      
   $sql = "CREATE TABLE IF NOT EXISTS $table (
				  id int(1) NOT NULL AUTO_INCREMENT,
				  username_shipworks varchar(255) NOT NULL,
				  password_shipworks varchar(255) NOT NULL,
				  company_name varchar(255) NOT NULL,
				  street1 varchar(255) NOT NULL,
				  street2 varchar(255) NOT NULL,
				  street3 varchar(255) NOT NULL,
				  city varchar(255) NOT NULL,
				  state varchar(50) NOT NULL,
				  zip varchar(50) NOT NULL,
				  country varchar(255) NOT NULL,
				  phone varchar(50) NOT NULL,
				  support varchar(255) NOT NULL,
				  PRIMARY KEY (id)
				);";
		
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

function db_shipworks_init() {
	 global $wpdb;
	 $table = $wpdb->prefix . "shipworks_bridge";
  	 $rows_affected = $wpdb->insert( $table, array( 
		 'username_shipworks' => '', 
		 'password_shipworks' => '', 
		 'company_name' => '',
		 'street1' => '', 
		 'street2' => '', 
		 'street3' => '', 
		 'city' => '',
		 'state' => '',
		 'zip' => '', 
		 'country' => '',
		 'phone' => '',
		 'support' => '',
		 ) 
	 );	
}

/* Ajout du menu et de la page */

// Ajout de la fonction au menu
add_action('admin_menu', 'shipworks_admin_menu');


//Définition du menu dans les options
function shipworks_admin_menu() {
		add_menu_page(__('Shipworks WordPress'), __('Shipworks WP'),'manage_options','shipworks-wordpress', 'shipworks_admin',PLUGIN_URL_SHIPWORKSWORDPRESS.'img/logo.png');
		add_submenu_page('shipworks-wordpress',__('Set Up | Shipworks WordPress'),__('Set Up'),'manage_options','set-up','shipworks_set_up');
		add_submenu_page('shipworks-wordpress',__('Subscription | Shipworks WordPress'),__('Subscription'),'manage_options','subscription','shipworks_subscription');
		global $submenu;
		if (isset($submenu['shipworks-wordpress'])) {
			$submenu['shipworks-wordpress'][0][0] =	__('Settings');
		}
}

// Contenu de la page à afficher
function shipworks_admin() {  
   		// On affiche la page de base
		require_once('control/controlAdmin.php');
		wp_enqueue_style( 'ShipworksCss', plugins_url( 'ShipWorksBridge/css/admin.css' , dirname(__FILE__) ));
}

function shipworks_set_up() {
		require_once('control/controlSetUp.php');
		wp_enqueue_style( 'ShipworksCss', plugins_url( 'ShipWorksBridge/css/admin.css' , dirname(__FILE__) ));
}

function shipworks_subscription() {
		require_once('control/controlSubscription.php');
		wp_enqueue_style( 'ShipworksCss', plugins_url( 'ShipWorksBridge/css/admin.css' , dirname(__FILE__) ));
		wp_enqueue_style( 'ModalCss', plugins_url( 'ShipWorksBridge/css/bootstrap.min.css' , dirname(__FILE__) ));
		wp_enqueue_script( 'ModalJs', plugins_url( 'ShipWorksBridge/css/bootstrap.min.js' , dirname(__FILE__) ) );
}

/****************************************/

/************* Partie Shipworks *********/

/****************************************/

// On veut savoir si l'appel a été fait par Shipworks

if ( isset($_POST['action']) && isset($_POST['username']) && isset($_POST['password']) ) {
	include_once('connection.php');
	exit;
}

/*  Copyright 2013  Olivier Volatier  (email : olivier@advanced-creation.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
