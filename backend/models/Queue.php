<?php

namespace backend\models;

class queue extends \yii\db\ActiveRecord
{    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_queues';
    }
    
   public function attributeLabels()
    {
        return [
            'queue_id' => '',
            'queue_num' => 'Номер очереди Asterisk',
            'queue_name' => 'Название очереди',
            'queue_url' => 'Ссылка на CRM',
        ];
    }

    public function rules()
    {
        return [
            [['queue_num'], 'required'],
            [['queue_num','queue_id'], 'number'],
            [['queue_url','queue_name'], 'string'],
            
        ];
    }

}