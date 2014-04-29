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

<div class="rpbcalendar-eventBlockList">
	<?php while($model->fetchEvent()): ?>

		<div class="rpbcalendar-eventBlock"
			style="<?php echo htmlspecialchars($model->getEventBackgroundStyle()); ?>"
			data-event-id="<?php echo htmlspecialchars($model->getEventID()); ?>"
		>
			<?php echo htmlspecialchars($model->getEventTitle()); ?>
		</div>

	<?php endwhile; ?>
</div>


<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		$('#' + <?php echo json_encode($model->getWidgetID()); ?> + ' .rpbcalendar-eventBlock').each(function(i,e) {
			$(e).qtip({
				content: {
					title: $(e).text(),
					button: true,
					text: 'TODO<br/>ID=' + $(e).data('eventId')
				},
				position: { my: 'top left', at: 'bottom left' },
				style: { classes: 'qtip-tipped rpbcalendar-qtip qtip-shadow' },
				show: { delay: 100, solo: true },
				hide: { delay: 100, fixed: true }
			});
		});
	});

</script>
