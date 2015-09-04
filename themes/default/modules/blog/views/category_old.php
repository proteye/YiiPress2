<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\core\components\YpLinkPager;

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

<div class="content blog blog-mul-col">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <!-- Block style layout. Use the class "pblock" -->
                <div class="posts pblock">

                    <!-- Each posts should be enclosed inside "entry" class" -->
                    <div class="row">
                    <?php foreach($posts as $key => $post): ?>
                        <div class="col-md-4 col-sm-4">
                            <!-- Post #<?= $post->id ?> -->
                            <div class="entry">
                                <h2><a href="<?= $post->url ?>"><?= $post->title ?></a></h2>
                                <!-- Meta details -->
                                <div class="meta">
                                    <i class="fa fa-calendar"></i> <?= strftime('%B %e, %Y', $post->published_at) ?>
                                    <span class="pull-right">
                                        <i class="fa fa-comment"></i>
                                        <?= Html::a('Комментировать', $post->url . '#disqus_thread', ['data-disqus-identifier' => $post->id]) ?>
                                    </span>
                                </div>
                                <?php if ($post->image): ?>
                                <!-- Thumbnail -->
                                <div class="bthumb">
                                    <a href="<?= $post->url ?>"><img src="<?= $post->getThumbUrl(320, 240) ?>" alt="<?= $post->image_alt ?>" class="img-responsive"/></a>
                                </div>
                                <?php endif; ?>
                                <!-- Para -->
                                <p><?= $post->quote ?></p>
                                <div class="meta2">
                                    <i class="fa fa-user"></i> <?= $post->user->username ?>
                                    <i class="fa fa-folder-open"></i>
                                    <?= ($post->category->url != Url::to()) ? '<a href="' . $post->category->url . '">' : null ?>
                                    <?= $post->category->name ?>
                                    <?= ($post->category->url != Url::to()) ? '</a>' : null ?>
                                </div>
                            </div>
                        </div>
                    <?= (($key+1) % 3 == 0) ? '</div><div class="row">' : null ?>
                    <?php endforeach; ?>
                    </div>

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
        </div>
    </div>
</div>

<!-- Page content ends -->
<?php endif; ?>