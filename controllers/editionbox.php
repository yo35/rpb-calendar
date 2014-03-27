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


require_once(RPBCALENDAR_ABSPATH . 'controllers/abstractcontroller.php');


/**
 * Controls the display of the edition boxes showing the meta information
 * associated to an event in the backend edition interface.
 */
class RPBCalendarControllerEditionBox extends RPBCalendarAbstractController
{
	public function __construct()
	{
		parent::__construct('EditionBox');
	}


	public function run()
	{
		// Register the link edition box
		add_meta_box(
			'rpbcalendar-admin-eventLink',
			__('Link', 'rpbcalendar'),
			array($this, 'printLinkEditionBox'),
			'rpbcalendar_event',
			'normal',
			'high'
		);

		// Register the date/time edition box
		add_meta_box(
			'rpbcalendar-admin-eventDateTime',
			__('Date/time', 'rpbcalendar'),
			array($this, 'printDateTimeEditionBox'),
			'rpbcalendar_event',
			'side',
			'high'
		);
	}


	/**
	 * Print the edition box showing the link assocated to an event.
	 *
	 * @param object $event
	 */
	public function printLinkEditionBox($event)
	{
		$this->printEditionBox($event, 'LinkEditionBox');
	}


	/**
	 * Print the edition box showing the date and time assocated to an event.
	 *
	 * @param object $event
	 */
	public function printDateTimeEditionBox($event)
	{
		$this->printEditionBox($event, 'DateTimeEditionBox');
	}


	/**
	 * Generic callback for printing an edition box.
	 *
	 * @param object $event
	 * @param string $templateName
	 */
	private function printEditionBox($event, $templateName)
	{
		$model = $this->getModel();
		$model->setEventID($event->ID);
		$model->useTemplate($templateName);
		$this->getView()->display();
	}
}
