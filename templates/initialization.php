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

		// Color-picker localization
		if(jQuery && jQuery.iris2) {
			jQuery.iris2.RANDOM_BUTTON_LABEL = <?php echo json_encode(__('Random', 'rpbcalendar')); ?>;
			jQuery.iris2.CLEAR_BUTTON_LABEL = <?php echo json_encode(__('Clear', 'rpbcalendar')); ?>;
			jQuery.iris2.RANDOM_BUTTON_POPUP = <?php echo json_encode(__('Select a color at random', 'rpbcalendar')); ?>;
			jQuery.iris2.CLEAR_BUTTON_POPUP = <?php echo json_encode(__('Unselect the current color', 'rpbcalendar')); ?>;
		}

		// Configuration
		RPBCalendar.config.FETCH_EVENTS_URL = <?php echo json_encode(RPBCALENDAR_URL . '/ajax/fetchevents.php'); ?>;
		RPBCalendar.config.FETCH_EVENT_DATA_URL = <?php echo json_encode(RPBCALENDAR_URL . '/ajax/fetcheventdata.php'); ?>;
		RPBCalendar.config.FIRST_DAY_OF_WEEK = <?php echo get_option('start_of_week'); ?>;

	})();

</script>
