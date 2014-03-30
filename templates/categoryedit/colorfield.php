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

<tr class="form-field">

	<th valign="top" scope="row">
		<label for="rpbcalendar-admin-categoryColorField"><?php _e('Color', 'rpbcalendar'); ?></label>
	</th>

	<td>
		<input type="text" name="rpbevent_category_color" id="rpbcalendar-admin-categoryColorField" value="<?php
			echo htmlspecialchars($model->getCategoryColor());
		?>" size="7" />
	</td>

</tr>
