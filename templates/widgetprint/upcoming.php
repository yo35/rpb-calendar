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

<dl>
	<?php
		$eventFetched = $model->fetchEvent();
		while($eventFetched):
	?>

		<?php if($model->needToOpenNextEventSection()): ?>
			<dt class="rpbcalendar-eventSectionTitle"><?php echo htmlspecialchars($model->getEventSectionTitle()); ?></dd>
			<dd class="rpbcalendar-eventBlockList">
		<?php endif; ?>

		<div class="rpbcalendar-eventBlock"
			style="<?php echo htmlspecialchars($model->getEventBlockStyle()); ?>"
			data-event-id="<?php echo htmlspecialchars($model->getEventID()); ?>"
		>
			<?php echo htmlspecialchars($model->getEventTitle()); ?>
		</div>

		<?php $eventFetched = $model->fetchEvent(); ?>

		<?php if($model->needToClosePreviousEventSection()): ?>
			</dd>
		<?php endif; ?>

	<?php endwhile; ?>
</dl>

<?php
	// Decorate the event blocks with tool-tips.
	include(RPBCALENDAR_ABSPATH . 'templates/widgetprint/tooltips.php');
?>
