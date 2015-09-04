<?php
use yii\helpers\Html;
use app\modules\core\components\YpLinkPager;
use app\modules\blog\widgets\RecentPost;
use app\modules\blog\widgets\RecentDisqus;
use app\modules\blog\widgets\TagCloud;
use app\modules\core\widgets\SearchPost;
/**
 * @var yii\web\View $this
 */
$this->title = 'Тег - ' . $model->title . ' | ' . Yii::$app->name;
$this->registerMetaTag(['name' => 'description', 'content' => 'Найденные записи по тегу - ' . $model->title], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'найденные записи тег ' . $model->title], 'meta-keywords');
?>

<!-- Page heading starts -->

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $model->title ?></h1>
                <div class="h4">архив тега</div>
            </div>
        </div>
    </div>
</div>

<!-- Page Heading ends -->

<!-- Page content starts -->

<div class="content blog">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
            <?php if ($posts): ?>
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
                                    <?= Html::a($post->category->name, $post->category->url, ['rel' => 'nofollow']) ?>
                                </noindex>
                                <?php /*
                                <i class="fa fa-comment"></i>
                                <?= Html::a('Комментировать', $post->url . '#disqus_thread', ['data-disqus-identifier' => $post->id]) ?>
                                */ ?>
                            </span>
                        </div>
                    <?php if ($post->image): ?>
                        <!-- Thumbnail -->
                        <div class="bthumb3">
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
            <?php else: ?>
                <p>Сожалеем, но по Вашему запросу ничего не найдено. Пожалуйста уточните поиск с использованием других запросов.</p>
            <?php endif; ?>
            </div>
            <div class="col-md-4">
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Search Post -->
                    <?= SearchPost::widget() ?>

                    <!-- Recent Posts widget -->
                    <?= RecentPost::widget() ?>

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