<?php

namespace app\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string $email
 * @property string $short_description
 * @property string $description
 * @property string $logo
 * @property string $site
 * @property string $skype
 * @property string $icq
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_in
 * @property double $rating
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property string $user_ip
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $comment_status
 * @property string $view_count
 * @property integer $status
 *
 * @property User $updatedBy
 * @property User $createdBy
 * @property CompanyAddress[] $companyAddresses
 * @property CompanyImage[] $companyImages
 * @property Image[] $images
 * @property CompanyVideo[] $companyVideos
 */
class Company extends \app\modules\core\models\CoreModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug', 'name', 'email', 'created_at', 'updated_at', 'published_at'], 'required'],
            [['description'], 'string'],
            [['rating'], 'number'],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'published_at', 'comment_status', 'view_count', 'status'], 'integer'],
            [['slug', 'skype'], 'string', 'max' => 160],
            [['name', 'email', 'logo', 'site', 'link_vk', 'link_fb', 'link_in'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 512],
            [['icq', 'user_ip'], 'string', 'max' => 20],
            [['meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 250]
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
            'email' => 'Email',
            'short_description' => 'Short Description',
            'description' => 'Description',
            'logo' => 'Logo',
            'site' => 'Site',
            'skype' => 'Skype',
            'icq' => 'Icq',
            'link_vk' => 'Link Vk',
            'link_fb' => 'Link Fb',
            'link_in' => 'Link In',
            'rating' => 'Rating',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'published_at' => 'Published At',
            'user_ip' => 'User Ip',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'comment_status' => 'Comment Status',
            'view_count' => 'View Count',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyAddresses()
    {
        return $this->hasMany(CompanyAddress::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyImages()
    {
        return $this->hasMany(CompanyImage::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['id' => 'image_id'])->viaTable('{{%company_image}}', ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyVideos()
    {
        return $this->hasMany(CompanyVideo::className(), ['company_id' => 'id']);
    }
}
