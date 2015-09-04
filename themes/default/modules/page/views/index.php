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

<!-- Flex Slider starts -->
<div class="container flex-main">
    <div class="row">
        <div class="col-md-12">

            <?php if ($this->beginCache('main_slider', ['duration' => Yii::$app->getModule('core')->cacheTime])): ?>
            <div class="flex-image flexslider">
                <ul class="slides">
                    <li>
                        <!-- Slide #1 -->
                        <div class="featured-posts">
                    <?php foreach ($slider_posts as $key => $post): ?>
                        <?php if ($key != 0 && ($key % 5) == 0) {
                            echo '
                        </div>
                    </li>
                    <li>
                        <!-- Slide #' . ($key / 5 + 1) . '-->
                        <div class="featured-posts">
                        ';
                        }
                        ?>
                        <!-- Post #<?= $key + 1 ?> -->
                        <div class="featured-post featured-post-<?= $key % 5 + 1?>">
                            <div class="featured-post-inner" style="background-image: url('<?= $post->image ? $post->getThumbUrl(500, 375) : '' ?>')">
                                <div class="featured-cover">
                                    <a href="<?= $post->url ?>"></a>
                                </div>
                                <a href="<?= $post->url ?>">
                                    <div class="featured-title">
                                        <div class="featured-h2"><?= $post->title ?></div>
                                        <noindex>
                                            <div class="featured-h3"><?= $post->getShortQuote(100) ?></div>
                                        </noindex>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                        </div>
                    </li>
                </ul>
            </div>
                <?php $this->endCache();
            endif; ?>

        </div>
    </div>
</div>
<!-- Flex slider ends -->

<!-- Page content starts -->

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12">

                <?php if ($this->beginCache('index_content', ['duration' => Yii::$app->getModule('core')->cacheTime])): ?>
                <?php if ($categories): ?>
                <!-- Category Box -->
                <?php foreach($categories as $cat): ?>
                <?php if($cat->allPosts): ?>
                <section class="cat-box">
                    <div class="cat-box-title">
                        <noindex>
                        <div class="h2 title"><?= Html::a($cat->name, $cat->url,  ['rel' => 'nofollow']) ?></div>
                        </noindex>
                    </div>
                    <div class="cat-box-content">
                        <ul>
                            <?php $k = 0; ?>
                            <?php foreach($cat->getAllPosts(5) as $post): ?>
                                <?php if($k == 0): $k++; ?>
                                    <li class="first-post col-md-6 col-sm-12 col-xs-12">
                                        <?php if ($post->image): ?>
                                        <!-- Thumbnail -->
                                        <div class="bthumb">
                                            <noindex>
                                            <a href="<?= $post->url ?>" rel="nofollow"><img src="<?= $post->getThumbUrl(320, 240) ?>" alt="<?= $post->image_alt ?>"/></a>
                                            </noindex>
                                        </div>
                                        <?php endif; ?>
                                        <!-- Title -->
                                        <div class="h2"><a href="<?= $post->url ?>"><?= $post->title ?></a></div>
                                        <!-- Meta details -->
                                        <div class="meta">
                                            <i class="fa fa-folder-open"></i>
                                            <?= $post->category->name ?>
                                            <span class="pull-right">
                                                <i class="fa fa-eye"></i> <?= (int)$post->view_count ?>
                                            </span>
                                        </div>
                                        <!-- Announce -->
                                        <div class="announce">
                                            <p><?= $post->shortQuote ?></p>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                <?php else: ?>
                                    <li class="other-post col-md-6 col-sm-12 col-xs-12">
                                        <?php if ($post->image): ?>
                                        <!-- Thumbnail -->
                                        <div class="bthumb2">
                                            <noindex>
                                            <a href="<?= $post->url ?>" rel="nofollow"><img src="<?= $post->thumbUrl ?>" alt="<?= $post->image_alt ?>"/></a>
                                            </noindex>
                                        </div>
                                        <?php endif; ?>
                                        <!-- Title -->
                                        <div class="h3"><a href="<?= $post->url ?>"><?= $post->title ?></a></div>
                                        <!-- Meta details -->
                                        <div class="meta">
                                            <i class="fa fa-eye"></i> <?= (int)$post->view_count ?>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </section>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
                    <?php $this->endCache();
                endif; ?>
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