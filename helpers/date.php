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


/**
 * Date manipulation utilities
 */
abstract class RPBCalendarHelperDate
{
	private static $today;


	/**
	 * Timestamp corresponding to the current day.
	 *
	 * @return int
	 */
	public static function today()
	{
		if(!isset(self::$today)) {
			self::$today = floor(time() / 86400) * 86400;
		}
		return self::$today;
	}


	/**
	 * Format a date into a human-readable string.
	 *
	 * @param int $date
	 * @param boolean $showWeekday
	 * @param boolean $showYear
	 * @return string
	 */
	public static function format($date, $showWeekday, $showYear)
	{
		global $wp_locale;
		$date = getdate($date);

		$res = $showWeekday ? ($showYear ?
			/*i18n Date format, full (e.g. "Sunday, August 3, 2014")   */ __('%w$s, %m$s %d$s, %y$s', 'rpbcalendar') :
			/*i18n Date format, without year (e.g. "Sunday, August 3") */ __('%w$s, %m$s %d$s'      , 'rpbcalendar')
		) : ($showYear ?
			/*i18n Date format, without weekday (e.g. "August 3, 2014") */ __('%m$s %d$s, %y$s'      , 'rpbcalendar') :
			/*i18n Date format, only month and day (e.g. "August 3")    */ __('%m$s %d$s'            , 'rpbcalendar')
		);

		$res = preg_replace('/%y\\$s/', $date['year']                         , $res);
		$res = preg_replace('/%m\\$s/', $wp_locale->get_month($date['mon'])   , $res);
		$res = preg_replace('/%d\\$s/', $date['mday']                         , $res);
		$res = preg_replace('/%w\\$s/', $wp_locale->get_weekday($date['wday']), $res);
		return $res;
	}


	/**
	 * Format a date range into a human-readable string.
	 *
	 * @param int $from
	 * @param int $to
	 * @return string
	 */
	public static function formatRange($from, $to)
	{
		if($from === $to) {
			return self::format($from, false, true);
		}

		global $wp_locale;
		$date1 = getdate($from);
		$date2 = getdate($to  );

		// First case: the begin date and the end date are within the same month.
		if($date1['mon'] === $date2['mon'] && $date1['year'] === $date2['year']) {
			$res = /*i18n Date range format, same begin/end month */ __('%m1$s %d1$s–%d2$s, %y1$s', 'rpbcalendar');
		}

		// Second case: the begin and the end date are within the same year.
		else if($date1['year'] === $date2['year']) {
			$res = /*i18n Date range format, same begin/end year */ __('%m1$s %d1$s – %m2$s %d2$s, %y1$s', 'rpbcalendar');
		}

		// General case
		else {
			$res = /*i18n Date range format, large range */ __('%m1$s %d1$s, %y1$s – %m2$s %d2$s, %y2$s', 'rpbcalendar');
		}

		$res = preg_replace('/%y1\\$s/', $date1['year']                         , $res);
		$res = preg_replace('/%m1\\$s/', $wp_locale->get_month($date1['mon'])   , $res);
		$res = preg_replace('/%d1\\$s/', $date1['mday']                         , $res);
		$res = preg_replace('/%y2\\$s/', $date2['year']                         , $res);
		$res = preg_replace('/%m2\\$s/', $wp_locale->get_month($date2['mon'])   , $res);
		$res = preg_replace('/%d2\\$s/', $date2['mday']                         , $res);
		return $res;
	}
}
