<?php

namespace app\modules\geolocation\models;

use Yii;

/**
 * This is the model class for table "{{%geo_city}}".
 *
 * @property integer $id
 * @property integer $geo_region_id
 * @property integer $geo_country_id
 * @property string $slug
 * @property string $name
 *
 * @property CompanyAddress[] $companyAddresses
 * @property GeoCountry $geoCountry
 * @property GeoRegion $geoRegion
 */
class GeoCity extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['geo_region_id', 'geo_country_id', 'name'], 'required'],
            [['geo_region_id', 'geo_country_id'], 'integer'],
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
            'geo_region_id' => 'Geo Region ID',
            'geo_country_id' => 'Geo Country ID',
            'slug' => 'Slug',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyAddresses()
    {
        return $this->hasMany(CompanyAddress::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id' => 'geo_country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoRegion()
    {
        return $this->hasOne(GeoRegion::className(), ['id' => 'geo_region_id']);
    }
}
