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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/adminpage.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/date.php');


/**
 * Model for the "Add several events" page.
 */
class RPBCalendarModelAdminPageAddSeveral extends RPBCalendarAbstractModelAdminPage
{
	private $initialEventDateFields;


	public function __construct()
	{
		parent::__construct();
		$this->loadTrait('AdminPageURLs');
	}


	/**
	 * ID of the current user.
	 *
	 * @return int
	 */
	public function getCurrentUserID()
	{
		return get_current_user_id();
	}


	/**
	 * Initial value of the event begin/end fields.
	 *
	 * @return string
	 */
	public function getInitialEventDateFields()
	{
		if(!isset($this->initialEventDateFields)) {
			$this->initialEventDateFields = date_i18n('Y-m-d', RPBCalendarHelperDate::today());
		}
		return $this->initialEventDateFields;
	}
}
