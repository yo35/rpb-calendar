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


require_once(RPBCALENDAR_ABSPATH . 'models/traits/abstracttrait.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Update the meta information associated to an event category.
 */
class RPBCalendarTraitPostCategory extends RPBCalendarAbstractTrait
{
	private $color;


	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Load the color.
		if(isset($_POST['rpbevent_category_color'])) {
			$this->color = RPBCalendarHelperValidation::validateColor($_POST['rpbevent_category_color'], true);
		}
	}


	/**
	 * Execute the update request.
	 */
	public function updateCategory($categoryID)
	{
		// Update the color.
		if(isset($this->color)) {
			update_option('rpbevent_category_' . $categoryID . '_color', $this->color);
		}
	}
}
