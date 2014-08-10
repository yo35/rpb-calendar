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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractmodel.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');


/**
 * Model for fetch events AJAX page.
 */
class RPBCalendarModelFetchEvents extends RPBCalendarAbstractModel
{
	private $queryValid;


	/**
	 * The width of the time frame cannot exceed the number of days defined by this constant.
	 */
	const MAXIMUM_NUMBER_OF_DAYS = 366;


	/**
	 * Whether the parameters passed to the query are valid or not.
	 *
	 * @return boolean
	 */
	public function isQueryValid()
	{
		$this->ensureQueryInitialized();
		return $this->queryValid;
	}


	/**
	 * Initialize the fetch event query.
	 */
	private function ensureQueryInitialized()
	{
		if(isset($this->queryValid)) {
			return;
		}
		$this->queryValid = false;

		// Retrieve the GET parameters that define the start/end of the time frame from which events must be fetched.
		if(!isset($_GET['start']) || !$_GET['end']) {
			return;
		}
		$timeFrameBegin = RPBCalendarHelperValidation::validateDate($_GET['start']);
		$timeFrameEnd   = RPBCalendarHelperValidation::validateDate($_GET['end'  ]);
		if($timeFrameBegin===null || $timeFrameEnd===null || $timeFrameEnd-$timeFrameBegin > 86400*self::MAXIMUM_NUMBER_OF_DAYS) {
			return;
		}

		// Fetch the requested events.
		$this->loadTrait('EventQuery', array(
			'time_frame_begin' => date('Y-m-d', $timeFrameBegin),
			'time_frame_end'   => date('Y-m-d', $timeFrameEnd  )
		));
		$this->queryValid = true;
	}
}
