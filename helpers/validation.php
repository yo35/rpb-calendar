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
	 * @return array May be null if the value does not represent a valid date.
	 *         Otherwise, an array with the keys `'year'`, `'month'` and `'day'` is returned.
	 */
	public static function validateDate($value)
	{
		// If the input is a string, parse it.
		if(is_string($value)) {
			if(!preg_match('/^\s*([0-9][0-9][0-9][0-9])-([0-9][0-9])-([0-9][0-9])\s*$/', $value, $matches)) {
				return null;
			}
			$y = intval($matches[1]);
			$m = intval($matches[2]);
			$d = intval($matches[3]);
		}

		// If the input is an array, ensure that the required keys are defined.
		else if(is_array($value)) {
			if(!(array_key_exists('year', $value) && array_key_exists('month', $value) && array_key_exists('day', $value))) {
				return null;
			}
			$y = (int)$value['year' ];
			$m = (int)$value['month'];
			$d = (int)$value['day'  ];
		}

		// Other types of input are rejected.
		else {
			return null;
		}

		// Check the date, and return the result if it is valid.
		if(!checkdate($m, $d, $y)) {
			return null;
		}
		return array('year' => $y, 'month' => $m, 'day' => $d);
	}


	/**
	 * Validate an URL.
	 *
	 * @param mixed $value
	 * @param boolean $allowEmptyString Whether `''` is considered as a valid URL or not (default: false).
	 * @return string May be null if the value does not represent a valid URL.
	 */
	public static function validateURL($value, $allowEmptyString=false)
	{
		if(!is_string($value)) {
			return null;
		}
		$value = trim($value);
		if($allowEmptyString && $value=='') {
			return '';
		}
		else {
			$value = filter_var($value, FILTER_VALIDATE_URL);
			return $value===false ? null : $value;
		}
	}


	/**
	 * Validate a boolean.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateBoolean($value)
	{
		return (is_null($value) || $value==='') ? null : filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}


	/**
	 * Validate a boolean specified as an integer value.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateBooleanFromInt($value)
	{
		$value = filter_var($value, FILTER_VALIDATE_INT);
		if     ($value===0) return false;
		else if($value===1) return true ;
		else                return null ;
	}
}
