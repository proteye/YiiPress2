<?php
use yii\helpers\Html;
use app\modules\core\widgets\SearchPost;
use app\modules\blog\widgets\RecentPost;
use app\modules\blog\widgets\TagCloud;

/* @var $this yii\web\View */

$this->title = 'Карта сайта';
?>

<!-- Page heading starts -->

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $this->title ?></h1>
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
                <ul>
                    <?php foreach ($model as $category): ?>
                    <li><strong>Категория:</strong> <?= Html::a($category->name, $category->url) ?>
                        <?php if ($category->getCategories(true)): ?>
                        <ul>
                            <?php foreach ($category->getCategories(true) as $children): ?>
                            <li><strong>Категория:</strong> <?= Html::a($children->name, $children->url) ?>
                                <?php if ($children->getPosts(true)): ?>
                                    <ul>
                                    <?php foreach ($children->getPosts(true) as $post): ?>
                                        <li><?= Html::a($post->title, $post->url) ?></li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
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