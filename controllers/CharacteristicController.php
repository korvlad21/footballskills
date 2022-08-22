<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Characteristic;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\search\CharacteristicSearch;
use kartik\grid\EditableColumnAction;

class CharacteristicController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new CharacteristicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        $model = new Characteristic();

        if ($model->load(Yii::$app->request->post())) {

            $model->parent_id = $model->prepareParent();
            if ($model->save()) {
                if ($parentModel = $model->parent) {
                    $parentModel->is_child = 0;
                    $parentModel->save();
                }
                return $this->redirect(['index']);
            } else {
                $strErrors = array_shift($model->errors)[0];
                Yii::$app->session->setFlash('warning', $strErrors);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();

        if ($model->load($post)) {

            $model->parent_id = $model->prepareParent();
            $model->isChild();

            if ($model->save()) {
                if ($parentModel = $model->parent) {
                    $parentModel->isChild();
                    $parentModel->save();
                }
            } else {
                $strErrors = array_shift($model->errors)[0];
                Yii::$app->session->setFlash('warning', $strErrors);
            }

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->changeChildParentId();
        $model->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Characteristic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
