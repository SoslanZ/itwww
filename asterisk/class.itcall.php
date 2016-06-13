<?php

class itCall {

	private $mysqli;

	private $action;
	private $guid;
	private $uid;
	private $cid;
	private $did;
	private $qnum;
	private $dialstatus;
	private $record;

	private $operator_id;
	private $phone_num;

	function __construct($_mysqli,$_asterisk_obj) {

		$this->mysqli = $_mysqli;

		$this->operator_id = 'NULL';
		$this->phone_num = 'NULL';

		list($empty,
			$this->action,
			$this->guid,
			$this->uid,
			$this->cid,
			$this->did,
			$this->qnum,
			$this->dialstatus,
			$this->record) = $_asterisk_obj;

	}

	public function getAction() {

		return $this->action;

	}

	private function getOperatorByDid() {

		$r = $this->mysqli->query("select operator_id from now_calls_operators where phone_num = '$this->did' ");
		if ($r) {
			while ($row = $r->fetch_row()) {
				$this->operator_id = intval( $row['0'] );
			}
		}

		return $this->operator_id;
	}

	private function getOperatorByUid() {

		$r = $this->mysqli->query("select operator_id from log_calls_details where uid = '$this->uid' ");
		if ($r) {
			while ($row = $r->fetch_row()) {
                                $this->operator_id = intval( $row['0'] );
                        }
		}

		return $this->operator_id;
	}

	private function getPhoneNumByUid() {

		$this->did = 'NULL';
		$r = $this->mysqli->query("select did from log_calls_details where uid = '$this->uid' ");
                if ($r) {
                        while ($row = $r->fetch_row()) {
                                $this->did = intval( $row['0'] );
                        }
                }

                return $this->did;
	}

	public function createCall() { 

		$this->mysqli->query("INSERT INTO log_calls(guid,cid,did,date_call) VALUES('$this->guid','$this->cid','$this->did',NOW())"); 

	}

	public function dialOperator() {

		$op = self::getOperatorByDid();
		$this->mysqli->query( "INSERT INTO log_calls_details(guid,uid,cid,did,queue_num,operator_id,date_call)
                                 VALUES('$this->guid','$this->uid','$this->cid','$this->did','$this->qnum',$op,NOW() )" );

		if ($this->qnum) {

			$this->mysqli->query(" UPDATE now_calls_operators
					    	  set is_ringing = 1,
					       	      is_answering = 0,
					       	      cid = '$this->cid',
						      uid = '$this->uid',
					              queue_num = $this->qnum
					        where phone_num = $this->did
					          and operator_id = $op ");
		}
	}

	public function answerOperator() {

		$this->mysqli->query("UPDATE log_calls_details set date_answer = NOW() where uid = '$this->uid' and guid = '$this->guid'");

		if($this->qnum) {

			$op = self::getOperatorByUid();
                        $phonenum = self::getPhoneNumByUid();

                        $this->mysqli->query(" UPDATE now_calls_operators
                                                  set is_answering = 1
                                                where operator_id = $op
                                                  and phone_num = '$phonenum' ");
		}
	}

	public function hangup() {

                $this->mysqli->query("UPDATE log_calls_details
                                   	 SET date_hangup = NOW(),
                                       	     dial_status = '$this->dialstatus',
                                       	     dial_record = '$this->record'
                                       WHERE uid = '$this->uid'
                                         and guid = '$this->guid'");
		if ($this->qnum) {

			$op = self::getOperatorByUid();
			$phonenum = self::getPhoneNumByUid();

	                $this->mysqli->query(" UPDATE now_calls_operators
	        	                          set is_ringing = 0,
                	                              cid = '',
                        	                      queue_num = '',
						      uid = ''
                                	        where operator_id = $op
					 	  and phone_num = '$phonenum' ");
		}
	}
}
