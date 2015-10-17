<?php
use yii\helpers\Html;
use app\modules\blog\widgets\TopPost;
use app\modules\blog\widgets\TagCloud;
use app\modules\core\widgets\SearchPost;
use app\modules\core\widgets\VkGroup;

/**
 * @var yii\web\View $this
 * CacheIds = ['main_slider', 'index_content']
 */
$this->title = $model ? $model->meta_title : Yii::$app->name;
if ($model) {
    $this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description], 'meta-description');
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords], 'meta-keywords');
}
?>

<!-- Page content starts -->

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <!-- Index Content -->
                <article class="index">
                    <h1><?= $model ? $model->title : $this->title ?></h1>
                    <?= $model ? $model->content : '' ?>
                    <div class="clearfix"></div>
                </article>
            </div>
            <div class="col-md-4 col-sm-12">
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Search Post -->
                    <?= SearchPost::widget() ?>

                    <!-- Recent Posts widget -->
                    <?= TopPost::widget() ?>

                    <!-- Vk.com Group widget -->
                    <?= VkGroup::widget() ?>

                    <!-- Tag cloud widget -->
                    <?= TagCloud::widget() ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content ends -->