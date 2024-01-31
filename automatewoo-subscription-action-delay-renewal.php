<?php
/*
 * Plugin Name: AutomateWoo Subscription Action - Delay Renewal
 * Plugin URI:  https://github.com/a8cteam51/automatewoo-subscription-action-delay-renewal
 * Description: Extends the functionality of AutomateWoo with a custom action which allows you to delay the next subscription renewal
 * Version:     1.0.0
 * Author:      WP Special Projects
 * Author URI:  https://wpspecialprojects.wordpress.com/
 * License:     GPL v2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once dirname( __FILE__ ) . '/class-automatewoo-subscription-delay-renewal.php';
