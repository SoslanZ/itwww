<?php
require 'db_conf.php';

// Asterisk get wav record

$rec = $_GET['rec'];
$rec_path = "/var/spool/asterisk/monitor";

$recfile_name = '';

if ($result = $mysqli->query("SELECT date_format(date_call,'%Y') as year,
				     date_format(date_call,'%m') as month,
				     date_format(date_call,'%d') as day,
				     dial_record
                                FROM log_calls_details 
                               WHERE uid = '$rec'
                               LIMIT 1")) {

        while($obj = $result->fetch_object()) {

		$rec_path.= "/".$obj->year;
		$rec_path.= "/".$obj->month;
		$rec_path.= "/".$obj->day;
		$recfile_name = $obj->dial_record;

        }

    }

$result->close();

$file = $rec_path."/".$recfile_name;

if (file_exists($file)) {

	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($file));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
}
