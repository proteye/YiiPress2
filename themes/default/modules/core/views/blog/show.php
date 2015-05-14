<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Post Show';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $model->content ?>
    </p>

    <code><?= __FILE__ ?></code>
</div>
