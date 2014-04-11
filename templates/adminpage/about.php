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

<div id="rpbcalendar-admin-aboutPage">

	<p>
		<?php _e(
			'The RPB Calendar plugin allows you to create, organize, plan and publish events through your Wordpress blog.',
			'rpbcalendar');
		?>
	</p>
	<p>
		<a href="#TODO" target="_blank">TODO: link to the Wordpress page</a>
		<br/>
		<a href="#TODO" target="_blank">TODO: link to the GitHub page</a>
		<?php echo '('.__('developer link', 'rpbcalendar').')'; ?>
	</p>
	<p class="description">
		<?php echo sprintf(
			__(
				'If you encounter some bugs with this plugin, or if you wish to get new features in the future versions, '.
				'you can report/propose them in the bug tracker at %1$s.',
			'rpbcalendar'),
			'<a href="#TODO" target="_blank">TODO: link to the GitHub page/issues</a>');
		?>
	</p>


	<h3><?php _e('Plugin version', 'rpbcalendar'); ?></h3>
	<p><?php echo htmlspecialchars($model->getPluginVersion()); ?></p>


	<h3><?php _e('Author', 'rpbcalendar'); ?></h3>
	<p>Yoann Le Montagner</p>


	<h3><?php _e('Translation', 'rpbcalendar'); ?></h3>
	<dl id="rpbcalendar-admin-translator-list">
		<div>
			<dt><img src="<?php echo RPBCALENDAR_URL.'/images/flags/gb.png'; ?>" />English</dt>
			<dd>Yoann Le Montagner</dd>
		</div>
		<div>
			<dt><img src="<?php echo RPBCALENDAR_URL.'/images/flags/fr.png'; ?>" />Français</dt>
			<dd>Yoann Le Montagner</dd>
		</div>
	</dl>
	<p class="description">
		<?php echo sprintf(
			__(
				'If you are interested in translating this plugin into your language, please contact the author %1$s.',
			'rpbcalendar'),
			'Yoann Le Montagner <a href="mailto:yo35@melix.net">yo35@melix.net</a>');
		?>
	</p>


	<h3><?php _e('License', 'rpbcalendar'); ?></h3>
	<p>
		<?php echo sprintf(
			__(
				'This plugin is distributed under the GNU General Public License version 3 (GPLv3). '.
				'The full text of this license is available at %1$s. A copy of this document '.
				'is also provided with the plugin source code.',
			'rpbcalendar'),
			'<a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">http://www.gnu.org/licenses/gpl-3.0.html</a>');
		?>
	</p>

</div>
