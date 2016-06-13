<?php
use yii\helpers\Html;

$this->title = 'Настройки очередей центра звонков';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <div class="row">
        <div class="col-lg-12">
            <h3><?=$this->title; ?></h3>
            <?= Html::a('Добавить очередь', ['/queue/form'], ['class'=>'btn btn-primary']) ?>
            <br/><br/>
                
            <?= yii\grid\GridView::widget([
                'dataProvider' => $provider,
                'columns' => [
                    [ 
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                            return Html::a('<i class="glyphicon glyphicon-pencil"></i>',  
                                                    ['queue/form', 'queue_id' => $model->queue_id]
                                            );
                                        }
                        ],
                        'template'=>'{update}' 
                    ],
                 'queue_num',
                 'queue_name',
                 'queue_url',
                ]
            ]);
            ?>
        </div>
    </div>
</div>