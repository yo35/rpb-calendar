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

<?php if($model->isNewCategoryMode()): ?>
	<div class="form-field">
<?php else: ?>
	<tr class="form-field">
		<th valign="top" scope="row">
<?php endif; ?>


<label for="rpbcalendar-categoryColorField"><?php _e('Color', 'rpbcalendar'); ?></label>

<?php if(!$model->isNewCategoryMode()): ?>
		</th>
		<td>
<?php endif; ?>

<input type="hidden" name="rpbevent_category_color" id="rpbcalendar-categoryColorField" value="<?php
	echo htmlspecialchars($model->getCategoryColor());
?>" />

<div id="rpbcalendar-categoryColorIris"></div>

<p class="description">
	<?php echo sprintf(
		__(
			'The color is used to display the events that belong to the category. '.
			'The events belonging to a category for which no color is defined are displayed '.
			'either with the color associated to the category parent (if the latter exists), '.
			'or with the default category color (see the %1$sglobal settings%2$s).',
		'rpbcalendar'),
		'<a href="'.htmlspecialchars($model->getPageOptionsURL()).'">',
		'</a>');
	?>
</p>

<?php if($model->isNewCategoryMode()): ?>
	</div>
<?php else: ?>
		</td>
	</tr>
<?php endif; ?>



<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		// Initialize the color picker widget.
		$('#rpbcalendar-categoryColorIris').iris2({
			buttonClass: 'button',
			color: $('#rpbcalendar-categoryColorField').val(),
			change: function(event, ui) {
				$('#rpbcalendar-categoryColorField').val(ui.color);
			}
		});

		// Initial aspect of the color sample.
		<?php if($model->isNewCategoryMode()): ?>
			$('#rpbcalendar-categoryColorIris').iris2('selectRandom');
		<?php endif; ?>

	});

</script>
