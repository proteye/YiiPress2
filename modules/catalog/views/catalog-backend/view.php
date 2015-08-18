<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\Company */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'slug',
            'name',
            'email:email',
            'short_descr',
            'description:ntext',
            'logo',
            'site',
            'skype',
            'icq',
            'link_vk',
            'link_fb',
            'link_in',
            'rating',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'published_at',
            'user_ip',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'comment_status',
            'view_count',
            'status',
        ],
    ]) ?>

</div>
