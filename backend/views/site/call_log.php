<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Журнал вызовов центра звонков';

?>
<div class="site-index">
    <!--div class="container-fluid"><h3><?=$this->title; ?></h3></div-->
    
    <div class="call-log container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php
                
                echo yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [   'attribute' => 'logc_id',
                                'label' => 'ID'],
                            [   'attribute' => 'date_call',
                                'label' => 'Дата звонка',
                                'format' =>  ['date', 'dd.MM.Y HH:mm:ss']   ],
                            [   'attribute' => 'main_cid',
                                'label' => 'Звонивший'    ],  
                            [   'attribute' => 'main_did',
                                'label' => 'Назначение'  ],  
                            [   'attribute' => 'queue_num',
                                'label' => 'Очередь'  ],
                            [   'attribute' => 'did',
                                'label' => 'Внутр.'  ],
                            [   'attribute' => 'ci_date_call',
                                'label' => 'Дозвон',
                                'format' =>  ['date', 'HH:mm:ss'] ],
                            [   'attribute' => 'date_answer',
                                'label' => 'Ответ',
                                'format' =>  ['date', 'HH:mm:ss'] ],
                            [   'attribute' => 'date_hangup',
                                'label' => 'Закончили',
                                'format' =>  ['date', 'HH:mm:ss'] ],
                            [   'attribute' => 'operator_name',
                                'label' => 'Оператор',
                                'filter' => Html::activeDropDownList(
                                        $searchModel,
                                        'operator_id',
                                        ArrayHelper::map($operatorArr, 'operator_id', 'operator_name'),
                                        [
                                            'class'=>'form-control',
                                            'prompt' => ' - Оператор'
                                        ]
                                ),
                                ],
                            [   'attribute' => 'dstatus',
                                'label' => 'Статус',
                                'filter' => Html::activeDropDownList(
                                        $searchModel,
                                        'dstatus',
                                        ArrayHelper::map($statusArr, 'addition', 'value'),
                                        [
                                            'class'=>'form-control',
                                            'prompt' => ' - Статус'
                                        ]
                                ),
                                ],
                            [
                                'label' => '',
                                'content' => function ($model, $key, $index, $column){
                                    if ( in_array( $model['dial_status'] , ['ANSWER','CHANUNAVAIL']) ) {

                                        return '<audio controls style="width: 115px !important;">'
                                                . '<source src="/itwww/asterisk/get_rec.php?rec='.
                                                $model['uid'].'" type="audio/mpeg">'.
                                                '</audio>';
                                        ;
                                    }
                                },
                                
                            ],
                            [   'attribute' => 'uid',
                                'content' => function ($model, $key, $index, $column){
                                    if ( in_array( $model['dial_status'] , ['ANSWER','CHANUNAVAIL']) ) {

                                        return '<a href="/itwww/asterisk/get_rec.php?rec='.
                                                $model['uid'].'">'.
                                                '<i class="glyphicon glyphicon-file"></i>'.
                                                '</a>';
                                        ;
                                    }
                                },
                                'label' => ''    ]                                
                            ],
                    ]);
                ?>
            </div>
        </div>
    </div>
</div>