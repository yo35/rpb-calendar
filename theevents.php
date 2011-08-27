<?php

	/*
	 * Variables used by the template
	 *  - $events
	 *  - $opts
	 */

	// Utilities
	require_once(RPBCALENDAR_ABSPATH.'tools.php');

	// Loop
	foreach($events as $event) {
		include(RPBCALENDAR_ABSPATH.'theevent.php');
	}
?>
