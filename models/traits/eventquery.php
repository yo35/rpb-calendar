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


require_once(RPBCALENDAR_ABSPATH . 'models/traits/event.php');


/**
 * Fetch the events that meet a certain set of criterias.
 */
class RPBCalendarTraitEventQuery extends RPBCalendarTraitEvent
{
	private $query;
	private $atLeastOneEvent;


	/**
	 * Constructor.
	 *
	 * @param array $where Selection criterias:
	 *
	 *  - `$where['time_frame_begin']`: only the events that end at or after this date,
	 *  - `$where['time_frame_end']`: only the events that start before or at this date.
	 */
	public function __construct($where)
	{
		// Query default arguments.
		$args = array(
			'post_type' => 'rpbevent',
			'nopaging'  => true
		);
		$metaQuery = array();

		// Filter: only the events within a given time frame.
		if(isset($where['time_frame_begin'])) {
			$metaQuery[] = array(
				'key'     => 'rpbevent_date_end',
				'value'   => $where['time_frame_begin'],
				'compare' => '>='
			);
		}
		if(isset($where['time_frame_end'])) {
			$metaQuery[] = array(
				'key'     => 'rpbevent_date_begin',
				'value'   => $where['time_frame_end'],
				'compare' => '<='
			);
		}

		// Create the WP_Query object.
		if(!empty($metaQuery)) {
			$args['meta_query'] = $metaQuery;
		}
		$this->query = new WP_Query($args);

		// At least one event founded?
		$this->atLeastOneEvent = $this->query->have_posts();
	}


	/**
	 * Whether some events matching the required criterias have been founded or not.
	 *
	 * @return boolean
	 */
	public function haveEvent()
	{
		return $this->atLeastOneEvent;
	}


	/**
	 * Try to fetch the next matching event.
	 *
	 * @return boolean True if the next event has been fetched, false after the last event.
	 */
	public function fetchEvent()
	{
		// Return false if there is no more events.
		if(!$this->query->have_posts()) {
			return false;
		}

		// Otherwise, fetch the next event, set its ID, and return true.
		$this->query->next_post();
		$this->setEventID($this->query->post->ID);
		return true;
	}
}
