<?php

namespace app\controllers;


use app\models\Tasks;
use yii\helpers\ArrayHelper;
use yii\web\controller;
use app\models\Users;
use yii\web\Response;

class TasksController extends Controller {
    
//    VUE
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionView(){
        return $this->render('view');
    }

    public function actionEdit(){
        return $this->render('edit');

    }
//    END VUE
    public function actionGetdata(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data['tasksWithUser'] = (new \yii\db\Query())
            ->select(['tasks.id','title', 'description', 'status', 'created_at', 'name'])
            ->from('tasks')
            ->innerJoin('users', 'users.id = tasks.user_id')
            ->all();

        
        $data['tasksWithoutUser'] = Tasks::find()->where(['user_id' => null])->all();
        $data['users'] = Users::find()->select(['name' => 'name', 'id' => 'id'])->all();

        return $data;
    }

    public function actionStore(){
        $task = new Tasks();
        $request = \Yii::$app->request->post();

        $task->attributes = $request;

        if($task->validate()):
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $task->save();
            $id = \Yii::$app->db->getLastInsertID();
            $task = Tasks::findOne($id);
            return $task;
        else:
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $errors['errors']= $task->errors;
            return $errors;
        endif;
    }

    public function actionDelete($id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $task = Tasks::findOne($id);
        if($task->delete()):
            $message = 'Task has been deleted';
        endif;
        return $message;
    }

    public function actionGettask($id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data['task'] = Tasks::findOne($id);
        $data['users'] = Users::find()->select(['name' => 'name', 'id' => 'id'])->all();

        return $data;
    }

    public function actionUpdate($id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = \Yii::$app->request->post();
        $task = Tasks::findOne($id);

        if($task->title === $request['title'] && $task->description === $request['description'] && $task->status === $request['status'] && $task->user_id === $request['user_id']):
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $task->addError('title','Nothing to change');
            $errors['errors']= $task->errors;

            return $errors;
        else:
            $task->attributes = $request;
            if($task->validate()):
                $task->update();

                return $task;
            else:
                $errors['errors'] = $task->errors;
                return $errors;
            endif;

        endif;
    }
}