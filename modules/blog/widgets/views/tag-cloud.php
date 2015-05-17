<?php
use yii\helpers\Html;
?>

<?php if ($model): ?>

    <div class="widget">
        <h4>Метки</h4>
        <div class="tags">
            <?php foreach ($model as $tag): ?>
                <?= Html::a($tag->title, $tag->url, ['title' => '']) ?>
            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>