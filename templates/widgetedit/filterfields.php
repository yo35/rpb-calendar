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
	<label for="<?php echo htmlspecialchars($model->getInclusiveModeFieldID()); ?>"><?php
		_e('Event category filter:', '');
	?></label>
	<select class="widefat"
		id="<?php echo htmlspecialchars($model->getInclusiveModeFieldID()); ?>"
		name="<?php echo htmlspecialchars($model->getInclusiveModeFieldName()); ?>"
	>
		<option value="0" <?php if(!$model->getInclusiveMode()): ?>selected="selected"<?php endif; ?>>
			<?php _e('Hide the selected categories', 'rpbcalendar'); ?>
		</option>
		<option value="1" <?php if($model->getInclusiveMode()): ?>selected="selected"<?php endif; ?>>
			<?php _e('Only show the selected categories', 'rpbcalendar'); ?>
		</option>
	</select>
</p>

<p>
	<!-- TODO: provide an easier way to selected categories -->
	<label for="<?php echo htmlspecialchars($model->getFilteredCategoriesFieldID()); ?>"><?php
		_e('IDs of the filtered categories (comma separated string):', 'rpbcalendar');
	?></label>
	<input type="text" class="widefat"
		id="<?php echo htmlspecialchars($model->getFilteredCategoriesFieldID()); ?>"
		name="<?php echo htmlspecialchars($model->getFilteredCategoriesFieldName()); ?>"
		value="<?php echo htmlspecialchars($model->getFilteredCategories(true)); ?>"
	/>
</p>
