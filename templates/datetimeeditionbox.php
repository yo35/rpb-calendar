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

<div class="rpbcalendar-admin-gridLayout">
	<div>
		<div>
			<label for="rpbcalendar-admin-eventDateBeginField"><?php _e('From:', 'rpbcalendar'); ?></label>
		</div>
		<div>
			<input type="text" name="event_date_begin" id="rpbcalendar-admin-eventDateBeginField" value="<?php
				echo htmlspecialchars($model->getEventDateBeginAsString());
			?>" size="10" />
		</div>
	</div>
	<div>
		<div>
			<label for="rpbcalendar-admin-eventDateBeginField"><?php _e('To:', 'rpbcalendar'); ?></label>
		</div>
		<div>
			<input type="text" name="event_date_end" id="rpbcalendar-admin-eventDateEndField" value="<?php
				echo htmlspecialchars($model->getEventDateEndAsString());
			?>" size="10" />
		</div>
	</div>
</div>
