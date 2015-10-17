<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<!-- Page content starts -->

<div class="content error-page">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="big-text">404</div>
                <hr />
            </div>
            <div class="col-md-7 col-md-offset-1 col-sm-6">
                <h2>Упс<span class="color">!!!</span></h2>
                <h4>Страница не найдена</h4>
                <hr />
                <div class="alert alert-danger">
                    <?= nl2br(Html::encode($message)) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content ends -->