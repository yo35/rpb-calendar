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

<p>
	<label for="<?php echo htmlspecialchars($model->getTitleFieldID()); ?>"><?php _e('Title:', 'rpbcalendar'); ?></label>
	<input type="text" class="widefat"
		id="<?php echo htmlspecialchars($model->getTitleFieldID()); ?>"
		name="<?php echo htmlspecialchars($model->getTitleFieldName()); ?>"
		value="<?php echo htmlspecialchars($model->getTitle()); ?>"
	/>
</p>

<p>
	<label for="<?php echo htmlspecialchars($model->getTimeFrameFieldID()); ?>"><?php
		_e('Length of the time frame (in days):', 'rpbcalendar');
	?></label>
	<input type="text" class="widefat"
		id="<?php echo htmlspecialchars($model->getTimeFrameFieldID()); ?>"
		name="<?php echo htmlspecialchars($model->getTimeFrameFieldName()); ?>"
		value="<?php echo htmlspecialchars($model->getTimeFrame()); ?>"
	/>
</p>

<p>
	<input type="hidden" value="0" name="<?php echo htmlspecialchars($model->getWithTodayFieldName()); ?>" />
	<input type="checkbox" value="1" class="widefat"
		id="<?php echo htmlspecialchars($model->getWithTodayFieldID()); ?>"
		name="<?php echo htmlspecialchars($model->getWithTodayFieldName()); ?>"
		<?php if($model->getWithToday()) echo 'checked="1"'; ?>
	/>
	<label for="<?php echo htmlspecialchars($model->getWithTodayFieldID()); ?>"><?php
		_e('Show events of the current day', 'rpbcalendar');
	?></label>
</p>
