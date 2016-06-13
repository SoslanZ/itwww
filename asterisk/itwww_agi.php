#!/usr/bin/php
<?php

require 'db_conf.php';
require 'class.itcall.php';

$c = new itCall( $mysqli, $argv );

switch ( $c->getAction() ) {
	case 'trunk':
		$c->createCall();
	        break;
	case 'agent':
		$c->createCall();
		break;
	case 'hangup':
		$c->Hangup();
		break;
	case 'dialone':
		$c->dialOperator();
                break;
	case 'agentanswer':
		$c->answerOperator();
                break;
}

$mysqli->close();
