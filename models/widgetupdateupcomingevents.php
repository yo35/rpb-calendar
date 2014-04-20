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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractwidgetupdatemodel.php');


/**
 * Model to update the settings of the upcoming events widget.
 */
class RPBCalendarModelWidgetUpdateUpcomingEvents extends RPBCalendarAbstractWidgetUpdateModel
{
	private $validatedInstance;
	private $newTimeFrame;
	private $newWithToday;


	public function __construct($instance, $newInstance)
	{
		parent::__construct($instance, $newInstance);
		$this->loadTrait('WidgetUpcomingEvents', $this->getInstance());

		// Initialize the new widget parameters.
		$this->newTimeFrame = isset($newInstance['time-frame']) ? RPBCalendarHelperValidation::validateInteger($newInstance['time-frame'], 1) : null;
		$this->newWithToday = isset($newInstance['with-today']) ? RPBCalendarHelperValidation::validateBooleanFromInt($newInstance['with-today']) : null;
	}


	protected function makeValidatedInstance()
	{
		$retVal = parent::makeValidatedInstance();
		$retVal['time-frame'] = isset($this->newTimeFrame) ? $this->newTimeFrame : $this->getTimeFrame();
		$retVal['with-today'] = (isset($this->newWithToday) ? $this->newWithToday : $this->getWithToday()) ? 1 : 0;
		return $retVal;
	}


	/**
	 * New value of the "time-frame" parameter.
	 *
	 * @return int May be null if the new title is invalid.
	 */
	public function getNewTimeFrame()
	{
		return $this->newTimeFrame;
	}


	/**
	 * New value of the "with-today" parameter.
	 *
	 * @return boolean May be null if the new title is invalid.
	 */
	public function getNewWithToday()
	{
		return $this->newWithToday;
	}
}
