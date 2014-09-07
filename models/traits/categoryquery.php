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


require_once(RPBCALENDAR_ABSPATH . 'models/traits/category.php');


/**
 * Fetch the event categories that meet a certain set of criterias.
 */
class RPBCalendarTraitCategoryQuery extends RPBCalendarTraitCategory
{
	private $currentCategoryList;
	private $currentCategoryIndex = -1;
	private $currentCategory;
	private $queryStack = array();


	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->currentCategoryList = get_terms('rpbevent_category', array(
			'hide_empty' => false,
			'parent'     => 0 // Fetch the top-level category only.
		));
	}


	/**
	 * Whether some category matching the required criterias have been founded or not.
	 *
	 * @return boolean
	 */
	public function hasCategory()
	{
		return !empty($this->currentCategoryList);
	}


	/**
	 * Try to fetch the next matching category.
	 *
	 * @return boolean True if the next category has been fetched, false after the last event.
	 */
	public function fetchCategory()
	{
		++$this->currentCategoryIndex;
		$this->resetCurrentCategory();
		return isset($this->currentCategory);
	}


	/**
	 * Initiate a sub-query to fetch the children of the current category. The state of the current query is saved,
	 * and will be restored by calling method `#endFetchCategoryChildren()`.
	 */
	public function beginFetchCategoryChildren()
	{
		// Save the current category list and index.
		$this->queryStack[] = array(
			'list'  => $this->currentCategoryList,
			'index' => $this->currentCategoryIndex
		);

		// Fetch the children of the current category.
		$this->currentCategoryList = get_terms('rpbevent_category', array(
			'hide_empty' => false,
			'parent'     => $this->getCategoryID()
		));
		$this->currentCategoryIndex = -1;
		$this->resetCurrentCategory();
	}


	/**
	 * Terminate the current sub-query, and restore the object state that has been saved by the last call
	 * to `#beginFetchCategoryChildren()`.
	 */
	public function endFetchCategoryChildren()
	{
		// Nothing to do if the query stack is empty -> this case should not happen
		// if every call to this method is preceded by a call to `#beginFetchCategoryChildren()`.
		if(empty($this->queryStack)) {
			return;
		}

		// Restore the last saved state.
		$state = array_pop($this->queryStack);
		$this->currentCategoryList  = $state['list' ];
		$this->currentCategoryIndex = $state['index'];
		$this->resetCurrentCategory();
	}


	/**
	 * Reset the pointer to the current category given the current category list and index.
	 */
	private function resetCurrentCategory()
	{
		if($this->currentCategoryIndex >= 0 && $this->currentCategoryIndex < count($this->currentCategoryList)) {
			$this->currentCategory = $this->currentCategoryList[$this->currentCategoryIndex];
			$this->setCategoryID($this->currentCategory->term_id);
		}
		else {
			$this->currentCategory = null;
			$this->setCategoryID(-1);
		}
	}
}
