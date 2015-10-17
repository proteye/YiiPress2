<?php
use yii\helpers\Html;
use app\modules\core\components\YpLinkPager;
use app\modules\blog\widgets\RecentPost;
use app\modules\blog\widgets\TagCloud;
use app\modules\core\widgets\SearchPost;

/**
 * @var yii\web\View $this
 */
$this->title = 'Вы искали - ' . $query . ' | ' . Yii::$app->name;
$this->registerMetaTag(['name' => 'description', 'content' => 'Найденные записи по запросу - ' . $query], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'найденные записи запрос ' . $query], 'meta-keywords');
?>

<!-- Page heading starts -->

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Поиск</h1>
                <div>Результаты поиска для: <?= $query ?></div>
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
            <?php if ($model): ?>
                <div>
                <?php foreach ($model as $key => $post): ?>
                    <!-- Post <?= $key + 1 ?> -->
                    <div>
                        <h2><?= Html::a($post->title, $post->url) ?></h2>
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content ends -->