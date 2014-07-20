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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/widgetupdate.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Model to update the settings of the today events widget.
 */
class RPBCalendarModelWidgetUpdateToday extends RPBCalendarAbstractModelWidgetUpdate
{
	public function __construct($newInstance, $oldInstance)
	{
		parent::__construct($newInstance, $oldInstance);
		$this->loadTrait('WidgetToday', $this->instance);
		$this->registerFields($this->getTodayWidgetFields());
	}


	protected function validateField($field, $value)
	{
		switch($field) {
			case 'Title': return RPBCalendarHelperValidation::validateString($value);
			default: return parent::validateField($field, $value);
		}
	}
}
