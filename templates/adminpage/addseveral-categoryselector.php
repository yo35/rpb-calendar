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

<?php if($model->hasCategory()): ?>
	<ul>

		<?php while($model->fetchCategory()): ?>
			<li>

				<input type="checkbox" name="rpbevent_categories_0[]" value="<?php echo htmlspecialchars($model->getCategoryID()); ?>"
					id="rpbcalendar-eventCategoryField-category<?php echo htmlspecialchars($model->getCategoryID()); ?>-0"
				/>
				<label for="rpbcalendar-eventCategoryField-category<?php echo htmlspecialchars($model->getCategoryID()); ?>-0">
					<span class="rpbcalendar-categoryTag" style="background-color:<?php echo htmlspecialchars($model->getCategoryInheritedColor()); ?>"></span>
					<?php echo htmlspecialchars($model->getCategoryName()); ?>
				</label>

				<?php
					$model->beginFetchCategoryChildren();
					include(RPBCALENDAR_ABSPATH . 'templates/adminpage/addseveral-categoryselector.php');
					$model->endFetchCategoryChildren();
				?>

			</li>
		<?php endwhile; ?>

	</ul>
<?php endif; ?>
