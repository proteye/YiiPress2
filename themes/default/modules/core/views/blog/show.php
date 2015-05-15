<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = $model->meta_title;
$this->params['breadcrumbs'][] = $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords], 'meta-keywords');
?>
<div>
    <h1><?= Html::encode($model->title) ?></h1>

    <ul>
        <?php
        echo '<li>';
        foreach ($model->postTags as $tag) {
            echo Html::a($tag->title, $tag->url) . '<br/>';
        }
        echo '</li>';
        echo '<li>' . strftime('%B %e, %Y', $model->published_at) . '</li>';
        echo '<li>' . Html::encode($model->user->username) . '</li>';
        echo '<li>' . Html::encode($model->category->name) . '</li>';
        echo '<li>' . $model->image ? Html::img($model->fileUrl, ['alt' => $model->image_alt]) : null . '</li>';
        echo '<li>' . $model->content . '</li>';
        ?>
    </ul>
</div>
