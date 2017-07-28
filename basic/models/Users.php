<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Users extends ActiveRecord {

    public static function  tableName(){
        return 'users';
    }

    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['name', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            [['name', 'email'], 'trim']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
        ];
    }

    public function getTasks(){
        return $this->hasMany(Tasks::classname(), ['user_id' => 'id']);
    }


}