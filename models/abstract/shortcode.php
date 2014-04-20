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
 * Base class for the models used to render the Wordpress shortcodes defined by the plugin.
 */
abstract class RPBCalendarAbstractShortcodeModel extends RPBCalendarAbstractModel
{
	private $atts   ;
	private $content;
	private $contentFiltered = false;
	private $shortcodeName;
	private $itemID;


	/**
	 * Constructor.
	 *
	 * @param array $atts Attributes passed with the shortcode.
	 * @param string $content Shortcode enclosed content.
	 */
	public function __construct($atts, $content)
	{
		parent::__construct();
		$this->atts    = is_array($atts) ? $atts : array();
		$this->content = $content;
		$this->useTemplate($this->getShortcodeName());
	}


	/**
	 * Use the "Shortcode" view by default.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'Shortcode';
	}


	/**
	 * Name of the shortcode.
	 *
	 * @return string
	 */
	public function getShortcodeName()
	{
		if(!isset($this->shortcodeName)) {
			$this->shortcodeName = preg_match('/^Shortcode(.*)$/', $this->getName(), $matches) ? $matches[1] : '';
		}
		return $this->shortcodeName;
	}


	/**
	 * Return the attributes passed with the shortcode.
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->atts;
	}


	/**
	 * Return the enclosed shortcode content.
	 *
	 * @return string
	 */
	public function getContent()
	{
		if(!$this->contentFiltered) {
			$this->content = $this->filterShortcodeContent($this->content);
			$this->contentFiltered = true;
		}
		return $this->content;
	}


	/**
	 * Pre-process the shortcode enclosed content, for instance to get rid of the
	 * auto-format HTML tags introduced by the Wordpress engine. By default, this
	 * function returns the raw content "as-is". The function should be re-implemented
	 * in the derived models.
	 *
	 * @param string $content Raw content.
	 * @return string Filtered content.
	 */
	protected function filterShortcodeContent($content)
	{
		return $content;
	}


	/**
	 * Return a string that may be used as an HTML ID (as-is or as a prefix) to tag
	 * the HTML nodes that needs to.
	 *
	 * @return string
	 */
	public function getItemID()
	{
		if(!isset($this->itemID)) {
			$this->itemID = self::allocateID();
		}
		return $this->itemID;
	}


	/**
	 * Allocate a new ID for a HTML node.
	 *
	 * @return string
	 */
	private static function allocateID()
	{
		++self::$idCounter;
		return 'rpbcalendar-item' . self::$idCounter;
	}


	/**
	 * ID counter.
	 */
	private static $idCounter = 0;
}
