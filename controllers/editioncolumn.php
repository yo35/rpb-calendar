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
 * Controls the display of the columns in the "list of events" page of the backend interface.
 */
class RPBCalendarControllerEditionColumn extends RPBCalendarAbstractController
{
	private $defaultColumns; // Default set of columns as defined by the WP engine.


	public function __construct($defaultColumns)
	{
		parent::__construct('EditionColumn');
		$this->defaultColumns = $defaultColumns;
	}


	public function run()
	{
		// Register the callback to use to print the content of the custom columns.
		add_action('manage_rpbcalendar_event_posts_custom_column', array($this, 'printEditionColumn'), 10, 2);

		// New set of columns.
		return array(
			'cb'         => $this->defaultColumns['cb'      ],
			'title'      => $this->defaultColumns['title'   ],
			'event_date' => __('Date', 'rpbcalendar'),
			'author'     => $this->defaultColumns['author'  ],
			'comments'   => $this->defaultColumns['comments'],
			'date'       => __('State', 'rpbcalendar')
		);
	}


	/**
	 * Render the content of a cell in the event list table.
	 *
	 * @param string $column ID of the current columns in the event list table.
	 * @param int $eventID ID of the event corresponding to the current row in the event list table.
	 */
	public function printEditionColumn($column, $eventID)
	{
		echo 'TODO col=' . $column . ' ev='.$eventID;
	}
}
