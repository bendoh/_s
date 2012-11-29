<?php

/*
 * VIP functions, per http://lobby.vip.wordpress.com/getting-started/development-environment/
 */

// Init WP.com VIP environment
require_once( WP_CONTENT_DIR . '/themes/vip/plugins/vip-init.php' );

// VIP Plugins
if ( function_exists( 'wpcom_vip_load_plugin' ) ) {
	//wpcom_vip_load_plugin( 'facebook' );
}