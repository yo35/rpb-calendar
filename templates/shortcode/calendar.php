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

<div id="cal" class="rpbcalendar-calendar"></div>

<script type="text/javascript">

	jQuery(document).ready(function($) {

		$('#cal').fullCalendar({
			header: { left: 'title', center: '', right: 'today prevYear,prev,next,nextYear' },
			firstDay: 1
		});

		$('#cal').fullCalendar('renderEvent', {
			title: "Ev1",
			start: "2014-04-01",
			end  : "2014-04-03",
			color: "#ffdd00"
		}, true);

		$('#cal').fullCalendar('renderEvent', {
			title: "An event with a very long title that may be wider than a cell...",
			start: "2014-04-25",
			end  : "2014-04-25",
			color: "#88ff88",
			textColor: "black",
			editable: true
		}, true);

		$('#cal').fullCalendar('renderEvent', {
			title: "Ev2",
			start: "2014-04-03",
			end  : "2014-04-10",
			color: "#880000",
			editable: true
		}, true);

		$('#cal').fullCalendar('renderEvent', {
			title: "Ev2",
			start: "2014-04-05",
			end  : "2014-04-05",
			color: "#0088ff"
		}, true);

	});

</script>
