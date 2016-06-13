<?php

namespace backend\models;

use yii\db\ActiveRecord;

class refCommon extends ActiveRecord {
    
    public static function tableName() {
        
        return 'ref_commons';
        
    }
    
    public static function getStatusArr() {
        
        return self::find()->asArray()->all();
        
    }
    
}
