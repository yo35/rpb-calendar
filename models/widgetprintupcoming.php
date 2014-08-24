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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/widgetprint.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/date.php');


/**
 * Model used to render the upcoming events widget in the frontend.
 */
class RPBCalendarModelWidgetPrintUpcoming extends RPBCalendarAbstractModelWidgetPrint
{
	private $previousEventDateBegin;
	private $previousEventDateEnd;


	public function __construct($instance, $theme)
	{
		parent::__construct($instance, $theme);
		$this->loadTrait('WidgetUpcoming', $this->instance);
		$this->registerFields($this->getUpcomingWidgetFields());

		// Load the events.
		$where = array(
			'time_frame_begin' => $this->getTimeFrameBegin(),
			'time_frame_end'   => $this->getTimeFrameEnd  ()
		);
		$where[$this->getInclusiveMode() ? 'category_in' : 'category_not_in'] = $this->getFilteredCategories();
		$this->loadTrait('EventQuery', $where);
	}


	protected function computeIsWidgetHidden()
	{
		return !$this->hasEvent();
	}


	public function fetchEvent()
	{
		if($this->getEventID() > 0) {
			$this->previousEventDateBegin = $this->getEventDateBegin();
			$this->previousEventDateEnd   = $this->getEventDateEnd  ();
		}
		return parent::fetchEvent();
	}


	/**
	 * Whether the previous event was the last of its event section (= group of events having the same begin and end dates).
	 *
	 * @return boolean
	 */
	public function needToClosePreviousEventSection()
	{
		// There is no event section to close if no event was loaded before the last call to `fetch()`.
		if($this->previousEventDateBegin===null || $this->previousEventDateEnd===null) {
			return false;
		}

		// Close the section is there is no further event or if the next event has not the same begin and end dates.
		return !($this->getEventID() > 0 && $this->previousEventDateBegin===$this->getEventDateBegin() &&
			$this->previousEventDateEnd===$this->getEventDateEnd());
	}


	/**
	 * Whether the current event is the first of its event section (= group of events having the same begin and end dates).
	 *
	 * @return boolean
	 */
	public function needToOpenNextEventSection()
	{
		// Always open a new section if no event was loaded before the last call to `fetch()`.
		if($this->previousEventDateBegin===null || $this->previousEventDateEnd===null) {
			return true;
		}

		// Open a new section if the previous event has not the same begin and end dates.
		return $this->getEventID() > 0 && !($this->previousEventDateBegin===$this->getEventDateBegin() &&
			$this->previousEventDateEnd===$this->getEventDateEnd());
	}


	/**
	 * Title of the current event section.
	 *
	 * @return string
	 */
	public function getEventSectionTitle()
	{
		$dateBegin = $this->getEventDateBegin();
		$dateEnd   = $this->getEventDateEnd  ();
		return RPBCalendarHelperDate::formatRange($dateBegin, $dateEnd);
	}


	/**
	 * Begin date of the time frame.
	 *
	 * @return string
	 */
	private function getTimeFrameBegin()
	{
		$t = RPBCalendarHelperDate::today();
		if(!$this->getWithToday()) {
			$t += 86400; // 86400 = 24*60*60 = number of seconds in a day.
		}
		return date('Y-m-d', $t);
	}


	/**
	 * End date of the time frame.
	 *
	 * @return string
	 */
	private function getTimeFrameEnd()
	{
		$t = RPBCalendarHelperDate::today();
		$t += $this->getTimeFrame() * 86400; // 86400 = 24*60*60 = number of seconds in a day.
		return date('Y-m-d', $t);
	}
}
