<?php

// Load wordpress
require_once(dirname(__FILE__).'/../../../wp-load.php');

// Load rpbcalendar
require_once(dirname(__FILE__).'/config.php');



//var_dump('Coucou'.$wpdb->prefix);

require(RPBCALENDAR_ABSPATH.'rpbcalendarpdf.class.php');

//var_dump('Coucou'.$wpdb->prefix);

$pdf = new RpbCalendarPDF();
$pdf->PrintMonthTable(10, 2011);
$pdf->Cell(0, 0, 'Hello World !', 1);
//$pdf->PrintMonthTable(8, 2011);
//$pdf->PrintMonthTable(9, 2011);
$pdf->Output();

?>
