<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\tables\Photo;
use app\models\tables\Property;
use app\models\tables\PropertyFeatures;
use app\models\tables\PropertyPhoto;
use app\models\tables\PropertyPropertyFeatures;

class PropertyForm extends Model
{
    public $id;
    public $property_name;
    public $property_type;
    public $ad_type;
    public $currency;
    public $sale_price;
    public $rent_price;
    public $address;
    public $map_latitude;
    public $map_longitude;
    public $direction_id;
    public $distance_to_mrar;
    public $with_finishing;
    public $with_furniture;
    public $bathrooms;
    public $bedrooms;
    public $garage;
    public $land_area;
    public $build_area;
    public $description;
    public $property_features;
    public $photos;
    public $photos_sequence;
    public $is_archive;
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    /**
     * @var Property
     */
    private $_propertyModel;

    private $returnMessageCode = null;
    private $returnMessage = null;

    public function __construct(Property $propertyModel, array $config = [])
    {
        $this->_propertyModel = $propertyModel;
        $this->loadAttributes();
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['property_name', 'property_type', 'ad_type', 'currency', 'address', 'map_latitude', 'map_longitude', 'direction_id'], 'required'],
            [['id', 'direction_id', 'distance_to_mrar', 'sale_price', 'rent_price'], 'integer'],
            [['address', 'property_type', 'currency', 'bathrooms', 'bedrooms', 'garage', 'description', 'photos_sequence'], 'string'],
            [['land_area', 'build_area'], 'number'],
            [['map_latitude', 'map_longitude'], 'double'],
            [['property_name'], 'string', 'max' => 255],
            [['is_archive'], 'boolean'],
            ['property_type', 'in', 'range' => ['Дом', 'Таунхаус', 'Квартира', 'Участок']],
            ['currency', 'in', 'range' => ['₽', '$', '€']],
            [['with_finishing', 'with_furniture'], 'in', 'range' => ['🛇', '0', '1']],
            ['bathrooms', 'in', 'range' => ['🛇', '1', '2', '3', '4', '5', '> 5']],
            ['bedrooms', 'in', 'range' => ['🛇', '1', '2', '3', '4', '5', '> 5']],
            ['garage', 'in', 'range' => ['🛇', '0', '1', '2', '3', '4', '5', '> 5']],
            [['with_finishing', 'with_furniture', 'bathrooms', 'bedrooms', 'garage'], 'default', 'value' => '🛇'],
            ['ad_type', 'in', 'range' => ['is_rent', 'is_sale'], 'allowArray' => true],
            ['property_features', 'each', 'rule' => ['string']],
            ['photos', 'each', 'rule' => ['string']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 30],
        ];
    }

    public function save()
    {
        $success = false;
        if ($this->validate()) {
            if ($this->with_finishing == '🛇') {
                $this->with_finishing = null;
            }
            if ($this->with_furniture == '🛇') {
                $this->with_furniture = null;
            }
            if ($this->bathrooms == '🛇') {
                $this->bathrooms = null;
            }
            if ($this->bedrooms == '🛇') {
                $this->bedrooms = null;
            }
            if ($this->garage == '🛇') {
                $this->garage = null;
            }
            $this->_propertyModel->property_name = $this->property_name;
            $this->_propertyModel->property_type = $this->property_type;
            if (is_array($this->ad_type)) {
                $this->_propertyModel->is_rent = array_search('is_rent', $this->ad_type) !== false ? true : false;
                $this->_propertyModel->is_sale = array_search('is_sale', $this->ad_type) !== false ? true : false;
            }
            $this->_propertyModel->currency = $this->currency;
            $this->_propertyModel->sale_price = $this->sale_price;
            $this->_propertyModel->rent_price = $this->rent_price;
            $this->_propertyModel->address = $this->address;
            $this->_propertyModel->map_latitude = $this->map_latitude;
            $this->_propertyModel->map_longitude = $this->map_longitude;
            $this->_propertyModel->direction_id = $this->direction_id;
            $this->_propertyModel->distance_to_mrar = $this->distance_to_mrar;
            $this->_propertyModel->with_finishing = $this->with_finishing;
            $this->_propertyModel->with_furniture = $this->with_furniture;
            $this->_propertyModel->bathrooms = $this->bathrooms;
            $this->_propertyModel->bedrooms = $this->bedrooms;
            $this->_propertyModel->garage = $this->garage;
            $this->_propertyModel->land_area = $this->land_area;
            $this->_propertyModel->build_area = $this->build_area;
            $this->_propertyModel->description = $this->description;
            $this->_propertyModel->is_archive = $this->is_archive;

            if ($this->_propertyModel->isNewRecord) {
                if ($this->_propertyModel->save()) {
                    Yii::$app->session->setFlash(
                        'success',
                        strtr('Объект "{nameToShow}" создан.', ['{nameToShow}' => $this->property_name,])
                    );
                    $success = true;
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось создать объект');
                    $success = false;
                }
            } elseif ($this->_propertyModel->save()) {
                Yii::$app->session->setFlash(
                    'success',
                    strtr('Объект "{nameToShow}" успешно изменен.', ['{nameToShow}' => $this->property_name,])
                );
                $success = true;
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось изменить объект');
                $success = false;
            }

            //Ососбенности
            if ($success && is_array($this->property_features)) {
                $propertyFeaturesExist = $this->_propertyModel->getPropertyFeatures()->all();
                $propertyFeaturesSelected = (new \ArrayObject($this->property_features))->getArrayCopy();
                foreach ($propertyFeaturesSelected as $PFSKey => $propertyFeatureSelected) {
                    foreach ($propertyFeaturesExist as $PFEKey => $propertyFeatureExist) {
                        if ($propertyFeatureExist->name == $propertyFeatureSelected) {
                            unset($propertyFeaturesExist[$PFEKey]);
                            unset($propertyFeaturesSelected[$PFSKey]);
                            break;
                        }
                    }
                }
                foreach ($propertyFeaturesExist as $propertyFeatureExist) {
                    $tmp = PropertyPropertyFeatures::find()
                        ->where(['property_features_id' => $propertyFeatureExist->id])
                        ->andWhere(['property_id' => $this->_propertyModel->id])
                        ->one();
                    if (!empty($tmp)) {
                        $tmp->delete();
                    }
                }
                foreach ($propertyFeaturesSelected as $propertyFeatureSelected) {
                    $propertyFeature = PropertyFeatures::find()->where(['name' => $propertyFeatureSelected])->one();
                    if (empty($propertyFeature)) {
                        $propertyFeature = new PropertyFeatures();
                        $propertyFeature->name = $propertyFeatureSelected;
                        $propertyFeature->save();
                    }
                    $tmp = new PropertyPropertyFeatures();
                    $tmp->property_id = $this->_propertyModel->id;
                    $tmp->property_features_id = $propertyFeature->id;
                    $tmp->save();
                }
            }

            //Порядок
            if ($success && !empty($this->photos_sequence)) {
                $sequence = explode(',', $this->photos_sequence);
                $propertyPhotoModels = $this->_propertyModel->getPropertyPhotos()->all();
                $i = 1;
                foreach ($sequence as $element) {
                    foreach ($propertyPhotoModels as $propertyPhotoModel) {
                        if ($propertyPhotoModel->photo_id == $element) {
                            $propertyPhotoModel->position = $i;
                            $propertyPhotoModel->save();
                            break;
                        }
                    }
                    $i++;
                }
            }
        } else {
            Yii::$app->session->setFlash('error', 'Введены некорректные данные');
        }
        //\Yii::debug(\yii\helpers\Json::encode($this->getErrorSummary(true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), __METHOD__);
        //\Yii::debug(\yii\helpers\Json::encode($this->_propertyModel, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), __METHOD__);
        return $success;
    }

    public function getErrorSummary($showAllErrors)
    {
        if (empty(parent::getErrorSummary($showAllErrors))) {
            return $this->_propertyModel->getErrorSummary($showAllErrors);
        }
        return parent::getErrorSummary($showAllErrors);
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $photoModel = new Photo();
                $photoModel->imageFile = $file;
                $photoModel->name = '/uploads/property/original/' . $this->_propertyModel->property_slug . '-' . Yii::$app->security->generateRandomString(6) . '.' . $file->extension;
                if (!$photoModel->upload()) {
                    Yii::$app->session->setFlash('error', 'Не удалось загрузить фото ' . $photoModel->name . '.');
                    continue;
                }
                if (!$photoModel->save()) {
                    Yii::$app->session->setFlash('error', 'Не удалось создать запись в БД о фото ' . $photoModel->name . '.<br>' . yii\helpers\Json::encode($photoModel->getErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    continue;
                }
                $newPropertyPhotoModel = new PropertyPhoto();
                $newPropertyPhotoModel->property_id = $this->_propertyModel->id;
                $newPropertyPhotoModel->photo_id = $photoModel->id;
                $maxPosition = PropertyPhoto::find()->where(['property_id' => $this->_propertyModel->id])->max('position');
                if (!isset($maxPosition)) {
                    $maxPosition = 0;
                }
                $newPropertyPhotoModel->position = $maxPosition + 1;
                if (!$newPropertyPhotoModel->save()) {
                    Yii::$app->session->setFlash('error', 'Не удалось присоединить фото ' . $photoModel->name . ' к объекту.');
                }
//                $uploadDir = Yii::getAlias('@app') . '/web/uploads/property/original';


//                $photoName = $this->_propertyModel->property_slug . '-' . Yii::$app->security->generateRandomString(6) . '.' . $file->extension;
//                $uploadDir = Yii::getAlias('@app') . '/web/uploads/property/original';
//                if ($file->saveAs($uploadDir . '/' . $photoName)) {
//                    $photoModel = new Photo();
//                    $photoModel->name = $photoName;
//                    if ($photoModel->save()) {
//                        $newPropertyPhotoModel = new PropertyPhoto();
//                        $newPropertyPhotoModel->property_id = $this->_propertyModel->id;
//                        $newPropertyPhotoModel->photo_id = $photoModel->id;
//                        $maxPosition = PropertyPhoto::find()->max('position');
//                        $newPropertyPhotoModel->position = $maxPosition + 1;
//                        if (!$newPropertyPhotoModel->save()) {
//                            Yii::$app->session->setFlash('error', 'Не удалось присоединить фото ' . $photoName . ' к объекту.');
//                        }
//                    } else {
//                        Yii::$app->session->setFlash('error', 'Не удалось создать запись в БД о фото ' . $photoName . '.<br>' . yii\helpers\Json::encode($photoModel->getErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
//                    }
//                } else {
//                    Yii::$app->session->setFlash('error', 'Не удалось загрузить фото ' . $file->baseName . '.' . $file->extension . '.');
//                }
            }
            if (count($this->imageFiles) != 0) {
                Yii::$app->session->setFlash('success', 'Фото успешно загружены.');
            }
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось загрузить фото.');
            return false;
        }
    }

    public function attributeLabels()
    {
        return array_merge(
            Property::$attributeLabels,
            [
                'ad_type' => 'Тип публикации',
                'property_features' => 'Особенности',
                'imageFiles' => 'Загрузить фото',
                'photos_sequence' => 'Фото',
            ]
        );
    }

    public function loadAttributes()
    {
        $this->id = $this->_propertyModel->id;
        $this->property_name = $this->_propertyModel->property_name;
        $this->property_type = $this->_propertyModel->property_type;
        if ($this->_propertyModel->is_rent) {
            $this->ad_type[] = 'is_rent';
        }
        if ($this->_propertyModel->is_sale) {
            $this->ad_type[] = 'is_sale';
        }
        $this->currency = $this->_propertyModel->currency;
        $this->sale_price = $this->_propertyModel->sale_price;
        $this->rent_price = $this->_propertyModel->rent_price;
        $this->address = $this->_propertyModel->address;
        $this->map_latitude = $this->_propertyModel->map_latitude;
        $this->map_longitude = $this->_propertyModel->map_longitude;
        $this->direction_id = $this->_propertyModel->direction_id;
        $this->distance_to_mrar = $this->_propertyModel->distance_to_mrar;
        $this->with_finishing = $this->_propertyModel->with_finishing;
        $this->with_furniture = $this->_propertyModel->with_furniture;
        $this->bathrooms = $this->_propertyModel->bathrooms;
        $this->bedrooms = $this->_propertyModel->bedrooms;
        $this->garage = $this->_propertyModel->garage;
        $this->land_area = $this->_propertyModel->land_area;
        $this->build_area = $this->_propertyModel->build_area;
        $this->description = $this->_propertyModel->description;
        $this->is_archive = $this->_propertyModel->is_archive;
        if (empty($this->with_finishing)) {
            $this->with_finishing = '🛇';
        }
        if (empty($this->with_furniture)) {
            $this->with_furniture = '🛇';
        }
        if (empty($this->bathrooms)) {
            $this->bathrooms = '🛇';
        }
        if (empty($this->bedrooms)) {
            $this->bedrooms = '🛇';
        }
        if (empty($this->garage)) {
            $this->garage = '🛇';
        }
        $tmp = $this->_propertyModel->getPropertyFeatures()->all();
        foreach ($tmp as $propertyFeature) {
            $this->property_features[$propertyFeature->id] = $propertyFeature->name;
        }

        $tmp = $this->_propertyModel->getPropertyPhotos()->orderBy('position')->all();
        if (!empty($tmp)) {
            foreach ($tmp as $key => $propertyPhoto) {
                $this->photos_sequence[] = $propertyPhoto->photo_id;
                $tmp[$key] = $propertyPhoto->getPhoto()->one();
            }
            $this->photos_sequence = implode(',', $this->photos_sequence);
            foreach ($tmp as $photo) {
                $this->photos[$photo->id] = $photo->name;
            }
        }
    }
}
