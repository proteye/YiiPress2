<?php

namespace app\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "{{%company_phone}}".
 *
 * @property integer $id
 * @property integer $company_address_id
 * @property integer $phone_country
 * @property integer $phone_city
 * @property string $phone_number
 * @property integer $sort
 *
 * @property CompanyAddress $companyAddress
 */
class CompanyPhone extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company_phone}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_address_id'], 'required'],
            [['company_address_id', 'phone_country', 'phone_city', 'phone_number', 'sort'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_address_id' => 'Company Address ID',
            'phone_country' => 'Phone Country',
            'phone_city' => 'Phone City',
            'phone_number' => 'Phone Number',
            'sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyAddress()
    {
        return $this->hasOne(CompanyAddress::className(), ['id' => 'company_address_id']);
    }
}
