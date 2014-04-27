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

<h2><?php echo htmlspecialchars($model->getTitle()); ?></h2>

<div id="rpbcalendar-admin-javascriptWarning" class="error">
	<p><?php
		_e('To work properly, the RPB Calendar plugin needs javascript to be activated in your browser.',
			'rpbcalendar');
	?></p>
</div>

<?php if($model->getPostMessage()!=''): ?>
	<div class="updated">
		<p><?php echo htmlspecialchars($model->getPostMessage()); ?></p>
	</div>
<?php endif; ?>

<script type="text/javascript">

	jQuery('#rpbcalendar-admin-javascriptWarning').addClass('rpbcalendar-invisible');

</script>
