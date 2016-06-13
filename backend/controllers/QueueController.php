<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\Queue;
use yii\db\Query;

/**
 * Site controller
 */
class QueueController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index','form',
                                      'create','update','delete',
                                      'delete-operator','add-operator'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        
       $query = Queue::find();
        
        $provider = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);

        
        return $this->render('queues_list',[
            'provider' => $provider
        ]);
        
    }
    
    public function actionForm($queue_id = null) {
        $qOpsProvider = '';
        
        if (!is_null($queue_id)) {
            
            $qModel = Queue::findOne($queue_id);
            
            // find relations to operators
            $qOps = new Query();
            
            $qOps->select('o.*,qo.queue_id, qo.queue_penalty')
                 ->from('ref_queues_operators qo')->innerJoin('ref_operators o','qo.operator_id = o.operator_id')
                 ->where(['qo.queue_id' => $queue_id]);
                 //->all();
                    
            $qOpsProvider = new ActiveDataProvider([
                        'query' => $qOps,
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);        
            
        } else {
            $qModel = new Queue();
        }
        
        return $this->render('queue_form',
                             ['model' => $qModel,
                              'action' => is_null($queue_id) ? 'create' : 'edit',
                              'QueueOperatorModel' => new \backend\models\QueueOperatorForm(),   
                              is_null($queue_id) ? '': 'dataProvider' => $qOpsProvider 
                             ]
                            );
        
    }
    
    public function actionUpdate($queue_id) {
        
        $qModel = Queue::findOne( $queue_id );
        
        if ($qModel->load(Yii::$app->request->post()) && $qModel->validate() && $qModel->save() ) {
            Yii::$app->session->setFlash('success', 'Действие выполнено');
            self::dialplanReload();
        } else {
            Yii::$app->session->setFlash('error', 'Действие не выполнено');
        }
        
        return $this->redirect(['/queue/index']);
        
        
    }
    
    public function actionDelete($queue_id) {
        
        $qModel = Queue::findOne($queue_id);
        
        if (!$qModel->delete()) {
            Yii::$app->session->setFlash('error', 'Действие не выполнено');
        } else {
            Yii::$app->session->setFlash('success', 'Очередь удалена успешно');
            self::dialplanReload();
        }
        
        return $this->redirect(['/queue/index']);
        
    }
    
    public function actionCreate() {
        
        $qModel = new Queue();
        
        if ($qModel->load(Yii::$app->request->post()) && $qModel->validate() && $qModel->save() ) {
            Yii::$app->session->setFlash('success', 'Действие выполнено');
            self::dialplanReload();
        } else {
            Yii::$app->session->setFlash('error', 'Действие не выполнено');
        }
        
        return $this->redirect(['/queue/index']);
    }
    
    public function actionDeleteOperator($queue_id,$operator_id) {

        $row_count = Yii::$app->db->createCommand()->delete('ref_queues_operators', 
                                                ['queue_id' => $queue_id, 
                                                 'operator_id' => $operator_id] )->execute();
        
        if ($row_count > 0) {
            Yii::$app->session->setFlash('success', 'Оператор удален из очереди');
        } else 
            Yii::$app->session->setFlash('error', 'Действие не выполнено, возможно запись уже удалена');
        
        return $this->redirect(['/queue/form', 
                                'queue_id' => $queue_id]);
        
    }
    
    public function actionAddOperator($queue_id) {
        
        $operator_id = Yii::$app->request->post()['QueueOperatorForm']['operator_id'] ;
        $queue_penalty = Yii::$app->request->post()['QueueOperatorForm']['queue_penalty'] ;
        
        if (!empty($operator_id)) {
        $row_count = Yii::$app->db->createCommand()->insert('ref_queues_operators', 
                                                ['queue_id' => $queue_id, 
                                                 'operator_id' => $operator_id,
                                                 'queue_penalty' => $queue_penalty,
                                                    ] )->execute();
        
        if ($row_count > 0) {
            Yii::$app->session->setFlash('success', 'Оператор добавлен в очередь');
        } else 
            Yii::$app->session->setFlash('error', 'Действие не выполнено');
        }
        return $this->redirect(['/queue/form', 
                                'queue_id' => $queue_id]);
         
        
    }
    
    public function dialplanReload() {
        shell_exec( 'asterisk -rx \'dialplan reload\' ');
    }
    
}
