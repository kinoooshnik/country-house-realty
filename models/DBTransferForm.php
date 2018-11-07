<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class DBTransferForm extends Model
{
    public $json;
    private $objSlug;
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['json'], 'string'],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1000],
        ];
    }

    public function save()
    {
        $content = json_decode($this->json, true);
        foreach ($content as $obj) {
            $property = new Property();
            $property->property_name = $obj['property_name'];
            $property->property_type = strtr($obj['property_type'], [1 => 'Дом', 2 => 'Таунхаус', 3 => 'Квартира', 4 => 'Участок']);
            if ($obj['property_purpose'] == 'Продажа') {
                $property->is_sale = true;
                $property->is_rent = false;
            } else {
                $property->is_rent = true;
                $property->is_sale = false;
            }
            $property->currency = $obj['currency'];
            $property->price = $obj['price'];
            $property->address = $obj['address'];
            $property->map_latitude = $obj['map_latitude'];
            $property->map_longitude = $obj['map_longitude'];
            $property->direction_id = $obj['direction'];
            $property->distance_to_mrar = $obj['range'];
            if ($obj['readiness'] == 1 || $obj['readiness'] == 3 || $obj['readiness'] == 4) {
                $property->with_finishing = true;
            } elseif ($obj['readiness'] == 2) {
                $property->with_finishing = false;
            }
            if ($obj['readiness'] == 3 || $obj['readiness'] == 1) {
                $property->with_furniture = true;
            } elseif ($obj['readiness'] == 4) {
                $property->with_furniture = false;
            }
            $property->bathrooms = $obj['bathrooms'];
            $property->bedrooms = $obj['bedrooms'];
            $property->garage = $obj['garage'];
            $property->land_area = $obj['land_area'];
            $property->build_area = $obj['build_area'];
            $property->description = $obj['description'];
            $property->user_id = $obj['user_id'];
            $property->is_archive = false;

            $property_features = explode(' ', $obj['property_features']);
            if ($property->save() && is_array($property_features)) {
                $propertyFeaturesExist = $property->getPropertyFeatures()->all();
                $propertyFeaturesSelected = (new \ArrayObject($property_features))->getArrayCopy();

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
                        ->andWhere(['property_id' => $property->id])
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
                    $tmp->property_id = $property->id;
                    $tmp->property_features_id = $propertyFeature->id;
                    $tmp->save();
                }
            }
            $this->objSlug[] = [
                'model' => $property,
                'slug' => $obj['property_slug'],
            ];
//            echo \yii\helpers\Json::encode($property, JSON_PRETTY_PRINT);
            break;
        }
        return true;
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $fileName = preg_replace('/_(\d+)-b/i', '', $file->baseName);
                $model = null;
                foreach ($this->objSlug as $obj) {
                    if (stripos($fileName, $obj['slug']) !== false) {
                        $model = $obj['model'];
                    }
                }
                if (empty($model)) {
                    continue;
                }
                $photoName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                $uploadDir = Yii::getAlias('@app') . '/web/uploads/property/original';
                if ($file->saveAs($uploadDir . '/' . $photoName)) {
                    $photoModel = new Photo();
                    $photoModel->name = $photoName;
                    if ($photoModel->save()) {
                        $newPropertyPhotoModel = new PropertyPhoto();
                        $newPropertyPhotoModel->property_id = $model->id;
                        $newPropertyPhotoModel->photo_id = $photoModel->id;
                        $maxPosition = PropertyPhoto::find()->max('position');
                        $newPropertyPhotoModel->position = $maxPosition + 1;
                        if (!$newPropertyPhotoModel->save()) {
                            Yii::$app->session->setFlash('error', 'Не удалось присоединить фото ' . $photoName . ' к объекту.');
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Не удалось создать запись в БД о фото ' . $photoName . '.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось загрузить фото ' . $file->baseName . '.' . $file->extension . '.');
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
