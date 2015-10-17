<?php
use yii\helpers\Html;
?>

<?php if ($model): ?>

    <div class="widget">
        <div>Метки</div>
        <div>
            <?php foreach ($model as $tag): ?>
                <?= Html::a($tag->title, $tag->url, ['title' => '']) ?>
            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>