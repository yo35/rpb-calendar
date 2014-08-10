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
?>

<script type="text/javascript">

	(function() {

		// Localization
		RPBCalendar.i18n.POSTED_ON_BY = <?php echo json_encode(__('Posted on %1$s by %2$s', 'rpbcalendar')); ?>;
		RPBCalendar.i18n.TODAY = <?php echo json_encode(__('Today', 'rpbcalendar')); ?>;

		// Localization (month & weekdays)
		RPBCalendar.i18n.MONTH_NAMES = [
			<?php /*i18n Month name */ echo json_encode(__('January'  , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('February' , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('March'    , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('April'    , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('May'      , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('June'     , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('July'     , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('August'   , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('September', 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('October'  , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('November' , 'rpbcalendar')); ?>,
			<?php /*i18n Month name */ echo json_encode(__('December' , 'rpbcalendar')); ?>
		];
		RPBCalendar.i18n.MONTH_SHORT_NAMES = [
			<?php /*i18n Month short name */ echo json_encode(__('Jan', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Feb', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Mar', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Apr', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('May', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Jun', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Jul', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Aug', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Sep', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Oct', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Nov', 'rpbcalendar')); ?>,
			<?php /*i18n Month short name */ echo json_encode(__('Dec', 'rpbcalendar')); ?>
		];
		RPBCalendar.i18n.WEEKDAY_NAMES = [
			<?php /*i18n Weekday name */ echo json_encode(__('Sunday'   , 'rpbcalendar')); ?>,
			<?php /*i18n Weekday name */ echo json_encode(__('Monday'   , 'rpbcalendar')); ?>,
			<?php /*i18n Weekday name */ echo json_encode(__('Tuesday'  , 'rpbcalendar')); ?>,
			<?php /*i18n Weekday name */ echo json_encode(__('Wednesday', 'rpbcalendar')); ?>,
			<?php /*i18n Weekday name */ echo json_encode(__('Thursday' , 'rpbcalendar')); ?>,
			<?php /*i18n Weekday name */ echo json_encode(__('Friday'   , 'rpbcalendar')); ?>,
			<?php /*i18n Weekday name */ echo json_encode(__('Saturday' , 'rpbcalendar')); ?>
		];
		RPBCalendar.i18n.WEEKDAY_SHORT_NAMES = [
			<?php /*i18n Weekday short name */ echo json_encode(__('Sun', 'rpbcalendar')); ?>,
			<?php /*i18n Weekday short name */ echo json_encode(__('Mon', 'rpbcalendar')); ?>,
			<?php /*i18n Weekday short name */ echo json_encode(__('Tue', 'rpbcalendar')); ?>,
			<?php /*i18n Weekday short name */ echo json_encode(__('Wed', 'rpbcalendar')); ?>,
			<?php /*i18n Weekday short name */ echo json_encode(__('Thu', 'rpbcalendar')); ?>,
			<?php /*i18n Weekday short name */ echo json_encode(__('Fri', 'rpbcalendar')); ?>,
			<?php /*i18n Weekday short name */ echo json_encode(__('Sat', 'rpbcalendar')); ?>
		];

		// Configuration
		RPBCalendar.config.FETCH_EVENT_DATA_URL = <?php echo json_encode(RPBCALENDAR_URL . '/ajax/fetcheventdata.php'); /* TODO: use trait AjaxURLs instead */ ?>;

	})();

</script>
