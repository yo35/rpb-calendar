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


// Load the validation functions
require_once(dirname(__FILE__).'/helpers/validation.php');


// Build the answer
function do_validation()
{
	// Default answer
	$answer = array('result' => false);

	// Retrieve the method to use
	if(!isset($_GET['method'])) {
		$answer['error'] = 'No validation method defined.';
		return $answer;
	}
	$method = $_GET['method'];
	$answer['method'] = $method;
	if(!method_exists('RPBCalendarHelperValidation', $method)) {
		$answer['error'] = 'Bad validation method.';
		return $answer;
	}

	// Retrieve the value to validate
	if(!isset($_GET['value'])) {
		$answer['error'] = 'No value defined.';
		return $answer;
	}
	$value = $_GET['value'];
	$answer['value'] = $value;

	// Parse the additional arguments
	$callback = array('RPBCalendarHelperValidation', $method);
	$args     = array($value);
	if(isset($_GET['arg1'])) {
		$answer['arg1'] = $_GET['arg1'];
		array_push($args, $_GET['arg1']);
		if(isset($_GET['arg2'])) {
			$answer['arg2'] = $_GET['arg2'];
			array_push($args, $_GET['arg2']);
			if(isset($_GET['arg3'])) {
				$answer['arg3'] = $_GET['arg3'];
				array_push($args, $_GET['arg3']);
			}
		}
	}

	// Validate the result
	$answer['result'] = !is_null(call_user_func_array($callback, $args));
	return $answer;
}


// Return a JSON-encoded string.
header('Content-Type: application/json');
echo json_encode(do_validation());
