<?php

namespace app\controllers;

use app\models\forms\PropertyForm;
use app\models\search\PropertySearch;
use app\models\search\PropertyListSearch;
use app\models\tables\Direction;
use app\models\tables\Photo;
use app\models\tables\PropertyPhoto;
use app\models\tables\Property;
use app\models\views\PropertyView;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * PropertyController implements the CRUD actions for Property model.
 */
class PropertyController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view', 'error', 'index', 'list'],
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $direction = Direction::find()->where(['id' => [1, 2, 3, 4, 9, 15]])->limit(6)->all();
        foreach ($direction as $key => $direction) {
            $photo = $direction->getProperties()->orderBy(['id' => SORT_DESC])->limit(1)->one();
            if ($photo != null) {
                $photo = $photo->getMainPhoto()->one();
            }
            $directionCards[$key] =
                [
                    'direction' => $direction,
                    'photo' => $photo,
                ];
        }

        $properies = Property::find()->where(['is_archive' => false])->orderBy(['id' => SORT_DESC])->limit(6)->all();
        foreach ($properies as $property) {
            $propertyViews[] = new PropertyView($property);
        }
        return $this->render(
            'index',
            [
                'directionCards' => isset($directionCards) ? $directionCards : [],
                'propertyViews' => isset($propertyViews) ? $propertyViews : [],
            ]
        );
    }

    public function actionList($page = 1)
    {
        if ($page < 1) {
            $page = 1;
        }
        $itemsOnOnePage = 15;

        $propertySearchModel = new PropertyListSearch();
        $propertyQuery = $propertySearchModel->search(Yii::$app->request->queryParams);
        $propertyQuery->andWhere(['is_archive' => false])->orderBy(['id' => SORT_DESC]);

        $propertyCount = $propertyQuery->count();
        if ($propertyCount < $itemsOnOnePage * ($page - 1)) {
            $page = (int)($propertyCount / $itemsOnOnePage);
        }
        $propertyPageCount = (int)($propertyCount / $itemsOnOnePage) + 1;

        $propertyQuery->limit($itemsOnOnePage)->offset($itemsOnOnePage * ($page - 1));
        $properties = $propertyQuery->all();
        $propertyViews = [];
        foreach ($properties as $property) {
            $propertyViews[] = new PropertyView($property);
        }

        $directionList = [];
        $data = (new \yii\db\Query())
            ->select('id, name')
            ->from('direction')
            ->all();
        foreach ($data as $direction) {
            $directionList[$direction['id']] = $direction['name'];
        }

        // \Yii::debug(\yii\helpers\Json::encode(Yii::$app->request->queryParams, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), __METHOD__);
        // \Yii::debug(\yii\helpers\Json::encode($propertySearchModel, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), __METHOD__);
        return $this->render('list', [
            'propertySearchModel' => $propertySearchModel,
            'directionList' => $directionList,
            'propertyViews' => $propertyViews,
            'propertyCount' => $propertyCount,
            'nav' => [
                'currentPage' => $page,
                'pageCount' => $propertyPageCount
            ],
        ]);
    }

    /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionAdmin()
    {
        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
        ];

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Property model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($slug)
    {
        return $this->render('view', [
            'property' => new PropertyView($this->findModelBySlug($slug)),
            'otherPropertyViews' => PropertyView::getPropertyViews(Property::find()->orderBy('RAND()')->limit(6)->all()),
        ]);
    }

    public function actionViewById($id)
    {
        return $this->redirect(['view', 'slug' => $this->findModel($id)->property_slug]);
    }

    public function actionRestoreObjectToArchive($id)
    {
        $property = $this->findModel($id);
        $property->is_archive = false;
        $property->save();

        return $this->redirect(['view', 'slug' => $property->property_slug]);
    }

    public function actionSendObjectToArchive($id)
    {
        $property = $this->findModel($id);
        $property->is_archive = true;
        $property->save();

        return $this->redirect(['view', 'slug' => $property->property_slug]);
    }

    public function actionCreate()
    {
        $newProperty = new Property();
        $newProperty->user_id = Yii::$app->user->identity->id;
        $propertyForm = new PropertyForm($newProperty);
        if ($propertyForm->load(Yii::$app->request->post())) {
            $propertyForm->imageFiles = UploadedFile::getInstances($propertyForm, 'imageFiles');
            if ($propertyForm->save() && $propertyForm->upload()) {
                return $this->redirect(['update', 'id' => $newProperty->id]);
            } else {
                Yii::$app->session->setFlash('danger', \yii\helpers\Json::encode($propertyForm->getErrorSummary(true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }
//        \Yii::debug(\yii\helpers\Json::encode($propertyForm, JSON_PRETTY_PRINT), __METHOD__);
        return $this->render('create', [
            'propertyForm' => $propertyForm,
        ]);
    }

    /**
     * Updates an existing Property model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $propertyModel = $this->findModel($id);
        $propertyForm = new PropertyForm($propertyModel);
        if ($propertyForm->load(Yii::$app->request->post())) {
            $propertyForm->imageFiles = UploadedFile::getInstances($propertyForm, 'imageFiles');
            if ($propertyForm->save() && $propertyForm->upload()) {
                return $this->redirect(['update', 'id' => $propertyModel->id]);
            } else {
                Yii::$app->session->setFlash('danger', implode('<br>', Json::encode($propertyForm->getErrorSummary(true), JSON_PRETTY_PRINT)));
            }
        }

        return $this->render('update', [
            'propertyForm' => $propertyForm,
        ]);
    }

    /**
     * Deletes an existing Property model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['admin']);
    }

    /**
     * Finds the Property model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Property the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Property::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelBySlug($slug)
    {
        if (($model = Property::findOne(['property_slug' => $slug])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionFeaturesList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $data = (new \yii\db\Query())
            ->select('id, name AS text')
            ->from('property_features')
            ->limit(30);
        if (!is_null($q)) {
            $data->where(['like', 'name', $q]);
        }
        $data = $data->all();
        $out['results'] = array_values($data);
        return $out;
    }

    public function actionDeletePhoto($photoId, $projectId)
    {
        $photoModel = Photo::find()->where(['id' => $photoId])->limit(1)->one();
        if (!empty($photoModel)) {
            if (unlink(Yii::getAlias('@app') . '/web' . $photoModel->getPath())) {
                $photoModel->delete();
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось удалить фото');
            }
        }
        return $this->redirect(['update', 'id' => $projectId, '#' => 'propertyform-photos_sequence-sortable']);
    }
}
