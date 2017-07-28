<?php

namespace app\controllers;


use app\models\Tasks;
use yii\helpers\ArrayHelper;
use yii\web\controller;
use app\models\Users;
use yii\web\Response;



class UsersController extends Controller {

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

    public function actionTest(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $users = Users::find()->orderBy('name')->all();
        return $users;
    }

    public function actionStore(){
        $user = new Users();
        $request = \Yii::$app->request->post();
        $user->attributes = $request;


        if($user->validate()):

            if($this->validateEmail($user->email)):
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $user->save();
                return $user;
            else:
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $user->addError('email', 'This email already exist');
                $errors['errors'] = $user->errors;
                return $errors;
            endif;

        else:
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $errors['errors']= $user->errors;
            return $errors;
        endif;
    }


    public function actionDelete($id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Users::findOne($id);
        $tasks = Tasks::find()->where(['user_id' => $id])->all();

        if($user->delete()):
            for($i = 0; $i < count($tasks); $i++){
                $tasks[$i]->user_id = null;
                $tasks[$i]->save();
            }
            $message = 'User has been deleted';
        endif;
        return $message;
    }
    
    public function actionGetuser($id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Users::findOne($id);

        return $user;
    }


    public function actionUpdate($id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $request = \Yii::$app->request->post();
        $user = Users::findOne($id);

        if($user->email === $request['email'] && $user->name === $request['name']):
            $user->addError('email', 'Nothing to change');
            $errors['errors'] = $user->errors;
            return $errors;
        endif;

        if($user->email != $request['email']):
            $user->attributes = $request;
            if($user->validate()):
                if($this->validateEmail($user->email)):
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $user->update();

                    return $user;
                else:
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $user->addError('email', 'This email already exist');
                    $errors['errors'] = $user->errors;

                    return $errors;
                endif;
            else:
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $errors['errors']= $user->errors;

                return $errors;
            endif;
        else:
            $user->name = $request['name'];
            if($user->validate()):
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $user->update();

                return $user;
            else:
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $errors['errors']= $user->errors;


                return $errors;
            endif;
        endif;

    }

    public function actionTasks($id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data['user'] = Users::findOne($id);
        $data['tasks'] = $data['user']->tasks;

        return $data;
    }

    private function validateEmail($email){
        $emails = Users::find()->select(['email'])->asArray()->all();
        $emails = ArrayHelper::getColumn($emails, 'email');

        if(!in_array($email, $emails)):
            return true;
        endif;
    }

}