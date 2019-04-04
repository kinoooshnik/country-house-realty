<?php

namespace app\controllers;

use app\models\forms\DirectionForm;
use Yii;
use app\models\tables\Direction;
use app\models\search\DirectionSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class DirectionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionAdmin()
    {
        $searchModel = new DirectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex()
    {
        $directionCards = [];
        $directionSortIndexes = [1, 3, 2, 17, 9, 4, 15, 16, 10, 5, 6, 7, 8, 11, 12, 13, 14];
        $directions = Direction::find()->all();
        foreach ($directionSortIndexes as $index) {
            $direction = $directions[$index - 1];
            $photo = $direction->getProperties()->orderBy(['id' => SORT_DESC])->limit(1)->one();
            if (isset($photo)) {
                $photo = $photo->getMainPhoto()->one();
                $directionCards[] =
                    [
                        'direction' => $direction,
                        'photo' => $photo,
                    ];
            }
        }

        return $this->render('index', [
            'directionCards' => $directionCards,
        ]);
    }

    public function actionCreate()
    {
        $newDirection = new Direction();
        $directionForm = new DirectionForm($newDirection);
        if ($directionForm->load(Yii::$app->request->post()) && $directionForm->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $directionForm,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Direction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $data = (new \yii\db\Query())
            ->select('id, name AS text')
            ->from('direction')
            ->limit(30);
        if (!is_null($q)) {
            $data->where(['like', 'name', $q]);
        }
        $data = $data->all();
        $out['results'] = array_values($data);
        return $out;
    }
}
