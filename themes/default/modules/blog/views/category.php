<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\core\components\YpLinkPager;
use app\modules\blog\widgets\RecentPost;
use app\modules\blog\widgets\TagCloud;
use app\modules\blog\widgets\RecentDisqus;
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
                    <div class="entry">
                        <h2><?= Html::a($post->title, $post->url) ?></h2>

                        <!-- Meta details -->
                        <div class="meta">
                            <i class="fa fa-user"></i> <?= $post->user->userProfile->nick_nm ?> <i class="fa fa-eye"></i> <?= $post->view_count ?>
                            <span class="pull-right">
                                <i class="fa fa-folder-open"></i>
                                <noindex>
                                    <?= ($post->category->url != Url::to()) ? '<a href="' . $post->category->url . '" rel="nofollow">' : null ?>
                                    <?= $post->category->name ?>
                                    <?= ($post->category->url != Url::to()) ? '</a>' : null ?>
                                </noindex>
                                <?php /*
                                <i class="fa fa-comment"></i>
                                <noindex>
                                <?= Html::a('Комментировать', $post->url . '#disqus_thread', ['data-disqus-identifier' => $post->id, 'rel' => 'nofollow']) ?>
                                </noindex>
                                */ ?>
                            </span>
                        </div>
                        <?php if ($post->image): ?>
                            <!-- Thumbnail -->
                            <div class="bthumb3">
                                <noindex>
                                <?= Html::beginTag('a', ['href' => $post->url, 'rel' => 'nofollow']) ?>
                                <?= Html::img($post->thumbUrl, ['class' => 'img-responsive', 'alt' => $post->image_alt]) ?>
                                <?= Html::endTag('a') ?>
                                </noindex>
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

                    <!-- Recent Comments widget -->
                    <?= RecentDisqus::widget() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content ends -->
<?php endif; ?>