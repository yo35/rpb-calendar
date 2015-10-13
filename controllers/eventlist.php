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


require_once(RPBCALENDAR_ABSPATH . 'controllers/abstractcontroller.php');


/**
 * Customize the table showing the list of events.
 */
class RPBCalendarControllerEventList extends RPBCalendarAbstractController
{
	private $defaultColumns; // Default set of columns as defined by the WP engine.
	private static $initialized = false;


	public function __construct($defaultColumns)
	{
		parent::__construct('EventList');
		$this->defaultColumns = $defaultColumns;
	}


	public function run()
	{
		if(!self::$initialized) {
			self::$initialized = true;

			// Register the callback to use to print the content of the custom columns.
			add_action('manage_rpbevent_posts_custom_column', array($this, 'printCell'), 10, 2);

			// Register the callback to filter the events based on their categories.
			add_action('restrict_manage_posts', array($this, 'registerCategoryFilter'));

			// Register the filter that defines the sortable columns.
			add_filter('manage_edit-rpbevent_sortable_columns', array($this, 'registerSortableColumns'));
		}

		// New set of columns.
		return array(
			'cb'                  => $this->defaultColumns['cb'      ],
			'title'               => $this->defaultColumns['title'   ],
			'rpbevent_date_time'  => __('Date/time', 'rpbcalendar'),
			'rpbevent_link'       => __('Link', 'rpbcalendar'),
			'author'              => $this->defaultColumns['author'  ],
			'rpbevent_categories' => __('Categories', 'rpbcalendar'),
			'date'                => __('State', 'rpbcalendar')
		);
	}


	/**
	 * Print a combo-box allowing the user to show only the events belonging to a particular event category.
	 */
	public function registerCategoryFilter()
	{
		wp_dropdown_categories(array(
			'taxonomy'        => 'rpbevent_category',
			'name'            => 'rpbevent_category',
			'selected'        => isset($_GET['rpbevent_category']) ? $_GET['rpbevent_category'] : '',
			'hierarchical'    => true,
			'hide_empty'      => false,
			'show_option_all' => __('View all categories', 'rpbcalendar')
		));
	}


	/**
	 * Mark the column containing the event date/time as sortable.
	 *
	 * @param array $columns
	 */
	public function registerSortableColumns($columns)
	{
		$columns['rpbevent_date_time'] = 'rpbevent_date_begin';
		return $columns;
	}


	/**
	 * Render the content of a cell in the event list table.
	 *
	 * @param string $column ID of the current columns in the event list table.
	 * @param int $eventID ID of the event corresponding to the current row in the event list table.
	 */
	public function printCell($column, $eventID)
	{
		$model = $this->getModel();
		$model->setEventID($eventID);
		$model->setTemplateName(self::getTemplateName($column));
		$this->getView()->display();
	}


	/**
	 * Return the name of the template to use based on the given column ID.
	 *
	 * @param string $column
	 * @return string
	 */
	private static function getTemplateName($column)
	{
		switch($column) {
			case 'rpbevent_date_time' : return 'DateTimeColumn'  ;
			case 'rpbevent_link'      : return 'LinkColumn'      ;
			case 'rpbevent_categories': return 'CategoriesColumn';
			default: return null;
		}
	}
}
