<?php

namespace app\modules\geolocation\models;

use Yii;

/**
 * This is the model class for table "{{%geo_country}}".
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 *
 * @property GeoCity[] $geoCities
 * @property GeoRegion[] $geoRegions
 */
class GeoCountry extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['slug'], 'string', 'max' => 160],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCities()
    {
        return $this->hasMany(GeoCity::className(), ['geo_country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoRegions()
    {
        return $this->hasMany(GeoRegion::className(), ['geo_country_id' => 'id']);
    }
}
