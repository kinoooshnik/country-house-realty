<?php

namespace app\controllers;

use app\models\Photo;
use app\models\PropertyForm;
use app\models\PropertySearch;
use Yii;
use app\models\Property;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
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
                        'actions' => ['view', 'error', 'index'],
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
        return $this->render('index');
    }

    /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionAdmin()
    {
        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $newProperty = new Property();
        $newProperty->user_id = Yii::$app->user->identity->id;
        $propertyForm = new PropertyForm($newProperty);
        if ($propertyForm->load(Yii::$app->request->post())) {
            $propertyForm->imageFiles = UploadedFile::getInstances($propertyForm, 'imageFiles');
            if ($propertyForm->save() && $propertyForm->upload()) {
                Yii::$app->session->setFlash($propertyForm->getReturnMessageCode(), $propertyForm->getReturnMessage());
                $propertyForm->loadAttributes();
                //return $this->redirect(['update', 'id' => $newProperty->id]);
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
                Yii::$app->session->setFlash($propertyForm->getReturnMessageCode(), $propertyForm->getReturnMessage());
                return $this->redirect(['update', 'id' => $propertyModel->id]);
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
        \Yii::debug(\yii\helpers\Json::encode($out, JSON_PRETTY_PRINT), __METHOD__);
        return $out;
    }

    public function actionDeletePhoto($photoId, $projectId)
    {
        $photoModel = Photo::find()->where(['id' => $photoId])->limit(1)->one();
        if(!empty($photoModel)) {
            if (unlink(Yii::getAlias('@app') . '/web/uploads/property/original/' . $photoModel->name)) {
                $photoModel->delete();
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось удалить фото');
            }
        }
        return $this->redirect(['update', 'id' => $projectId, '#' => 'propertyform-photos_sequence-sortable']);
    }
}