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
 * Customize the table showing the list of event categories.
 */
class RPBCalendarControllerCategoryList extends RPBCalendarAbstractController
{
	private $defaultColumns; // Default set of columns as defined by the WP engine.


	public function __construct($defaultColumns)
	{
		parent::__construct('CategoryList');
		$this->defaultColumns = $defaultColumns;
	}


	public function run()
	{
		// Register the callback to use to print the content of the custom columns.
		add_action('manage_rpbevent_category_custom_column', array($this, 'printCell'), 10, 3);

		// New set of columns.
		return array(
			'cb'                      => $this->defaultColumns['cb'         ],
			'name'                    => $this->defaultColumns['name'       ],
			'description'             => $this->defaultColumns['description'],
			'rpbevent_category_color' => __('Color', 'rpbcalendar'),
			'posts'                   => $this->defaultColumns['posts'      ]
		);
	}


	/**
	 * Render the content of a cell in the event category list table.
	 *
	 * @param unknown $row Undocumented feature.
	 * @param string $column ID of the current column.
	 * @param int $categoryID ID of the event category corresponding to the current row.
	 */
	public function printCell($row, $column, $categoryID)
	{
		$model = $this->getModel();
		$model->setCategoryID($categoryID);
		$model->useTemplate('ColorColumn');
		$this->getView()->display();
	}
}
