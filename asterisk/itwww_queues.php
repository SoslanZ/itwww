#!/usr/bin/php
<?php

require 'db_conf.php';

$dialplan = '[from-queue]';
$dialplan .= '<<<DIALPLAN';

if ($result = $mysqli->query('SELECT queue_num FROM ref_queues')) {

    while ($row = $result->fetch_row()) {
        $dialplan .= '
			exten => ' . $row[0] . ',1,Noop( ITwww [from-queue-overrides] Enter in queue ${EXTEN})
			exten => ' . $row[0] . ',n,Set(__QNUM=${EXTEN})
			exten => ' . $row[0] . ',n,Goto(from-internal,${QAGENT},1)
		     ';
    }

    $result->close();
}

$dialplan .= '
	exten => _.,1,Set(QAGENT=${EXTEN})
	exten => _.,n,Goto(${NODEST},1)
';

$dialplan .= 'DIALPLAN';

$mysqli->close();

echo $dialplan;
