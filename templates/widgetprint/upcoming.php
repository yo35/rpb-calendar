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
	$eventFetched = $model->fetchEvent();
	while($eventFetched):
?>

	<?php if($model->needToOpenNextEventSection()): ?>
		<h3 class="rpbcalendar-eventSectionTitle"><?php echo htmlspecialchars($model->getEventSectionTitle()); ?></h3>
		<div class="rpbcalendar-eventBlockList">
	<?php endif; ?>

	<?php
		// Render the current event block.
		include(RPBCALENDAR_ABSPATH . 'templates/widgetprint/event.php');
	?>

	<?php $eventFetched = $model->fetchEvent(); ?>

	<?php if($model->needToClosePreviousEventSection()): ?>
		</div>
	<?php endif; ?>

<?php endwhile; ?>

<?php
	// Decorate the event blocks with tooltips.
	include(RPBCALENDAR_ABSPATH . 'templates/widgetprint/tooltips.php');
?>
