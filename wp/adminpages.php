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


/**
 * Register the plugin administration pages in the Wordpress backend.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations.
 */
abstract class RPBCalendarAdminPages
{
	/**
	 * Register the plugin administration pages. Must be called only once.
	 */
	public static function register()
	{
		$parentSlug = 'edit.php?post_type=rpbevent';


		// Page "add several"
		add_submenu_page($parentSlug,
			__('Add several events', 'rpbcalendar'),
			__('Add several', 'rpbcalendar'),
			'edit_posts', 'rpbcalendar-add-several', array(__CLASS__, 'callbackAddSeveral')
		);
		self::moveSubmenu($parentSlug, 'post-new.php?post_type=rpbevent', 'rpbcalendar-add-several');


		// Page "options"
		add_submenu_page($parentSlug,
			sprintf(__('Settings of the %1$s plugin', 'rpbcalendar'), 'RPB Calendar'),
			__('Settings', 'rpbcalendar'),
			'manage_options', 'rpbcalendar-options', array(__CLASS__, 'callbackPageOptions')
		);


		// Page "about"
		add_submenu_page($parentSlug,
			sprintf(__('About %1$s', 'rpbcalendar'), 'RPB Calendar'),
			__('About', 'rpbcalendar'),
			'manage_options', 'rpbcalendar-about', array(__CLASS__, 'callbackPageAbout')
		);
	}


	public static function callbackAddSeveral () { echo 'TODO'; }
	public static function callbackPageOptions() { self::printAdminPage('AdminPageOptions'); }
	public static function callbackPageAbout  () { self::printAdminPage('AdminPageAbout'  ); }


	/**
	 * Load and print the plugin administration page defined by the model `$modelName`.
	 *
	 * @param string $modelName
	 */
	private static function printAdminPage($modelName)
	{
		require_once(RPBCALENDAR_ABSPATH . 'controllers/adminpage.php');
		$controller = new RPBCalendarControllerAdminPage($modelName);
		$controller->run();
	}


	/**
	 * Move a sub-menu item within a given menu.
	 *
	 * @param string $parentSlug Slug of the parent menu.
	 * @param string $anchorMenuSlug Slug of the sub-menu item after which the moved item will be set.
	 * @param string $targetMenuSlug Slug of the sub-menu item to move.
	 */
	private static function moveSubmenu($parentSlug, $anchorMenuSlug, $targetMenuSlug)
	{
		global $submenu;

		// Retrieve the page indexes in the sub-menu.
		$anchorIndex = self::findSubmenuIndex($parentSlug, $anchorMenuSlug);
		$targetIndex = self::findSubmenuIndex($parentSlug, $targetMenuSlug);
		if($anchorIndex === null || $targetIndex === null) {
			return;
		}

		// Build the new sub-menu.
		$currentSubmenu = $submenu[$parentSlug];
		$newSubmenu = array();
		foreach($currentSubmenu as $index => $item) {
			if($index === $targetIndex) {
				continue;
			}
			$newSubmenu[$index] = $item;
			if($index === $anchorIndex) {
				$newSubmenu[$targetIndex] = $currentSubmenu[$targetIndex];
			}
		}

		// Replace the old sub-menu.
		$submenu[$parentSlug] = $newSubmenu;
	}


	/**
	 * Find the index of the sub-menu item corresponding to `$menuSlug` in the menu corresponding to `$parentSlug`.
	 *
	 * @param string $parentSlug
	 * @param string $menuSlug
	 * @return int|null Null is returned if the sub-menu is not found.
	 */
	private static function findSubmenuIndex($parentSlug, $menuSlug)
	{
		global $submenu;

		// Check that the parent menu is actually defined.
		if(!isset($submenu[$parentSlug]) || !is_array($submenu[$parentSlug])) {
			return null;
		}

		// Visit each sub-menu in the parent menu.
		foreach($submenu[$parentSlug] as $k => $item) {
			if($item[2] === $menuSlug) {
				return $k;
			}
		}
		return null;
	}
}
