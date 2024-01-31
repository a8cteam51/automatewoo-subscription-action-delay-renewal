<?php

namespace to51\AW_Action;

use AutomateWoo\Action;
use AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Action_Subscription_Delay_Renewal extends Action {

	/**
	 * The data items required by the action.
	 *
	 * @var array
	 */
	public $required_data_items = array( 'subscription' );

	/**
	 * Flag to define whether variable products should be included in search results for the
	 * product select field.
	 *
	 * @var bool
	 */
	protected $allow_variable_products = true;

	/**
	 * Method to load the action's fields.
	 */
	public function load_fields() {
		$this->add_field( $this->get_delay_time() );
	}

	/**
	 * Get a number field for setting days
	 */
	protected function get_delay_time() {
		$delay_time = new Fields\Number();
		$delay_time->set_name( 'delay_time' );
		$delay_time->set_title( __( 'Add delay to next renewal (days)', 'automatewoo' ) );
		$delay_time->set_required();

		return $delay_time;
	}

	/**
	 * Method to set the action's admin properties.
	 *
	 * Admin properties include: title, group and description.
	 */
	protected function load_admin_details() {
		$this->title       = __( 'Delay Next Renewal', 'automatewoo' );
		$this->group       = __( 'Subscription', 'automatewoo' );
		$this->description = __( 'Add X days to the next renewal date', 'automatewoo' );
	}

	/**
	 * Run the action.
	 */
	public function run() {
		$subscription = $this->workflow->data_layer()->get_subscription();

		$delay_time = $this->get_option( 'delay_time', 0 );

		$old_next_payment           = $subscription->get_date( 'next_payment' );
		$old_next_payment_timestamp = strtotime( $old_next_payment );
		$new_next_payment_timestamp = $old_next_payment_timestamp + ( $delay_time * DAY_IN_SECONDS );
		$new_next_payment           = gmdate( 'Y-m-d H:i:s', $new_next_payment_timestamp );

		$subscription->update_dates( array( 'next_payment' => $new_next_payment ) );
		$subscription->save();

		$this->add_subscription_note( $subscription, $delay_time, $old_next_payment_timestamp, $new_next_payment_timestamp );

	}

	/**
	 * Adds a note to the given subscription indicating the product swap
	 *
	 * @param \WC_Subscription $subscription
	 * @param \WC_Product $swap_out_product
	 * @param \WC_Product $swap_in_product
	 */
	protected function add_subscription_note( $subscription, $delay_time, $old_next_payment_timestamp, $new_next_payment_timestamp ) {

		$old_next_payment = gmdate( 'Y-m-d', $old_next_payment_timestamp );
		$new_next_payment = gmdate( 'Y-m-d', $new_next_payment_timestamp );

		$note = sprintf(
			// translators: 1: name of the product to swap out, 2: ID of the product to swap out, 3: name of the product to swap in, 4: ID of the product to swap in
			__( 'AutomateWoo - Delayed next renewal by %1$s days. (%2$s -> %3$s)', 'automatewoo' ),
			$delay_time,
			$old_next_payment,
			$new_next_payment
		);
		$subscription->add_order_note( $note );
	}
}
