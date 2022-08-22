<?php

namespace app\controllers;

use app\models\Characteristic;
use app\models\CharacteristicPlayer;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\search\PlayerSearch;
use app\models\Player;
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
        $model = new Player();
        $model->birthday = date('d.m.Y');
        if ($model->load( Yii::$app->request->post())) {
            
            $model->birthday=date('Y-m-d', strtotime($model->birthday));
            $res = $model->save();
            if (!$res) {
                var_dump($model->getErrors());
                exit;
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = Player::getModelById($id);
        $model->getBirthday();
        if ($model->load(Yii::$app->request->post())) {
            $model->birthday=date('Y-m-d', strtotime($model->birthday));
            $res = $model->save();
            if (!$res) {
                var_dump($model->getErrors());
                exit;
            }
            
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = Player::getModelById($id);
        $model->is_delete = 1;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionNewCharactItem()
    {
        $get = Yii::$app->request->get();

        $model_id = (int)$get['model_id'];
        $characteristic_id = (int)$get['characteristic_id'];

        $model = Player::findone($model_id);
        $characteristic = Characteristic::find()->where(['id' => $characteristic_id])->one();
        $classItem = 'id-item-' . $characteristic->id;

        if (!empty($model) && !empty($characteristic)) {

            return $this->renderPartial(
                '_charact_item',
                [
                    'characteristic' => $characteristic,
                    'value' => "",
                    'value_units' => "",
                    'model' => $model,
                    'class' => $classItem,
                ]
            );
        }
    }

    public function actionDeleteCharactItem()
    {
        $get = Yii::$app->request->get();

        $model_id = (int)$get['model_id'];
        $characteristic_id = (int)$get['characteristic_id'];

        $model = Player::findone($model_id);

        if (!empty($model)) {
            CharacteristicPlayer::deleteAll(
                [
                    'AND',
                    ['=', 'player_id', $model->id],
                    ['=', 'characteristic_id', $characteristic_id]
                ]
            );
        }
    }

    
}
