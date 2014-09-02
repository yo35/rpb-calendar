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

<div id="rpbcalendar-aboutPage">

	<p>
		<?php _e(
			'RPB Calendar allows you to list and schedule events, and to display them ' .
			'in a calendar in a post or a page of your WordPress blog.',
			'rpbcalendar');
		?>
	</p>
	<p>
		<a class="button" href="https://github.com/yo35/rpb-calendar/issues" target="_blank">
			<?php echo sprintf('%1$s / %2$s', __('Ask for help', 'rpbcalendar'), __('Report a problem', 'rpbcalendar')); ?>
		</a>
	</p>
	<p class="description">
		<?php echo sprintf(
			__(
				'If you encounter some bugs with this plugin, or if you wish to get new features in the future versions, '.
				'you can report/propose them in the %1$sGitHub bug tracker%2$s.',
			'rpbcalendar'),
			'<a href="https://github.com/yo35/rpb-calendar/issues" target="_blank">',
			'</a>');
		?>
	</p>


	<h3><?php _e('Plugin version', 'rpbcalendar'); ?></h3>
	<p><?php echo htmlspecialchars($model->getPluginVersion()); ?></p>


	<h3><?php _e('Links', 'rpbcalendar'); ?></h3>
	<ul>
		<li><strong><a href="http://yo35.org/rpb-calendar/" target="_blank">http://yo35.org/rpb-calendar/</a></strong></li>
		<li>
			<a href="https://wordpress.org/plugins/rpb-calendar/" target="_blank">https://wordpress.org/plugins/rpb-calendar/</a>
			<?php echo sprintf('(%1$s)', __('plugin page on WordPress.org', 'rpbcalendar')); ?>
		</li>
		<li>
			<a href="https://github.com/yo35/rpb-calendar" target="_blank">https://github.com/yo35/rpb-calendar</a>
			<?php echo sprintf('(%1$s)', __('source code on GitHub', 'rpbcalendar')); ?>
		</li>
	</ul>


	<h3><?php _e('Author', 'rpbcalendar'); ?></h3>
	<p><a href="mailto:yo35@melix.net">Yoann Le Montagner</a></p>


	<h3><?php _e('Translation', 'rpbcalendar'); ?></h3>
	<dl id="rpbcalendar-translatorList">
		<div>
			<dt><img src="<?php echo RPBCALENDAR_URL . 'images/flags/de.png'; ?>" />Deutsch</dt>
			<dd>Kay Zeisberg</dd>
		</div>
		<div>
			<dt><img src="<?php echo RPBCALENDAR_URL . 'images/flags/gb.png'; ?>" />English</dt>
			<dd>Yoann Le Montagner</dd>
		</div>
		<div>
			<dt><img src="<?php echo RPBCALENDAR_URL . 'images/flags/fr.png'; ?>" />Français</dt>
			<dd>Yoann Le Montagner</dd>
		</div>
	</dl>
	<p class="description">
		<?php echo sprintf(
			__(
				'If you are interested in translating this plugin into your language, please %1$scontact the author%2$s.',
			'rpbcalendar'),
			'<a href="mailto:yo35@melix.net">',
			'</a>');
		?>
	</p>


	<h3><?php _e('License', 'rpbcalendar'); ?></h3>
	<p>
		<?php echo sprintf(
			__(
				'This plugin is distributed under the terms of the %1$sGNU General Public License version 3%3$s (GPLv3), '.
				'as published by the %2$sFree Software Foundation%3$s. The full text of this license '.
				'is available at %4$s. A copy of this document is also provided with the plugin source code.',
			'rpbcalendar'),
			'<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">',
			'<a href="http://www.fsf.org/" target="_blank">',
			'</a>',
			'<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">http://www.gnu.org/licenses/gpl.html</a>');
		?>
	</p>
	<p>
		<?php _e(
			'This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; '.
			'without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. '.
			'See the GNU General Public License for more details.',
			'rpbcalendar');
		?>
	</p>

</div>
