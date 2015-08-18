<?php

namespace app\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "{{%company_address}}".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $city_id
 * @property string $address
 * @property integer $sort
 *
 * @property GeoCity $city
 * @property Company $company
 * @property CompanyPhone[] $companyPhones
 */
class CompanyAddress extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'city_id'], 'required'],
            [['company_id', 'city_id', 'sort'], 'integer'],
            [['address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'city_id' => 'City ID',
            'address' => 'Address',
            'sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyPhones()
    {
        return $this->hasMany(CompanyPhone::className(), ['company_address_id' => 'id']);
    }
}
