<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;
use yii\web\Session;

class OperAuth extends ActiveRecord implements IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_operators';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['operator_id' => $id]);//, 'status' => self::STATUS_ACTIVE*/]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public static function findByUsername($username)
    {
        //return self::findOne(['oper_id'=>$username]);
    }

    public static function findByPin($pin)
    {
        return self::findOne(['operator_pin'=>$pin]);
    }
    
    public function getId() {
	return $this->operator_id;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        //return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        //return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        //return Yii::$app->security->validatePassword($password, $this->password_hash);
        return $this->operator_pin === $password;
    }

    public function afterLogin($event) {
        
        $session = new Session;
        $session->open();
        
        // clear prev oper_id / phone_num from auth DB
        nowCO::deleteAll( [ 'phone_num' => $session['agent_phone'] ] );
        nowCO::deleteAll( [ 'operator_id' => $this->getId() ] );
        
        $cp = new nowCO();
        $cp->phone_num = $session['agent_phone'];
        $cp->operator_id = $this->getId();
        $cp->last_activity = new Expression('NOW()');
        $cp->php_session_id = $session->id;
        
        $cp->save();
        
        self::enterQueues();
        
    }
    
    public function afterLogout($event) {
        
        $session = new Session;
        $session->open();
        
        self::leaveQueues();
        
        nowCO::deleteAll( [ 'php_session_id' => $session->id ] );
        
    }
    
    public function enterQueues() {

        $asteriskQueues = (new \yii\db\Query())
                            ->select('rq.queue_num, nco.phone_num, rqo.queue_penalty')
                                 ->from('ref_queues_operators rqo')
                            ->innerJoin('ref_queues rq', 'rqo.queue_id = rq.queue_id')
                            ->innerJoin('now_calls_operators nco','rqo.operator_id = nco.operator_id')
                            ->where( [ 'nco.operator_id' => $this->getId() ] )
                            ->createCommand();
        // Asterisk shell
        foreach ( $rows = $asteriskQueues->queryAll() as $qo) {
          //var_dump($qo);

          //shell_exec( 'asterisk -rx \'queue add member Local/SIP-' . $qo['phone_num']. '@itwwwqueueagent to ' .$qo['queue_num']. ' penalty 0\' ' );
         shell_exec( 'sudo asterisk -rx' .
                      ' \'queue add member Local/' . $qo['phone_num'] . '@from-queue/n' .
                      ' to ' . $qo['queue_num']. 
                      ' penalty ' . $qo['queue_penalty'] . ' as ' . $qo['phone_num'] .
                      ' state_interface hint:'. $qo['phone_num'] .'@ext-local' .
                      '\' ' );
        }
        
        
    }
    
    public function leaveQueues() {
        
        $asteriskQueues = (new \yii\db\Query())
                            ->select('rq.queue_num, nco.phone_num')
                                 ->from('ref_queues_operators rqo')
                            ->innerJoin('ref_queues rq', 'rqo.queue_id = rq.queue_id')
                            ->innerJoin('now_calls_operators nco','rqo.operator_id = nco.operator_id')
                            ->where( [ 'nco.operator_id' => $this->getId() ] )
                            ->createCommand();
        // Asterisk shell
        foreach ( $rows = $asteriskQueues->queryAll() as $qo) {
          //shell_exec( 'asterisk -rx \'queue remove member Local/SIP-'. $qo['phone_num'] .'@itwwwqueueagent from '. $qo['queue_num'] .'\' ' );
          shell_exec( 'sudo asterisk -rx' .
                      ' \'queue remove member Local/' . $qo['phone_num']. '@from-queue/n' .
                      ' from '. $qo['queue_num'] .'\' ' );
        }
        
    }
}
