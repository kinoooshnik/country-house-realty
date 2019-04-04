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
                Yii::$app->session->setFlash('danger', implode('<br>', Json::encode($propertyForm->getErrorSummary(true), JSON_PRETTY_PRINT)));
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
            if (unlink(Yii::getAlias('@app') . '/web/uploads/property/original/' . $photoModel->name)) {
                $photoModel->delete();
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось удалить фото');
            }
        }
        return $this->redirect(['update', 'id' => $projectId, '#' => 'propertyform-photos_sequence-sortable']);
    }

    public function actionRenamePhoto()
    {
        $photoModels = Photo::find()->where(['not like', 'name', '%.jpg', false])->all();
        $s = '';
        foreach ($photoModels as $photoModel) {
            $s .= $photoModel->id . ' ' . $photoModel->name . '<br>';
            rename(Yii::getAlias('@app') . '/web/uploads/property/original/' . $photoModel->name, Yii::getAlias('@app') . '/web/uploads/property/original/' . substr($photoModel->name, 0, -3) . '.jpg');
            $photoModel->name = substr($photoModel->name, 0, -3) . '.jpg';
            $photoModel->save();
        }
        return count($photoModels) . '<br>' . $s;
    }

    public function actionRecoveryData()
    {
        ini_set('max_execution_time', 300);
        $data = (new \yii\db\Query())
            ->select('id, name AS text')
            ->from('direction')
            ->limit(30);
        $data = $data->all();
        foreach (array_values($data) as $pair) {
            $directions[$pair['id']] = $pair['text'];
        }

        $str = '';
        $row = 0;
        $obj = 0;
        if (($handle = fopen(__DIR__ . "/export_file_GC3pZrjp6N41D3us.csv", "r")) !== FALSE) {
            $property = new Property();
            $photoArr = [];
            while (($data = fgetcsv($handle, 5000, "~", '"', "\"")) !== FALSE) {
                $row++;
                if (count($data) < 20) {
//                    echo $row . '<br>';
//                    print_r($data);
//                    echo '<br>';
                    continue;
                }
                if ($property->property_name == $data[1]) {
                    if (isset($data[32])) {
                        $photoArr[] = $data[32];
                    }
                    continue;
                }
                $obj++;

                $this->saveProperty($property, $photoArr);

//                echo \yii\helpers\Json::encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) , '<br>';
                $property = new Property();
                $photoArr = [];
                $property->property_name = $data[1];
//                $property->property_slug = $data[12];
                $property->property_type = $data[15];
                $property->is_sale = $data[16] == 'Да' ? true : false;
                $property->is_rent = $data[17] == 'Да' ? true : false;
                $property->currency = $data[18];
                $property->sale_price = isset($data[19]) ? intval($data[19]) : null;;
                $property->rent_price = isset($data[20]) ? intval($data[20]) : null;;
                $property->address = $data[21];
                $map = explode(",", $data[22]);
                if (count($map) == 2) {
                    $property->map_latitude = floatval($map[0]);
                    $property->map_longitude = floatval($map[1]);
                }
                $property->distance_to_mrar = intval($data[23]);
                switch ($data[24]) {
                    case 'С отделкой':
                        $property->with_finishing = 1;
                        break;
                    case 'Без отделки':
                        $property->with_finishing = 0;
                        break;
                }
                switch ($data[25]) {
                    case 'С мебелью':
                        $property->with_furniture = 1;
                        break;
                    case 'Без мебели':
                        $property->with_furniture = 0;
                        break;
                }
                $property->bathrooms = isset($data[26]) ? $data[26] : null;
                $property->bedrooms = isset($data[27]) ? $data[27] : null;
                $property->garage = isset($data[28]) ? $data[28] : null;
                $property->land_area = isset($data[29]) ? intval($data[29]) : null;
                $property->build_area = isset($data[30]) ? intval($data[30]) : null;
                $property->description = $data[31];
                if (isset($data[32])) {
                    $photoArr[] = $data[32];
                }
                $property->is_archive = false;
                $property->direction_id = array_search($data[33], $directions);
                $property->user_id = 1;
//                echo print_r($property->validate()) . '<br>';
            }
            $this->saveProperty($property, $photoArr);
            fclose($handle);
        } else {
            return 'Opening the file error.';
        }
        return $str;
    }

    private function saveProperty(Property $property, array $photoArr)
    {
        if ($property->validate()) {
            $property->save();
            foreach ($photoArr as $file) {
                $fileExtension = explode(".", $file);
                $fileExtension = $fileExtension[count($fileExtension) - 1];
                $photoName = $property->property_slug . '-' . Yii::$app->security->generateRandomString(6) . '.' . $fileExtension;
                $oldName = __DIR__ . substr($file, 7);
                $newName = Yii::getAlias('@app') . '/web' . Yii::getAlias('@propertyOpiginalPhotoUploadDir') . '/' . $photoName;
//                        echo $oldName . ' ' . $newName . '<br>';
                if (strlen($file) > 5 && rename($oldName, $newName)) {
                    $photoModel = new Photo();
                    $photoModel->name = $photoName;
                    if ($photoModel->save()) {
                        $newPropertyPhotoModel = new PropertyPhoto();
                        $newPropertyPhotoModel->property_id = $property->id;
                        $newPropertyPhotoModel->photo_id = $photoModel->id;
                        $maxPosition = PropertyPhoto::find()->max('position');
                        $newPropertyPhotoModel->position = $maxPosition + 1;
                        if (!$newPropertyPhotoModel->save()) {
                            echo $newName . ' ' . $property->id . 'cannot join photo to property ' . print_r($newPropertyPhotoModel->getFirstErrors()) . '<br>';
                        }
                    } else {
                        echo $newName . 'db record creating error' . print_r($photoModel->getFirstErrors()) . '<br>';
                    }
                } else {
                    echo $oldName . ' ' . $newName . ' - renaming is failed<br>';
                }
            }
        } else {
            echo $property->property_name . ' ' . print_r($property->getErrors()) . " - not validate <br>";
            echo \yii\helpers\Json::encode($property, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), '<br>';
        }
    }
}
