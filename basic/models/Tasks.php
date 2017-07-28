<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tasks".
 *
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property string $created_at
 * @property string $user_id
 */
class Tasks extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['description'], 'string'],
            [['user_id'], 'integer'],
            [['title', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'user_id' => 'User ID',
        ];
    }

    public function getUser(){
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
