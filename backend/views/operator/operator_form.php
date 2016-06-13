<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$action == 'create' ? $this->title = 'Создание нового оператора' : $this->title = 'Редактирование оператора' ;

$this->params['breadcrumbs'][] = ['label' => 'Настройки операторов центра звонков', 'url' => ['/operator/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <h3><?=$this->title.' '.$model->operator_name; ?></h3>
    </div>    
    <div class="col-lg-6">
    <?php

    $form = ActiveForm::begin([
        //'id' => 'queue-form',
        'action' => $action == 'create' ? ['operator/create'] : ['operator/update','operator_id' => $model->operator_id ]
    ]);
    
    ?>
    <?= $form->field($model, 'operator_id')->hiddenInput() ?>    
    <?= $form->field($model, 'operator_name')->textInput()->hint('Укажите имя оператора') ?>
    <?= $form->field($model, 'add_info')->textInput()->hint('Дополнительная информация для интеграции') ?>
    <?= $form->field($model, 'operator_pin')->textInput()->hint('Укажите пароль для входа в АРМ') ?>

    <?= Html::a('Вернуться',['operator/index'],['class'=>'btn']) ?>
      
    <?php
        if (!is_null($model->operator_id)) {
            echo Html::a('Удалить',['operator/delete', 'operator_id' => $model->operator_id],['class'=>'btn btn-default']);
        }
    ?>    
        
    <?php
    if ($action == 'create') {
        echo Html::submitButton('Создать оператора', ['class' => 'btn btn-primary']); 
    }
      else {
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    }
    
    ?>
        
    <?php ActiveForm::end() ?>
    </div>
</div>