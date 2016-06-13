<?php

namespace backend\models;

use yii\base\Model;

class QueueOperatorForm extends Model {
    
    public $operator_id;
    public $queue_penalty;
    
    public function attributeLabels() {
        
        return [
            'operator_id' => 'Оператор',
            'queue_penalty' => 'Приоритет',
        ];
        
    }

    public function rules() {
        
        return [
            [['operator_id','queue_penalty'], 'required'],
            [['operator_id','queue_penalty'], 'number'],
        ];
        
    }

}