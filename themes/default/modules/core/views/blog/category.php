<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    foreach ($posts as $post) {
        echo '<p>' . Html::a($post['title'], $post['url']) . '</p>';
        echo '<p>' . Html::encode($post['quote']) . '</p>';

    }
    ?>

    <code><?= __FILE__ ?></code>
</div>
