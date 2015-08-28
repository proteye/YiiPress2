<?php

namespace app\modules\category\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%category_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 * @property Category[] $categories
 */
class CategoryType extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['type_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getItemsList()
    {
        $model = self::find()->all();

        return ArrayHelper::map($model, 'id', 'description');
    }
}
