<?php

namespace app\controllers;

use app\models\User;
use app\models\UserCreateForm;
use app\models\UserInfoForm;
use app\models\UserNewPassForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\data\ActiveDataProvider;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['login'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionProfile()
    {
        //\Yii::debug(\yii\helpers\Json::encode($model, JSON_PRETTY_PRINT), __METHOD__);
        $userInfoForm = new UserInfoForm(Yii::$app->user->identity);
        if ($userInfoForm->load(Yii::$app->request->post())) {
            $userInfoForm->save();
        }

        $userNewPassForm = new UserNewPassForm(Yii::$app->user->identity);
        if ($userNewPassForm->load(Yii::$app->request->post())) {
            $userNewPassForm->save();
        }

        return $this->render('profile', [
            'userInfoForm' => $userInfoForm,
            'userNewPassForm' => $userNewPassForm,
        ]);
    }

    public function actionAdmin()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $newUser = new User();
        $userCreateForm = new UserCreateForm($newUser);
        if ($userCreateForm->load(Yii::$app->request->post())) {
            if ($userCreateForm->save()) {
                Yii::$app->session->setFlash($userCreateForm->getReturnMessageCode(), $userCreateForm->getReturnMessage());
                return $this->redirect(['admin']);
            }
        }

        return $this->render('create', [
            'userCreateForm' => $userCreateForm,
        ]);
    }

    public function actionUpdate($id)
    {
        $userModel = User::findIdentity($id);
        $userInfoForm = new UserInfoForm($userModel);
        if ($userInfoForm->load(Yii::$app->request->post())) {
            $userInfoForm->save();
        }

        $userNewPassForm = new UserNewPassForm($userModel);
        if ($userNewPassForm->load(Yii::$app->request->post())) {
            $userNewPassForm->save();
        }

        return $this->render('profile', [
            'userModel' => $userModel,
            'userInfoForm' => $userInfoForm,
            'userNewPassForm' => $userNewPassForm,
        ]);
    }

    public function actionDelete($id)
    {
        if (User::findIdentity($id)->delete()) {
            Yii::$app->session->setFlash('success', 'Пользователь удален');
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось удалить пользователя');
        }

        return $this->redirect(['admin']);
    }

}
