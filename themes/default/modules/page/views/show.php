<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */
$this->title = $model->meta_title ? $model->meta_title : $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords], 'meta-keywords');
?>

<!-- Page heading starts -->

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $model->title ?></h1>
            </div>
        </div>
    </div>
</div>

<!-- Page Heading ends -->

<!-- Page content starts -->

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <?= $model->content ?>
            </div>
        </div>
    </div>
</div>

<!-- Page content ends -->