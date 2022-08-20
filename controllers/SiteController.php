<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SiteController extends AppController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'site-settings', 'change-admin-pass', 'sitemap', 'new-pass', 'stat', 'import-export', 'export'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        
        ];
    }

    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'main-login',
            ],
        ];
    }

    public function actionIndex()
    {
    }

    public function actionChangeAdminPass()
    {
        $change_pass_form = new \backend\models\ChangePasswordForm();

        if ($change_pass_form->load(Yii::$app->request->post()) && $change_pass_form->validate()) {
            $change_pass_form->save();
            Yii::$app->session->setFlash('info', 'Пароль изменен');
            $this->redirect(['/']);
        }
    }

    public function actionNewPass()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->assetManager->bundles = [
            'yii\web\YiiAsset' => false,
            'yii\bootstrap\BootstrapPluginAsset' => false,
            'yii\bootstrap\BootstrapAsset' => false,
            'yii\web\JqueryAsset' => false
        ];
        return [
            'textPassword' => $this->renderAjax('_pass'),
            'status' => true,
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    
}

