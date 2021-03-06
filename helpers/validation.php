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
 * Validation functions.
 */
abstract class RPBCalendarHelperValidation
{
	/**
	 * Validate a date.
	 *
	 * @param mixed $value
	 * @return int Unix timestamp, with hour, minutes and seconds set to 0.
	 */
	public static function validateDate($value)
	{
		// If the input represents a numerical value, it is assumed that it correspond
		// to a timestamp. In this case, the hour/minute/second information is hidden.
		if(is_numeric($value)) {
			return floor($value / 86400) * 86400; // 86400 = 24*60*60 = number of seconds in a day.
		}

		// Otherwise, if the input is a string, parse it.
		else if(is_string($value)) {
			if(!preg_match('/^\s*([0-9]{4})-([0-9]{2})-([0-9]{2})\s*$/', $value, $matches)) {
				return null;
			}
			$y = intval($matches[1]);
			$m = intval($matches[2]);
			$d = intval($matches[3]);

			// The day/month/year numbers must correspond to a valid Gregorian date.
			if(!checkdate($m, $d, $y)) {
				return null;
			}

			// Generate and return the appropriate timestamp.
			return mktime(0, 0, 0, $m, $d, $y);
		}

		// Other types of input are rejected.
		else {
			return null;
		}
	}


	/**
	 * Validate a string representing a color.
	 *
	 * @param mixed $value
	 * @param boolean $allowEmptyString Whether `''` is considered as a valid color or not (default: false).
	 * @return string May be null is the value is not valid.
	 */
	public static function validateColor($value, $allowEmptyString=false)
	{
		if(!is_string($value)) {
			return null;
		}
		$value = trim($value);
		if($allowEmptyString && $value==='') {
			return '';
		}
		else {
			return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? strtolower($value) : null;
		}
	}


	/**
	 * Validate a string.
	 *
	 * @param mixed $value
	 * @param boolean $trim Whether the value should be trimmed (true by default).
	 * @return string Never null.
	 */
	public static function validateString($value, $trim=true)
	{
		$value = (string) $value;
		if($trim) {
			$value = trim($value);
		}
		return $value;
	}


	/**
	 * Validate a non-empty string.
	 *
	 * @param mixed $value
	 * @param boolean $trim Whether the value should be trimmed (true by default).
	 * @return string
	 */
	public static function validateNonEmptyString($value, $trim=true)
	{
		$value = self::validateString($value, $trim);
		return $value === '' ? null : $value;
	}


	/**
	 * Validate an integer.
	 *
	 * @param mixed $value
	 * @param int $min Minimum value (optional).
	 * @param int $max Maximum value (optional).
	 * @return int May be null is the value is not valid.
	 */
	public static function validateInteger($value, $min=null, $max=null)
	{
		$value = filter_var($value, FILTER_VALIDATE_INT);
		return $value===false ? null : max($max===null ? $value : min($value, $max), $min);
	}


	/**
	 * Validate an array of integers.
	 *
	 * @param mixed $value
	 * @return array May be null is the value is not valid.
	 */
	public static function validateIntegerArray($value)
	{
		if(is_string($value)) {
			$value = trim($value);
			if($value === '') {
				return array();
			}
			$value = explode(',', $value);
		}
		if(!is_array($value)) {
			return null;
		}
		foreach($value as &$item) {
			$item = filter_var($item, FILTER_VALIDATE_INT);
			if($item===false) {
				return null;
			}
		}
		return $value;
	}


	/**
	 * Validate a boolean.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateBoolean($value)
	{
		return ($value===null || $value==='') ? null : filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}
}
