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


require_once(RPBCALENDAR_ABSPATH . 'models/abstract/abstractmodel.php');


/**
 * Base class for the models used to process the update requests of custom posts and categories.
 */
abstract class RPBCalendarAbstractModelCustomPostUpdate extends RPBCalendarAbstractModel
{
	private $methodName;


	/**
	 * Constructor.
	 *
	 * @param string $slug Post type or taxonomy slug.
	 * @param boolean $isCategory Whether it is a post type or a category update request.
	 * @param string $traitName Trait to load.
	 * @param string $methodName Method to call in the trait to process the request.
	 */
	public function __construct($slug, $isCategory, $traitName, $methodName)
	{
		parent::__construct();

		// Determine whether the requests will be skipped or not.
		$field = $isCategory ? 'taxonomy' : 'post_type';
		if(isset($_POST[$field]) && $_POST[$field] === $slug) {
			$this->loadTrait($traitName);
			$this->methodName = $methodName;
		}
	}


	/**
	 * Process the request.
	 */
	public function processRequest($id)
	{
		if(isset($this->methodName)) {
			$methodName = $this->methodName;
			$this->$methodName($id);
		}
	}
}
