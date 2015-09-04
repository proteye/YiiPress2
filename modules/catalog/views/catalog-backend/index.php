<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\catalog\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'slug',
            'name',
            'email:email',
            'short_description',
            // 'description:ntext',
            // 'logo',
            // 'site',
            // 'skype',
            // 'icq',
            // 'link_vk',
            // 'link_fb',
            // 'link_in',
            // 'rating',
            // 'created_by',
            // 'updated_by',
            // 'created_at',
            // 'updated_at',
            // 'published_at',
            // 'user_ip',
            // 'meta_title',
            // 'meta_keywords',
            // 'meta_description',
            // 'comment_status',
            // 'view_count',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
