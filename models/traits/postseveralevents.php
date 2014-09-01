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
require_once(RPBCALENDAR_ABSPATH . 'helpers/date.php');


/**
 * Update the options of the plugin.
 */
class RPBCalendarTraitPostSeveralEvents extends RPBCalendarAbstractTrait
{
	public function updateEvents()
	{
		$counter = 0;
		foreach($this->getIndexesToProcess() as $index) {
			if($this->processRow($index)) {
				++$counter;
			}
		}

		switch($counter) {
			case 0: return __('No event added', 'rpbcalendar');
			case 1: return __('1 event added', 'rpbcalendar');
			default: return sprintf(__('%1$d events added', 'rpbcalendar'), $counter);
		}
	}


	/**
	 * Return the list of indexes to process (corresponding to row indexes in the add-several-events form).
	 *
	 * @return array
	 */
	private function getIndexesToProcess()
	{
		$retVal = array();
		foreach($_POST as $field => $value) {
			if(preg_match('/^rpbevent_id_(0|[1-9][0-9]*)$/', $field, $m)) {
				$retVal[] = intval($m[1]);
			}
		}
		return $retVal;
	}


	/**
	 * Process a row (i.e. 1 event).
	 *
	 * @param int $index Index of the row to process
	 * @return boolean True if the event has been successfully processed.
	 */
	private function processRow($index)
	{
		// Title
		$field = 'rpbevent_title_' . $index;
		$title = isset($_POST[$field]) ? RPBCalendarHelperValidation::validateNonEmptyString($_POST[$field]) : null;
		if($title === null) {
			return false;
		}

		// Teaser
		$field = 'rpbevent_teaser_' . $index;
		$teaser = isset($_POST[$field]) ? RPBCalendarHelperValidation::validateString($_POST[$field]) : '';

		// Link
		$field = 'rpbevent_link_' . $index;
		$link = isset($_POST[$field]) ? RPBCalendarHelperValidation::validateString($_POST[$field]) : '';

		// Date begin
		$field = 'rpbevent_date_begin_' . $index;
		$dateBegin = isset($_POST[$field]) ? RPBCalendarHelperValidation::validateDate($_POST[$field]) : null;
		if($dateBegin === null) {
			$dateBegin = RPBCalendarHelperDate::today();
		}

		// Date end
		$field = 'rpbevent_date_end_' . $index;
		$dateEnd = isset($_POST[$field]) ? RPBCalendarHelperValidation::validateDate($_POST[$field]) : null;
		if($dateEnd === null || $dateEnd < $dateBegin) {
			$dateEnd = $dateBegin;
		}

		// Categories
		$field = 'rpbevent_categories_' . $index;
		$categories = isset($_POST[$field]) ? RPBCalendarHelperValidation::validateIntegerArray($_POST[$field]) : null;
		if($categories === null) {
			$categories = array();
		}

		// Insert the post
		$eventID = wp_insert_post(array(
			'post_title'   => wp_strip_all_tags($title),
			'post_excerpt' => wp_strip_all_tags($teaser),
			'tax_input'    => array('rpbevent_category' => $categories),
			'post_type'    => 'rpbevent',
			'post_status'  => 'publish'
		));
		if($eventID === 0) {
			return false;
		}

		// Insert the meta-data
		update_post_meta($eventID, 'rpbevent_link'      , $link);
		update_post_meta($eventID, 'rpbevent_date_begin', date('Y-m-d', $dateBegin));
		update_post_meta($eventID, 'rpbevent_date_end'  , date('Y-m-d', $dateEnd  ));

		return true;
	}
}
