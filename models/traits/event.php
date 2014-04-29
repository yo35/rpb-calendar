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


require_once(RPBCALENDAR_ABSPATH . 'models/traits/abstracttrait.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/validation.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/today.php');
require_once(RPBCALENDAR_ABSPATH . 'helpers/color.php');


/**
 * Meta information associated to an event.
 */
class RPBCalendarTraitEvent extends RPBCalendarAbstractTrait
{
	private static $data = array();
	private $eventID = -1;
	private $event;
	private $categoryTrait;
	private $defaultColorsTrait;


	/**
	 * ID of the currently selected event.
	 *
	 * @return int
	 */
	public function getEventID()
	{
		return $this->eventID;
	}


	/**
	 * Change the currently selected event.
	 *
	 * @param int $eventID ID of the newly selected event.
	 */
	public function setEventID($eventID)
	{
		if($this->eventID==$eventID) {
			return;
		}
		$this->eventID = $eventID;
		$this->event = null;
	}


	/**
	 * Ensure that the object `$this->event` is equal to `self::$data[$this->eventID]`.
	 */
	private function ensureEventLoaded()
	{
		if(isset($this->event)) {
			return;
		}
		if(!isset(self::$data[$this->eventID])) {
			self::$data[$this->eventID] = new stdClass;
		}
		$this->event = self::$data[$this->eventID];
	}


