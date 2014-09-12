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

<div id="rpbcalendar-addSeveralPage">

	<form action="<?php echo htmlspecialchars($model->getPageAddSeveralURL()); ?>" method="post">

		<input type="hidden" name="rpbcalendar_action" value="add-several-events" />
		<?php wp_nonce_field('rpbcalendar-add-several-events'); ?>

		<div class="rpbcalendar-vBox" id="rpbcalendar-eventEntries">

			<div class="rpbcalendar-eventEntry" id="rpbcalendar-eventEntry-0">

				<input type="hidden" name="rpbevent_id_0" value="0" />

				<div class="rpbcalendar-hBox">

					<div class="rpbcalendar-vBox">
						<a href="#" class="dashicons dashicons-plus-alt rpbcalendar-cloneEntryButton" title="<?php _e('Copy this event', 'rpbcalendar'); ?>"></a>
						<a href="#" class="dashicons dashicons-dismiss rpbcalendar-removeEntryButton" title="<?php _e('Remove this event', 'rpbcalendar'); ?>"></a>
					</div>

					<div class="rpbcalendar-gridLayout">
						<div>
							<div class="rpbcalendar-labelCell">
								<label for="rpbcalendar-eventTitleField-0"><?php _e('Title', 'rpbcalendar'); ?></label>
							</div>
							<div>
								<input type="text" name="rpbevent_title_0" class="rpbcalendar-eventTitleField" id="rpbcalendar-eventTitleField-0" value="" />
							</div>
						</div>
						<div>
							<div class="rpbcalendar-labelCell">
								<label for="rpbcalendar-eventDateBeginField-0"><?php _e('From', 'rpbcalendar'); ?></label>
							</div>
							<div>
								<input type="text" name="rpbevent_date_begin_0" class="rpbcalendar-eventDateBeginField" id="rpbcalendar-eventDateBeginField-0" value="<?php
									echo htmlspecialchars($model->getInitialEventDateFields());
								?>" size="10" />
								<span class="rpbcalendar-eventDateBeginWeekday"></span>
							</div>
						</div>
						<div>
							<div class="rpbcalendar-labelCell">
								<label for="rpbcalendar-eventDateEndField-0"><?php _e('To', 'rpbcalendar'); ?></label>
							</div>
							<div>
								<input type="text" name="rpbevent_date_end_0" class="rpbcalendar-eventDateEndField" id="rpbcalendar-eventDateEndField-0" value="<?php
									echo htmlspecialchars($model->getInitialEventDateFields());
								?>" size="10" />
								<span class="rpbcalendar-eventDateEndWeekday"></span>
								<div class="rpbcalendar-jQuery-enableSmoothness rpbcalendar-eventDateBeginPicker" id="rpbcalendar-eventDateBeginPicker-0"></div>
								<div class="rpbcalendar-jQuery-enableSmoothness rpbcalendar-eventDateEndPicker" id="rpbcalendar-eventDateEndPicker-0"></div>
							</div>
						</div>
						<div>
							<div class="rpbcalendar-labelCell">
								<label for="rpbcalendar-eventLinkField-0"><?php _e('Link', 'rpbcalendar'); ?></label>
							</div>
							<div>
								<input type="text" name="rpbevent_link_0" class="rpbcalendar-eventLinkField" id="rpbcalendar-eventLinkField-0" value="" />
							</div>
						</div>
					</div>

					<div class="rpbcalendar-gridLayout">
						<div>
							<div class="rpbcalendar-labelCell">
								<label for="rpbcalendar-eventTeaserField-0"><?php _e('Excerpt', 'rpbcalendar'); ?></label>
							</div>
							<div>
								<textarea name="rpbevent_teaser_0" class="rpbcalendar-eventTeaserField" id="rpbcalendar-eventTeaserField-0"></textarea>
							</div>
						</div>
						<div>
							<div class="rpbcalendar-labelCell">
								<label for="rpbcalendar-eventContentField-0"><?php _e('Content', 'rpbcalendar'); ?></label>
							</div>
							<div>
								<input type="text" name="rpbevent_content_0" class="rpbcalendar-eventContentField" id="rpbcalendar-eventContentField-0" value="" />
							</div>
						</div>
					</div>

					<div class="rpbcalendar-eventCategorySelector">
						<input type="hidden" name="rpbevent_categories_0[]" value="0" />
						<?php include(RPBCALENDAR_ABSPATH . 'templates/adminpage/addseveral-categoryselector.php'); ?>
					</div>

				</div>

			</div>

		</div>


		<p class="submit">
			<input class="button button-primary" type="submit" value="<?php _e('Add events', 'rpbcalendar'); ?>" />
		</p>

	</form>

</div>

<script type="text/javascript">

	jQuery(document).ready(function($) {
		RPBCalendar.setupEventRow(0);
	});

</script>
