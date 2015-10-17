<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\modules\blog\widgets\TopicCategory;
use app\modules\blog\widgets\TagCloud;
use app\modules\category\models\Category;
use app\modules\core\widgets\SearchPost;
use app\modules\core\widgets\VkGroup;

/**
 * @var yii\web\View $this
 */
$this->title = $model->meta_title ? $model->meta_title : $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords], 'meta-keywords');

// Breadcrumbs
foreach (Category::getBreadcrumbs($model->category->id) as $breadcrumb)
    $this->params['breadcrumbs'][] = ['label' => $breadcrumb['label'], 'url' => $breadcrumb['url']];
?>

<!-- Page heading starts -->

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="h2"><?= $model->category->name ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Page Heading ends -->

<!-- Page content starts -->

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div>
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <div>
                    <!-- Post -->
                    <article>

                        <h1><?= $model->title ?></h1>

                        <?php if ($model->image): ?>
                            <!-- Thumbnail -->
                            <div>
                                <?= Html::img($model->getThumbUrl(620, 310), ['class' => 'img-responsive', 'alt' => $model->image_alt]) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Content -->
                        <?= $model->content ?>
                    </article>
                    <!-- /Post -->
                    
                    <hr />

                    <div class="clearfix"></div>
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Topic Category widget -->
                    <?= TopicCategory::widget([
                        'category_id' => $model->category->id,
                    ]) ?>

                    <!-- Search Post -->
                    <?= SearchPost::widget() ?>
                    
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