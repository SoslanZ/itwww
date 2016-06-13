<?php

namespace backend\models;

class Operator extends \yii\db\ActiveRecord
{    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_operators';
    }

    public function attributeLabels()
    {
        return [
            'operator_id' => '',
            'operator_name' => 'Имя оператора',
            'operator_pin' => 'Пароль для оператора',
            'add_info' => 'Дополнительная информация'
        ];
    }
    
    public function rules() {
        return [
            [['operator_name','operator_pin'], 'required'],
            [['operator_id'], 'number'],
            ['add_info', 'string'],
            
        ];
        
    }
    
    public static function getOperatorArr() {
        
        return self::find()->asArray()->all();
        
    }
    
}