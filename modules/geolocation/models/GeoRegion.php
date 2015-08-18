<?php

namespace app\modules\geolocation\models;

use Yii;

/**
 * This is the model class for table "{{%geo_region}}".
 *
 * @property integer $id
 * @property integer $geo_country_id
 * @property string $slug
 * @property string $name
 *
 * @property GeoCity[] $geoCities
 * @property GeoCountry $geoCountry
 */
class GeoRegion extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['geo_country_id', 'name'], 'required'],
            [['geo_country_id'], 'integer'],
            [['slug', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'geo_country_id' => 'Geo Country ID',
            'slug' => 'Slug',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCities()
    {
        return $this->hasMany(GeoCity::className(), ['geo_region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id' => 'geo_country_id']);
    }
}
