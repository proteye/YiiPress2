<?php
use yii\helpers\Html;
?>

<?php if ($model): ?>

    <div class="widget">
        <h4>Материалы по теме</h4>
        <ul>
            <?php foreach ($model as $post): ?>
                <li>
                    <!-- Title -->
                    <h3><?= Html::a($post->title, $post->url) ?></h3>
                    <?php if ($post->image): ?>
                        <!-- Thumbnail -->
                        <div class="bthumb4">
                            <?= Html::beginTag('a', ['href' => $post->url]) ?>
                            <?= Html::img($post->thumbUrl, ['class' => 'img-responsive', 'alt' => $post->image_alt]) ?>
                            <?= Html::endTag('a') ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>