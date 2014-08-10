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

<?php
	global $wp_locale;
?>

<script type="text/javascript">

	(function() {

		// Localization
		RPBCalendar.i18n.POSTED_ON_BY = <?php echo json_encode(__('Posted on %1$s by %2$s', 'rpbcalendar')); ?>;
		RPBCalendar.i18n.TODAY = <?php echo json_encode(__('Today', 'rpbcalendar')); ?>;

		// Localization (month & weekdays)
		RPBCalendar.i18n.MONTH_NAMES = [
			<?php echo json_encode($wp_locale->get_month( 1)); ?>,
			<?php echo json_encode($wp_locale->get_month( 2)); ?>,
			<?php echo json_encode($wp_locale->get_month( 3)); ?>,
			<?php echo json_encode($wp_locale->get_month( 4)); ?>,
			<?php echo json_encode($wp_locale->get_month( 5)); ?>,
			<?php echo json_encode($wp_locale->get_month( 6)); ?>,
			<?php echo json_encode($wp_locale->get_month( 7)); ?>,
			<?php echo json_encode($wp_locale->get_month( 8)); ?>,
			<?php echo json_encode($wp_locale->get_month( 9)); ?>,
			<?php echo json_encode($wp_locale->get_month(10)); ?>,
			<?php echo json_encode($wp_locale->get_month(11)); ?>,
			<?php echo json_encode($wp_locale->get_month(12)); ?>
		];
		RPBCalendar.i18n.MONTH_SHORT_NAMES = [
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 1))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 2))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 3))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 4))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 5))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 6))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 7))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 8))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month( 9))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month(10))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month(11))); ?>,
			<?php echo json_encode($wp_locale->get_month_abbrev($wp_locale->get_month(12))); ?>
		];
		RPBCalendar.i18n.WEEKDAY_NAMES = [
			<?php echo json_encode($wp_locale->get_weekday(0)); ?>,
			<?php echo json_encode($wp_locale->get_weekday(1)); ?>,
			<?php echo json_encode($wp_locale->get_weekday(2)); ?>,
			<?php echo json_encode($wp_locale->get_weekday(3)); ?>,
			<?php echo json_encode($wp_locale->get_weekday(4)); ?>,
			<?php echo json_encode($wp_locale->get_weekday(5)); ?>,
			<?php echo json_encode($wp_locale->get_weekday(6)); ?>
		];
		RPBCalendar.i18n.WEEKDAY_SHORT_NAMES = [
			<?php echo json_encode($wp_locale->get_weekday_abbrev($wp_locale->get_weekday(0))); ?>,
			<?php echo json_encode($wp_locale->get_weekday_abbrev($wp_locale->get_weekday(1))); ?>,
			<?php echo json_encode($wp_locale->get_weekday_abbrev($wp_locale->get_weekday(2))); ?>,
			<?php echo json_encode($wp_locale->get_weekday_abbrev($wp_locale->get_weekday(3))); ?>,
			<?php echo json_encode($wp_locale->get_weekday_abbrev($wp_locale->get_weekday(4))); ?>,
			<?php echo json_encode($wp_locale->get_weekday_abbrev($wp_locale->get_weekday(5))); ?>,
			<?php echo json_encode($wp_locale->get_weekday_abbrev($wp_locale->get_weekday(6))); ?>
		];

		// Configuration
		RPBCalendar.config.FETCH_EVENT_DATA_URL = <?php echo json_encode(RPBCALENDAR_URL . '/ajax/fetcheventdata.php'); /* TODO: use trait AjaxURLs instead */ ?>;

	})();

</script>
