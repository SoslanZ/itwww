<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$action == 'create' ? $this->title = 'Создание новой очереди' : $this->title = 'Редактирование очереди' ;

$this->params['breadcrumbs'][] = ['label' => 'Настройки очередей центра звонков', 'url' => ['/queue/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-6">
        <h3><?=$this->title.' '.$model->queue_num; ?></h3>
        <br>
    <?php
    
    $form = ActiveForm::begin([
        //'id' => 'queue-form',
        'action' => $action == 'create' ? ['queue/create'] : ['queue/update','queue_id' => $model->queue_id]
    ]);
    
    ?>
    <?= $form->field($model, 'queue_num')->textInput()->hint('Укажите номер очереди в Asterisk') ?>
    <?= $form->field($model, 'queue_name')->textInput()->hint('Укажите текстовое описание очереди') ?>
    <?= $form->field($model, 'queue_url')->textInput() ?>

    <?= Html::a('Вернуться',['queue/index'],['class'=>'btn']); ?>
      
    <?php
        if (!is_null($model->queue_id)) {
          echo Html::a('Удалить',['queue/delete', 'queue_id' => $model->queue_id],['class'=>'btn btn-default']);
        }
        
    ?>    
        
    <?php
    if ($action == 'create') {
        echo Html::submitButton('Создать очередь', ['class' => 'btn btn-primary']); 
    }
      else {
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    }
    
    ?>
        
    <?php ActiveForm::end() ?>
    </div>
    <div class="col-lg-6">
        
        
    <?php 
    if (!empty($model->queue_id)) {
            
        echo '<h3>Операторы в очереди</h3>';    
        
        ?>
        <br/>
        
        <?php
        $form = ActiveForm::begin([
            'action' => ['queue/add-operator','queue_id' => $model->queue_id]
            ]);
        
        // TODO когда будет время
        
        echo $form->field($QueueOperatorModel, 'operator_id')
                  ->dropDownList(
            ArrayHelper::map(\backend\models\Operator::find()
                    ->where('operator_id not in '
                            . '(select qo.operator_id '
                            . 'from ref_queues_operators qo '
                            . 'where qo.queue_id = :current_queue_id)'
                            ,[ 'current_queue_id' => $model->queue_id ])
                    ->all(),
                    'operator_id', 'operator_name'),
                    $params = [
                                'prompt' => '- Выберите оператора -'
                            ]
            );
        
        echo $form->field($QueueOperatorModel,'queue_penalty')
                  ->dropDownList(
                     ['0' => '0' , '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'],
                    $params = [
                                'prompt' => '- Приоритет -'
                              ]
             );
        
        ?>
        <?= Html::submitButton('Добавить в очередь', ['class' => 'btn btn-primary']) ?>
        
        <?php ActiveForm::end(); ?>
        
        <br/>
        
        <?php
        
        echo yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                        [ 'attribute' => 'operator_name',
                          'label' => 'Оператор', ],
                        [ 'attribute' => 'queue_penalty',
                          'label' => 'Приоритет', ],
                        [   
                            'content' => 
                                function ($rowModel, $key, $index, $column){
            
                                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',
                                                    [
                                                       'queue/delete-operator',
                                                       'operator_id' => $rowModel['operator_id'] ,
                                                       'queue_id' => $rowModel['queue_id'] ,
                                                       
                                                    ]
                                                  );
                                },
                            'label' => ''    
                        ]
                    ]
        ]);
    }
    ?>
    </div>    
</div>