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


require_once(RPBCALENDAR_ABSPATH . 'helpers/loader.php');


/**
 * Register the 'event' type of post in the Wordpress database.
 *
 * This class is not publically constructible. Call the static method `register()`
 * to trigger the registration operations.
 */
class RPBCalendarEventClass
{
	private static $registered = false;

	private $columnView;
	private $editionBoxView;


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
		// Register the new type of post
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
			'register_meta_box_cb' => array($this, 'registerMetaBoxCallback'),
		));

		// Callback for post saving
		add_action('save_post', array($this, 'save'));

		// Filters for the definition of the columns in the backend interface.
		add_filter('manage_rpbcalendar_event_posts_columns', array($this, 'registerEditionColumns'));
		add_action('manage_rpbcalendar_event_posts_custom_column', array($this, 'printEditionColumn'), 10, 2);
	}


	/**
	 * Callback for the edition boxes.
	 */
	public function registerMetaBoxCallback()
	{
		require_once(RPBCALENDAR_ABSPATH . 'controllers/editionbox.php');
		$controller = new RPBCalendarControllerEditionBox();
		$controller->run();
	}


	/**
	 * Customize the columns in the "list of events" page in the backend interface.
	 *
	 * @param array $columns Default columns.
	 * @return array
	 */
	public function registerEditionColumns($columns)
	{
		// New set of columns.
		return array(
			'cb'         => $columns['cb'      ],
			'title'      => $columns['title'   ],
			'event_date' => __('Date', 'rpbcalendar'),
			'author'     => $columns['author'  ],
			'comments'   => $columns['comments'],
			'date'       => __('State', 'rpbcalendar')
		);
	}


	/**
	 * TODO
	 */
	public function printEditionColumn($column, $eventID)
	{
		echo 'TODO col=' . $column . ' ev='.$eventID;
	}


	/**
	 * Save the meta-information associated to an event.
	 */
	public function save($postID)
	{
		$model = RPBCalendarHelperLoader::loadModel('UpdateEvent', $postID);
		$model->processRequest();
	}
}
