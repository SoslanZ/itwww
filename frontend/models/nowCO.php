<?php

namespace frontend\models;
use yii\db\Expression;

class nowCO extends \yii\db\ActiveRecord
{    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        
        return 'now_calls_operators';
        
    }

    public static function setCloseCard($data) {
        
        return nowCO::updateAll(['is_answering' => 0 ],[ 'php_session_id' => $data['phpid']]);
        
    }
    
    public static function getJsonCallInfo($data) {
        
        self::updateAll(['last_activity' => new Expression('NOW()')],['php_session_id'=>$data['phpid']]); 
        
        $callInfo = (new \yii\db\Query())
                            ->select('nco.operator_id,
                                      nco.phone_num,
                                      nco.queue_num,
                                      nco.is_ringing,
                                      nco.is_answering,
                                      nco.cid,
                                      now() as server_time,
                                      nco.uid,
                                      rq.queue_url,
                                      ro.add_info
                                      ')
                            ->from('now_calls_operators nco')
                            ->innerJoin('ref_operators ro', 'nco.operator_id = ro.operator_id')
                            ->leftJoin('ref_queues rq', ' nco.queue_num = rq.queue_num')
                            ->where( [ 'nco.php_session_id' => $data['phpid'] ] )
                            ->createCommand();
        
        $row = $callInfo->queryOne();
        
        if (!$row) {
                return [
                    'isL' => false
                ];
            } else {
        
        // %UID% , %CNUM% , %QNUM% , %PNUM%, %OID%
        $queueURL = str_replace( '%UID%'  ,  $row['uid'], $row['queue_url'] );
        $queueURL = str_replace( '%OID%'  ,  $row['add_info'], $queueURL );
        $queueURL = str_replace( '%QNUM%' , $row['queue_num'], $queueURL );
        $queueURL = str_replace( '%CNUM%' , $row['cid'], $queueURL );
        $queueURL = str_replace( '%PNUM%' , $row['phone_num'], $queueURL );
        
                
        return [
                    'isL' => true,
                    'aI' => $row['add_info'],
                    'oid' => $row['operator_id'],
                    'isR' => $row['is_ringing'],
                    'isA' => $row['is_answering'],
                    'pN' => $row['phone_num'],
                    'cN' => $row['cid'],
                    'qN' => $row['queue_num'],
                    'sT' => $row['server_time'],
                    'qU' => $queueURL,
                    'uid' => $row['uid']
                ];
        }
    }
    
}
