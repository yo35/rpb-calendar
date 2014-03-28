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
		// Register the new type of post.
		register_post_type('rpbevent', array(
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

		// Register the associated taxonomy.
		register_taxonomy('rpbevent_category', 'rpbevent', array(
			'labels' => array(
				'name'          => __('Event categories'       , 'rpbcalendar'),
				'singular_name' => __('Event category'         , 'rpbcalendar'),
				'add_new_item'  => __('Add new event category' , 'rpbcalendar'),
				'edit_item'     => __('Edit event category'    , 'rpbcalendar'),
				'view_item'     => __('View event category'    , 'rpbcalendar'),
				'search items'  => __('Search event categories', 'rpbcalendar'),
				'not_found'     => __('No event category found', 'rpbcalendar')
			),
			'public'       => true,
			'hierarchical' => true,
			'rewrite'      => array('slug' => 'event-category'),
			'query_var'    => 'event-category'
		));

		// Callback for post querying
		add_filter('request', array($this, 'alterQuery'));

		// Callback for post saving
		add_action('save_post', array($this, 'save'));

		// Filter for the definition of the columns in the backend interface.
		add_filter('manage_rpbevent_posts_columns', array($this, 'registerEditionColumns'));
	}


	/**
	 * Callback that alters the parameters of post queries to handle some meta-information
	 * associated to the events.
	 *
	 * @param array $args Query parameters
	 * @return array Modified query parameters
	 */
	public function alterQuery($args)
	{
		// Only events are affected
		if($args['post_type']=='rpbevent')
		{
			// Allow ordering by date
			if(isset($args['orderby']) && $args['orderby']=='rpbevent_date_begin') {
				$args = array_merge($args, array('meta_key' => 'rpbevent_date_begin', 'orderby' => 'meta_value'));
			}
		}
		return $args;
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
	 * Callback for the edition columns.
	 *
	 * @param array $columns Default columns.
	 * @return array
	 */
	public function registerEditionColumns($columns)
	{
		require_once(RPBCALENDAR_ABSPATH . 'controllers/editioncolumn.php');
		$controller = new RPBCalendarControllerEditionColumn($columns);
		return $controller->run();
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
