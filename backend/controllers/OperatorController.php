<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\Operator;

/**
 * Site controller
 */
class OperatorController extends Controller
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
                        'actions' => ['index','form',
                                      'create','update','delete'],
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
        
     $query = Operator::find();
        
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

    public function actionForm($operator_id = null) {
        
        if (!is_null($operator_id)) {
            $oModel = Operator::findOne($operator_id);
        } else {
            $oModel = new Operator();
        }
        
        return $this->render('operator_form',
                             ['model' => $oModel,
                              'action' => is_null($operator_id) ? 'create' : 'edit']
                            );
        
    }
    
    public function actionUpdate($operator_id) {
        
        $oModel = Operator::findOne( $operator_id );
        
        
        if ($oModel->load(Yii::$app->request->post()) && $oModel->validate() && $oModel->save() ) {
            Yii::$app->session->setFlash('success', 'Действие выполнено');
        } else {
            Yii::$app->session->setFlash('error', 'Действие не выполнено');
        }
        
        return $this->redirect(['/operator/index']);
        
    }
    
    public function actionDelete($operator_id) {
        
        $oModel = Operator::findOne($operator_id);
        
        if (!$oModel->delete()) {
            Yii::$app->session->setFlash('error', 'Действие не выполнено');
        } else {
            Yii::$app->session->setFlash('success', 'Оператор удален успешно');
        }
        
        return $this->redirect(['/operator/index']);
        
    }
    
    public function actionCreate() {
        
        $oModel = new Operator();
        
        if ($oModel->load(Yii::$app->request->post()) && $oModel->validate() && $oModel->save() ) {
            Yii::$app->session->setFlash('success', 'Действие выполнено');
        } else {
            Yii::$app->session->setFlash('error', 'Действие не выполнено');
        }
        
        return $this->redirect(['/operator/index']);
    }
    
}
