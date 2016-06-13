<?php
require 'db_conf.php';

function addQueueMember($qnum,$phone,$penalty) {
	$r = shell_exec( 'asterisk -rx \'queue add member SIP/' .$phone. ' to ' .$qnum. ' penalty ' .$penalty. '\' ' );
	return $r;
}

function delQueueMember($qnum,$phone) {
	$r = shell_exec( 'asterisk -rx \'queue remove member SIP/' .$phone. ' from ' .$qnum. '\' ' );
        return $r;
}

function addQueuesByPhone($phone) {
	
}

function addPhonesByQueue($qnum) {
	
}

function delQueuesByPhone($phone) {
	
}

function delPhonesByQueue($qnum) {
	
}

/*echo addQueueMember(1000,103,0);
echo delQueueMember(1000,103);*/
