<?php

// Utilities
require_once(RPBCALENDAR_ABSPATH.'tools.php');

// Hooks
add_action('plugins_loaded', 'rpbcalendar_check_install');

// Install procedure
function rpbcalendar_install()
{
	rpbcalendar_create_tables();
	add_option('rpbcalendar_version', RPBCALENDAR_VERSION);
}

// Check install procedure
function rpbcalendar_check_install()
{
	if(get_option('rpbcalendar_version')==RPBCALENDAR_VERSION) {
		return;
	}
	rpbcalendar_create_tables();
	update_option('rpbcalendar_version', RPBCALENDAR_VERSION);
}

// Create or upgrade the tables
function rpbcalendar_create_tables()
{
	// Tables definition
	$sql_event_table = "CREATE TABLE " . RPBCALENDAR_EVENT_TABLE . " (
		event_id       INT(11)     NOT NULL AUTO_INCREMENT,
		event_title    VARCHAR(30) NOT NULL,
		event_desc     TEXT        NOT NULL,
		event_begin    DATE        NOT NULL,
		event_end      DATE        NOT NULL,
		event_time     TIME,
		event_link     TEXT,
		event_author   BIGINT(20) UNSIGNED,
		event_category BIGINT(20) UNSIGNED,
		PRIMARY KEY (event_id)
	);";
	$sql_category_table = "CREATE TABLE " . RPBCALENDAR_CATEGORY_TABLE . " (
		category_id               INT(11)     NOT NULL AUTO_INCREMENT,
		category_name             VARCHAR(30) NOT NULL,
		category_text_color       VARCHAR(7)  NOT NULL,
		category_background_color VARCHAR(7)  NOT NULL,
		PRIMARY KEY (category_id)
	);";
	$sql_highday_table = "CREATE TABLE " . RPBCALENDAR_HIGHDAY_TABLE . " (
		highday_id    INT(11)     NOT NULL AUTO_INCREMENT,
		highday_name  VARCHAR(30) NOT NULL,
		highday_month INT(3)      NOT NULL,
		highday_day   INT(3)      NOT NULL,
		PRIMARY KEY (highday_id)
	);";
	$sql_holiday_table = "CREATE TABLE " . RPBCALENDAR_HOLIDAY_TABLE . " (
		holiday_id    INT(11)     NOT NULL AUTO_INCREMENT,
		holiday_name  VARCHAR(30) NOT NULL,
		holiday_begin DATE        NOT NULL,
		holiday_end   DATE        NOT NULL,
		PRIMARY KEY (holiday_id)
	);";

	// Execute
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_event_table   );
	dbDelta($sql_category_table);
	dbDelta($sql_highday_table );
	dbDelta($sql_holiday_table );
}

?>
