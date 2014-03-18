<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Calendar, a Wordpress plugin.                  *
 *    Copyright (C) 2014  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


/**
 * Register the 'event' type of post in the Wordpress database.
 *
 * This class is not publically constructible. Call the static method `register()`
 * to trigger the registration operations.
 */
class RPBCalendarEventClass
{
	private static $registered = false;


	/**
	 * Function to call externally to register the class. Must be called only once.
	 */
	public static function register()
	{
		if(self::$registered) {
			return;
		}
		new RPBCalendarEventClass();
		self::$registered = true;
	}


	/**
	 * Constructor.
	 */
	private function __construct()
	{
		register_post_type('rpbcalendar_event', array(
			'labels' => array(
				'name'               => __('Events'                 , 'rpbcalendar'),
				'singular_name'      => __('Event'                  , 'rpbcalendar'),
				'add_new_item'       => __('Add new event'          , 'rpbcalendar'),
				'edit_item'          => __('Edit event'             , 'rpbcalendar'),
				'new_item'           => __('New event'              , 'rpbcalendar'),
				'view_item'          => __('View event'             , 'rpbcalendar'),
				'search_items'       => __('Search events'          , 'rpbcalendar'),
				'not_found'          => __('No event found'         , 'rpbcalendar'),
				'not_found_in_trash' => __('No event found in trash', 'rpbcalendar')
			),
			'public'       => true,
			'menu_icon'    => null, // TODO: icon for the event post type
			'hierarchical' => false,
			'supports'     => array('title', 'editor', 'author', 'comments'),
			'rewrite'      => array('slug' => 'event'),
			'query_var'    => 'event',
			'register_meta_box_cb' => array($this, 'registerMetaBoxes'),
		));
	}


	/**
	 * Register the "boxes" that show the meta-information related to an event
	 * (the date, time,link, etc...) in the backend interface.
	 */
	public function registerMetaBoxes()
	{
		// Link
		add_meta_box(
			'rpbcalendar-admin-eventLink',
			__('Link', 'rpbcalendar'),
			array($this, 'printMetaLink'),
			'rpbcalendar_event',
			'normal',
			'high'
		);

		// Date/time/notes
		add_meta_box(
			'rpbcalendar-admin-eventDateTime',
			__('Date/time', 'rpbcalendar'),
			array($this, 'printMetaDateTime'),
			'rpbcalendar_event',
			'side',
			'high'
		);
	}


	/**
	 * TODO: printMetaLink
	 */
	public function printMetaLink($event)
	{
		echo 'Bonjour le monde';
	}


	/**
	 * TODO: printMetaDateTime
	 */
	public function printMetaDateTime($event)
	{
		echo 'Bonjour le monde';
	}
}
