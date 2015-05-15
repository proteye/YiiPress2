<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = $model->title . ' | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => 'Найденные записи по тегу: ' . $model->title], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => 'тег метка ' . $model->title], 'meta-keywords');
?>
<div>
    <h1>Тег: <?= Html::encode($model->title) ?></h1>

    <ul>
        <?php
        foreach ($model->posts as $post) {
            echo '<li>' . Html::a($post->title, $post->url) . '</li>';
            echo '<li>' . strftime('%B %e, %Y', $post->published_at) . '</li>';
            echo '<li>' . $post->image ? Html::img($post->fileUrl, ['alt' => $post->image_alt]) : null . '</li>';
            echo '<li>' . Html::encode($post->quote) . '</li>';
            echo '<li>' . Html::encode($post->user->username) . '</li>';
            echo '<li>' . Html::encode($post->category->name) . '</li>';
        }
        ?>
    </ul>
</div>
