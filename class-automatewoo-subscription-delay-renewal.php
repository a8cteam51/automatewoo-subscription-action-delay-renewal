<?php
namespace to51\AW_Action;

class AutomateWoo_Subscription_Delay_Renewal {
	public static function init() {
		add_filter( 'automatewoo/actions', array( __CLASS__, 'register_action' ) );
	}

	public static function register_action( $actions ) {
		require_once __DIR__ . '/includes/class-action-subscription-delay-renewal.php';

		$actions['to51_subscription_delay_renewal'] = Action_Subscription_Delay_renewal::class;
		return $actions;
	}
}

AutomateWoo_Subscription_Delay_Renewal::init();
