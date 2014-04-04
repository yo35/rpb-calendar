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
	private $fetchIntervalBegin;
	private $fetchIntervalEnd  ;
	private $query;


	public function __construct()
	{
		parent::__construct();
		$this->loadTrait('Event');
	}


	/**
	 * First day of the interval from which the events are fetched.
	 *
	 * @return int Timestamp, or null if the parameter is undefined or invalid.
	 */
	public function getFetchIntervalBegin()
	{
		if(!isset($this->fetchIntervalBegin)) {
			$this->fetchIntervalBegin = isset($_GET['start']) ? RPBCalendarHelperValidation::validateDate($_GET['start']) : null;
		}
		return $this->fetchIntervalBegin;
	}


	/**
	 * Last day of the interval from which the events are fetched.
	 *
	 * @return int Timestamp, or null if the parameter is undefined or invalid.
	 */
	public function getFetchIntervalEnd()
	{
		if(!isset($this->fetchIntervalEnd)) {
			$this->fetchIntervalEnd = isset($_GET['end']) ? RPBCalendarHelperValidation::validateDate($_GET['end']) : null;
		}
		return $this->fetchIntervalEnd;
	}


	/**
	 * First day of the fetch interval, formatted as a string.
	 *
	 * @return string Null if the parameter is undefined or invalid.
	 */
	public function getFetchIntervalBeginAsString()
	{
		$value = $this->getFetchIntervalBegin();
		return is_null($value) ? null : date('Y-m-d', $value);
	}


	/**
	 * Last day of the fetch interval, formatted as a string.
	 *
	 * @return string Null if the parameter is undefined or invalid.
	 */
	public function getFetchIntervalEndAsString()
	{
		$value = $this->getFetchIntervalEnd();
		return is_null($value) ? null : date('Y-m-d', $value);
	}


	/**
	 * Title of the currently selected event.
	 *
	 * @return string
	 */
	public function getEventTitle()
	{
		return get_the_title();
	}


	/**
	 * Try to fetch the next event retrieved by the query.
	 */
	public function fetchNextEvent()
	{
		$this->ensureQueryExecuted();

		// Return false if there is no more events.
		if(!$this->query->have_posts()) {
			return false;
		}

		// Otherwise, fetch the next event, set its ID, and return true.
		$this->query->the_post();
		$this->setEventID(get_the_ID());
		return true;
	}


	/**
	 * Define and execute the fetch query if not done yet.
	 */
	private function ensureQueryExecuted()
	{
		// Nothing to do if the query has already been defined and executed.
		if(isset($this->query)) {
			return;
		}

		// Set-up the query.
		$this->query = new WP_Query(array(
			'post_type' => 'rpbevent'
		));
	}
}
