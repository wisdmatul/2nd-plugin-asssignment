<?php

/**
 * Fired during plugin activation
 *
 * @link       https://atul.com
 * @since      1.0.0
 *
 * @package    Email_Admin
 * @subpackage Email_Admin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Email_Admin
 * @subpackage Email_Admin/includes
 * @author     atul.com/atul-plugin <atul@atul.com>
 */
class Email_Admin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if( !wp_next_scheduled( 'send_daily_post_summary' ) )
    {
        wp_schedule_event(time(), 'daily', 'send_daily_post_summary');
    }
	}

}
