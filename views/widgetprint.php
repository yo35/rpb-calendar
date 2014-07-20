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


require_once(RPBCALENDAR_ABSPATH . 'views/abstractview.php');


/**
 * Generic view for the widgets in the frontend.
 */
class RPBCalendarViewWidgetPrint extends RPBCalendarAbstractView
{
	public function display()
	{
		// Retrieve the model and the theme data.
		$model = $this->getModel();
		if($model->isWidgetHidden()) {
			return;
		}
		$theme = $model->getTheme();

		// Create the widget box if any.
		echo $theme['before_widget'];

		// Display the title.
		if($model->hasTitle()) {
			echo $theme['before_title'] . htmlspecialchars($model->getTitle()) . $theme['after_title'];
		}

		// Print the widget content.
		include(RPBCALENDAR_ABSPATH . 'templates/widgetprint/' . strtolower($model->getTemplateName()) . '.php');

		// Close the widget box if any.
		echo $theme['after_widget'];
	}
}
