<?php
use yii\helpers\Html;
?>

<div class="widget">
    <h4>Свежие записи</h4>
    <ul>
        <?php foreach ($model as $post): ?>
            <li>
                <?php if ($post->image): ?>
                    <!-- Thumbnail -->
                    <div class="bthumb4">
                        <?= Html::beginTag('a', ['href' => $post->url]) ?>
                        <?= Html::img($post->thumbUrl, ['class' => 'img-responsive', 'alt' => $post->image_alt]) ?>
                        <?= Html::endTag('a') ?>
                    </div>
                <?php endif; ?>
                <!-- Title -->
                <h3><?= Html::a($post->title, $post->url) ?></h3>
                <!-- Meta Date -->
                <span class="date">
                    <i class="fa fa-calendar"></i> <?= strftime('%B %e, %Y', $post->published_at) ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
