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


require_once(RPBCALENDAR_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCALENDAR_ABSPATH.'helpers/validation.php');


/**
 * Load the link meta information associated to an event.
 */
class RPBCalendarTraitEventLink extends RPBCalendarAbstractTrait
{
	private $event;
	private $link;
	private $linkLoaded = false;


	/**
	 * Constructor.
	 *
	 * @param object $event
	 */
	public function __construct($event)
	{
		$this->event = $event;
	}


	/**
	 * Return the web link associated to the given event.
	 *
	 * @return string Null is returned if no link is defined.
	 */
	public function getEventLink()
	{
		if(!$this->linkLoaded) {
			$this->link = RPBCalendarHelperValidation::validateURL(get_post_meta($event->ID, 'link', true));
			$this->linkLoaded = true;
		}
		return $this->link;
	}
}
