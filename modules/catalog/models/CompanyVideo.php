<?php

namespace app\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "{{%company_video}}".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $url
 * @property string $description
 * @property integer $sort
 *
 * @property Company $company
 */
class CompanyVideo extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company_video}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id'], 'required'],
            [['company_id', 'sort'], 'integer'],
            [['description'], 'string'],
            [['url'], 'string', 'max' => 255]
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
            'url' => 'Url',
            'description' => 'Description',
            'sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
