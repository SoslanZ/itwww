<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use backend\models\Queues;
use backend\models\Operator;
use backend\models\CallLogSearch;
use backend\models\refCommon;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 
                                      'index',
                                      'queues','add-queue',
                                      'operators','add-operator'],
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

        $searchModel = new CallLogSearch();
        $dataProv =  $searchModel->search(Yii::$app->request->get());
        
        return $this->render('call_log',[
            
            'dataProvider' => $dataProv,
            'searchModel' => $searchModel,
            'statusArr' => refCommon::getStatusArr(),
            'operatorArr' => Operator::getOperatorArr()
            
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionQueues() {
        $query = Queues::find();
        
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
    
    public function actionOperators() {
        $query = Operators::find();
        
        $provider = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);

        
        return $this->render('operators_list',[
            'provider' => $provider
        ]);
    }
    
    public function actionAddOperator() {
        
        $query = Operators::find();
        
        $provider = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);

        
        return $this->render('operators_list',[
            'provider' => $provider
        ]);
    }
    
    public function actionAddQueue() {
        
        $query = Operators::find();
        
        $provider = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);

        
        return $this->render('operators_list',[
            'provider' => $provider
        ]);
    }
}
