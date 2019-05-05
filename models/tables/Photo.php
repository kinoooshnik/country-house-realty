<?php

namespace app\models\tables;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "photo".
 *
 * @property int $id
 * @property string $name
 * @property int $uploaded_at
 *
 * @property PropertyPhoto[] $propertyPhotos
 * @property Property[] $properties
 */
class Photo extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs(Yii::getAlias('@webroot') . $this->name);
            $this->imageFile = null;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generate name based imageFile name and extension
     */
    public function generateName()
    {
        if (isset($this->imageFile)) {
            $this->name = Yii::getAlias('@uploadDir/') . $this->imageFile->baseName . '.' . $this->imageFile->extension;
        }
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['uploaded_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['uploaded_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'uploaded_at' => 'Uploaded At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyPhotos()
    {
        return $this->hasMany(PropertyPhoto::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(Property::className(), ['id' => 'property_id'])->viaTable('property_photo', ['photo_id' => 'id']);
    }

    public function getPath()
    {
        return $this->name;
    }

    public function getPathToSmall()
    {
        return $this->getPath();
    }
}
