<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\search\PlayerSearch;
use kartik\grid\EditableColumnAction;

class PlayerController extends AppController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PlayerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Article();
        $model->date = date('d.m.Y');
        if ($model->load(Yii::$app->request->post())) {

            $model->date = empty($model->date) ? date("Y-m-d H:i:s") : $model->date;
            $res = $model->save();

            if (!$res) {
                var_dump($model->getErrors());
                exit;
            }

            $model->image = UploadedFile::getInstance($model, 'image');
            if (!empty($model->image)) {
                $model->upload('image', $this->max_width, $this->max_height);
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {

            return $this->render('create', [
                'model' => $model,
                'seo' => $model->getSeo(),
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = Article::getModelById($id);
        $model->getDate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if (!empty($model->image)) {
                $model->upload('image', $this->max_width, $this->max_height);
            }
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'seo' => $model->getSeo(),
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = Article::getModelById($id);
        $model->is_delete = 1;
        $model->removeImages();
        $model->save();
        return $this->redirect(['index']);
    }

    
}
