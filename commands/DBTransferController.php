<?php

namespace app\commands;

use app\models\Property;
use app\models\PropertyFeatures;
use app\models\PropertyPropertyFeatures;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

class DBTransferController extends Controller
{
    public function actionTransfer($pathToDBJson, $pathToPhotosFolder)
    {
        $string = file_get_contents($pathToDBJson);
        $content = json_decode($string, true);
        foreach ($content as $obj) {
            $property = new Property();
            $property->property_name = $obj['property_name'];
            $property->property_type = strtr($obj['property_type'], [1 => 'Дом', 2 => 'Таунхаус', 3 => 'Квартира', 4 => 'Участок']);
            if ($obj['property_purpose'] == 'Продажа') {
                $property->is_sale = true;
            } else {
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
            } elseif($obj['readiness'] == 2) {
                $property->with_finishing = false;
            }
            if($obj['readiness'] == 3 || $obj['readiness'] == 1) {
                $property->with_furniture = true;
            } elseif($obj['readiness'] == 4) {
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

            echo \yii\helpers\Json::encode($property, JSON_PRETTY_PRINT);
            break;
        }
//        {
//            "id": 1,
//    "user_id": 1,
//    "featured_property": 0,
//    "property_name": "Дом, Московская область",
//    "property_slug": "dom-moskovskaya-oblast",
//    "property_type": "1",
//    "property_purpose": "Продажа",
//    "direction": "1",
//    "range": "17",
//    "readiness": "1",
//    "currency": "$",
//    "price": "1700000",
//    "crossrubl": "112157670",
//    "crossdollar": "1700000",
//    "crosseuro": "1466023",
//    "address": "Бузаево, Московская область, Россия",
//    "map_latitude": "55.7186982",
//    "map_longitude": "37.12152409999999",
//    "bathrooms": null,
//    "bedrooms": null,
//    "garage": null,
//    "land_area": "24",
//    "build_area": "400",
//    "description": "<p>Описание: Бузаево. Закрытый клубный поселок всего в 17 км от МКАД, а потому надежно защищен от шума и суеты городской жизни.Очень удобный подъезд к поселку, есть второй выезд на случай затруднения движения по Рубдево-Успенскому шоссе. Дом окружен лесным участком и имеет свой выход в лес. Архитектура дома напоминает архитектуру викторианской, изысканной Англии и создает атмосферу спокойствия и благочинности, в то время как интерьер дома выполнен по самым современным стандартам и впечатляет своей продуманностью и вниманием к деталям. Планировка : 1 этаж - холл, блок для прислуги, постирочная, гостевая спальня со своим сан узлом, гардеробная, библиотека с камином, каминый зал, зимний сад, столовая, просторная кухня, гостевая спальня с ванной комнатой, блок для персонала с отдельным входом, со своей кухней и с/у, постирочная, котельная. 2 этаж- хозяйская спальня с просторной ванной комнатой, две спальни, ванная. Мансарда - кабинет, детская игровая, с/у.</p>",
//    "property_features": null,
//    "featured_image": "dom-moskovskaya-oblast-c150275626bb4cfe3f7885074cc2b8d7",
//    "floor_plan": null,
//    "video_code": "",
//    "status": 1,
//    "created_at": null,
//    "updated_at": "2018-10-12 12:00:03"
//  },
// * @property Direction $direction
//    * @property PropertyPhoto[] $propertyPhotos
//    * @property Photo[] $photos
//    * @property PropertyPropertyFeatures[] $propertyPropertyFeatures
//    * @property PropertyFeatures[] $propertyFeatures
    }
}