<?php
use yii\helpers\Html;

$this->title = 'Настройки операторов центра звонков';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-index">
    <div class="row">
            <div class="col-lg-12">
                <h3><?= $this->title; ?></h3>
                <?= Html::a('Добавить оператора', ['/operator/form'], ['class'=>'btn btn-primary']) ?>
                <br/><br/>
                
                <?php
        
        echo yii\grid\GridView::widget([
                'dataProvider' => $provider,
                'columns' => [
                    [ 
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                             'update' => function ($url, $model, $key) {
                                   return Html::a('<i class="glyphicon glyphicon-pencil"></i>',  
                                                    ['operator/form', 'operator_id' => $model->operator_id]
                                                 );
                                }
                            ],
                        'template'=>'{update}' 
                    ],
                    [   'attribute' => 'operator_name',
                        'label' => 'Имя оператора',
                    ],
                    [   'attribute' => 'add_info',
                        'label' => 'Доп. инфо',
                    ],
                ]
            ]);
        ?>
            </div>
    </div>
</div>