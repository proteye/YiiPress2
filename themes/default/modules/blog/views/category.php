<?php
use yii\helpers\Html;
use app\modules\core\components\YpLinkPager;
use app\modules\blog\widgets\RecentPost;
use app\modules\blog\widgets\TagCloud;
use app\modules\core\widgets\SearchPost;
use app\modules\core\widgets\VkGroup;

/**
 * @var yii\web\View $this
 */
$this->title = $model->meta_title ? $model->meta_title . ' | ' . Yii::$app->name : $model->title . ' | ' . Yii::$app->name;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords], 'meta-keywords');
?>

<!-- Page heading starts -->

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $model->name ?></h1>
            </div>
        </div>
    </div>
</div>

<!-- Page Heading ends -->

<?php if ($model->description): ?>
<!-- Category content starts -->

<div class="content category">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= $model->content ?>
            </div>
        </div>
    </div>
</div>

<!-- Category content ends -->
<?php endif; ?>

<?php if ($posts): ?>
<!-- Page content starts -->

<div class="content blog">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="posts">
                <?php foreach ($posts as $key => $post): ?>
                    <!-- Post <?= $key + 1 ?> -->
                    <div>
                        <h2><?= Html::a($post->title, $post->url) ?></h2>

                        <?php if ($post->image): ?>
                            <!-- Thumbnail -->
                            <div>
                                <?= Html::beginTag('a', ['href' => $post->url]) ?>
                                <?= Html::img($post->thumbUrl, ['class' => 'img-responsive', 'alt' => $post->image_alt]) ?>
                                <?= Html::endTag('a') ?>
                            </div>
                        <?php endif; ?>
                        <!-- Para -->
                        <p><?= $post->quote ?></p>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>

                    <!-- Pagination -->
                    <?php
                    echo YpLinkPager::widget([
                        'pagination' => $pages,
                        'activePageCssClass' => 'current',
                        'maxButtonCount' => 6,
                        'firstPageLabel' => '<i class="fa fa-angle-left"></i>',
                        'lastPageLabel' => '<i class="fa fa-angle-right"></i>',
                        'prevPageLabel' => '<i class="fa fa-angle-double-left"></i>',
                        'nextPageLabel' => '<i class="fa fa-angle-double-right"></i>',
                        'options' => [
                            'class' => 'paging',
                        ],
                        //'dotsPageLabel' => '...',
                    ]);
                    ?>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Search Post -->
                    <?= SearchPost::widget() ?>

                    <!-- Recent Posts widget -->
                    <?= RecentPost::widget() ?>

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
<?php endif; ?>