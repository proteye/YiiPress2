<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = $model->meta_title;
$this->params['breadcrumbs'][] = $model->name;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords], 'meta-keywords');
?>
<div>
    <h1><?= Html::encode($model->name) ?></h1>

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
