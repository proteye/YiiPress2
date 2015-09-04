<?php
use yii\helpers\Html;
?>

<?php if ($model): ?>

    <div class="widget">
        <h4>Материалы по теме</h4>
        <ul>
            <?php foreach ($model as $post): ?>
                <li>
                    <?php if ($post->image): ?>
                        <!-- Thumbnail -->
                        <div class="bthumb4">
                            <noindex>
                            <?= Html::beginTag('a', ['href' => $post->url, 'rel' => 'nofollow']) ?>
                            <?= Html::img($post->thumbUrl, ['class' => 'img-responsive', 'alt' => $post->image_alt]) ?>
                            <?= Html::endTag('a') ?>
                            </noindex>
                        </div>
                    <?php endif; ?>
                    <!-- Title -->
                    <h3><?= Html::a($post->title, $post->url) ?></h3>
                    <!-- Meta Date -->
                    <span class="date">
                        <i class="fa fa-eye"></i> <?= $post->view_count ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>