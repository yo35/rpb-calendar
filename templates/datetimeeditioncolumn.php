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

<div class="rpbcalendar-admin-eventDateBegin">
	<?php echo htmlspecialchars($model->getEventDateBeginAsString('Y-M-j (D)')); ?>
</div>

<?php if($model->getEventDateBegin()!=$model->getEventDateEnd()): ?>

	<div class="rpbcalendar-admin-eventDateEnd">
		<span class="rpbcalendar-admin-label"><?php _e('Until:', 'rpbcalendar'); ?></span>
		<?php echo htmlspecialchars($model->getEventDateEndAsString('Y-M-j (D)')); ?>
	</div>

<?php endif; ?>