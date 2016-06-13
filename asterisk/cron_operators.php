#!/usr/bin/php
<?php

require 'db_conf.php';

$query = 'select operator_id
	    from now_calls_operators
           where last_activity + INTERVAL 15 MINUTE < NOW()';

$oid = $mysqli->query($query)->fetch_row()[0];

$query = 'select rq.queue_num, nco.phone_num
	    from ref_queues_operators rqo inner join ref_queues rq on rqo.queue_id = rq.queue_id
					  inner join now_calls_operators nco on rqo.operator_id = nco.operator_id
	   where nco.operator_id = '.$oid;

$r = $mysqli->query($query);

if ($r) {
	while ( $row = $r->fetch_row() ) {
		  shell_exec( 'sudo asterisk -rx \'queue remove member Local/'.$row[1].'@from-queue/n from '.$row[0].'\' ' );
        	  //shell_exec( 'asterisk -rx \'queue remove member SIP/'. $row[1] .' from '. $row[0] .'\' ' );
        }
}

$mysqli->query("delete from now_calls_operators where last_activity + INTERVAL 15 MINUTE < NOW()");

$mysqli->close();