	/**
	 * Return the categories associated to the currently selected event.
	 *
	 * @return array Array of objects. Each object `$c` corresponds to a category, and has the following fields:
	 *  - `$c->ID` (int): ID of the category,
	 *  - `$c->name` (string): name of the category,
	 *  - `$c->color` (string): color associated to the category, determined hierarchically.
	 */
	public function getEventCategories()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->categories)) {
			$this->event->categories = array();
			$categories = get_the_terms($this->eventID, 'rpbevent_category');
			if(is_array($categories)) {
				foreach($categories as $category) {
					$this->event->categories[] = (object) array(
						'ID'    => $category->term_id,
						'name'  => $category->name,
						'color' => $this->retrieveCategoryColor($category->term_id)
					);
				}
			}
		}
		return $this->event->categories;
	}


	/**
	 * Retrieve the color associated to the given category.
	 *
	 * @param int $categoryID
	 * @return string
	 */
	private function retrieveCategoryColor($categoryID)
	{
		$this->ensureCategoryTraitLoaded();
		$this->categoryTrait->setCategoryID($categoryID);
		return $this->categoryTrait->getCategoryInheritedColor();
	}


	/**
	 * Style attribute that must be apply to the event when rendering.
	 *
	 * @return string
	 */
	public function getEventBackgroundStyle()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->backgroundStyle)) {
			$this->event->backgroundStyle = $this->buildEventBackgroundStyle();
		}
		return $this->event->backgroundStyle;
	}


	/**
	 * Determine the style attribute that must be applied to the event when rendering.
	 *
	 * @return string
	 */
	private function buildEventBackgroundStyle()
	{
		$colors = array();
		foreach($this->getEventCategories() as $category) {
			$colors[] = $category->color;
		}
		$colors = array_unique($colors);
		sort($colors);
		switch(count($colors)) {

			// 0-color => the event does not belong to any category.
			case 0:
				$this->ensureDefaultColorsTraitLoaded();
				return self::uniformBackgroundStyle($this->defaultColorsTrait->getDefaultEventColor());

			// 1-color
			case 1:
				return self::uniformBackgroundStyle($colors[0]);

			// 2-colors
			case 2:
				return self::bicolorBackgroundStyle($colors[0], $colors[1]);

			// Too many colors!
			default:
				return self::fallbackBackgroundStyle();
		}
	}


	/**
	 * Style attribute for an event block with a uniform color.
	 *
	 * @param string $color
	 * @return string
	 */
	private static function uniformBackgroundStyle($color)
	{
		$lightness = RPBCalendarHelperColor::lightness($color);
		$textColor = $lightness>0.5 ? 'black' : 'white';
		return "background-color:$color; color:$textColor;";
	}


	/**
	 * Style attribute for an event block with 2 colors.
	 *
	 * @param string $color1
	 * @param string $color2
	 * @return string
	 */
	private static function bicolorBackgroundStyle($color1, $color2)
	{
		$lightness1 = RPBCalendarHelperColor::lightness($color1);
		$lightness2 = RPBCalendarHelperColor::lightness($color2);
		$textColor = ($lightness1 + $lightness2)/2 > 0.5 ? 'black' : 'white';
		$url = RPBCALENDAR_URL . '/ajax/color2.php?c1=' . urlencode($color1) . '&c2=' . urlencode($color2);
		return "background-image:url($url); background-repeat:repeat; color:$textColor;";
	}


	/**
	 * Style attribute for an event block with too many colors.
	 *
	 * @return string
	 */
	private static function fallbackBackgroundStyle()
	{
		$url = RPBCALENDAR_URL . '/images/fallback-pattern.png';
		return "background-image:url($url); background-repeat:repeat; color:black;";
	}


	/**
	 * Create a new instance of the category trait, if necessary.
	 */
	private function ensureCategoryTraitLoaded()
	{
		if(isset($this->categoryTrait)) {
			return;
		}
		$this->categoryTrait = RPBCalendarHelperLoader::loadTrait('Category');
	}


	/**
	 * Create a new instance of the default colors trait, if necessary.
	 */
	private function ensureDefaultColorsTraitLoaded()
	{
		if(isset($this->defaultColorsTrait)) {
			return;
		}
		$this->defaultColorsTrait = RPBCalendarHelperLoader::loadTrait('DefaultColors');
	}


	/**
	 * Return the web link associated to the currently selected event.
	 *
	 * @return string Either a valid URL or an empty string.
	 */
	public function getEventLink()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->link)) {
			$value = RPBCalendarHelperValidation::validateURL(get_post_meta($this->eventID, 'rpbevent_link', true), true);
			$this->event->link = isset($value) ? $value : '';
		}
		return $this->event->link;
	}


	/**
	 * Return the begin date of the currently selected event.
	 *
	 * @return int Timestamp
	 */
	public function getEventDateBegin()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->dateBegin)) {
			$value = RPBCalendarHelperValidation::validateDate(get_post_meta($this->eventID, 'rpbevent_date_begin', true));
			$this->event->dateBegin = isset($value) ? $value : RPBCalendarHelperToday::timestamp();
		}
		return $this->event->dateBegin;
	}


	/**
	 * Return the end date of the currently selected event.
	 *
	 * @return int Timestamp
	 */
	public function getEventDateEnd()
	{
		$this->ensureEventLoaded();
		if(!isset($this->event->dateEnd)) {
			$value = RPBCalendarHelperValidation::validateDate(get_post_meta($this->eventID, 'rpbevent_date_end', true));
			$dateBegin = $this->getEventDateBegin();
			$this->event->dateEnd = (isset($value) && $value>=$dateBegin) ? $value : $dateBegin;
		}
		return $this->event->dateEnd;
	}


	/**
	 * Return the begin date of the currently selected event formatted as a string.
	 *
	 * @param string $format Date format pattern, as specified by the WP `date_i18n()` function.
	 * @return string
	 */
	public function getEventDateBeginAsString($format = 'Y-m-d')
	{
		return date_i18n($format, $this->getEventDateBegin());
	}


	/**
	 * Return the end date of the currently selected event formatted as a string.
	 *
	 * @param string $format Date format pattern, as specified by the WP `date_i18n()` function.
	 * @return string
	 */
	public function getEventDateEndAsString($format = 'Y-m-d')
	{
		return date_i18n($format, $this->getEventDateEnd());
	}
}
