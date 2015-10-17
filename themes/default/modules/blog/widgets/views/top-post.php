<?php
use yii\helpers\Html;
?>

<?php if ($model): ?>
<div class="widget">
    <div>Популярное TOP-10</div>
    <ul>
        <?php foreach ($model as $post): ?>
            <li>
                <!-- Title -->
                <div><?= Html::a($post->title, $post->url) ?></div>
                <?php if ($post->image): ?>
                    <!-- Thumbnail -->
                    <div>
                        <noindex>
                            <?= Html::beginTag('a', ['href' => $post->url, 'rel' => 'nofollow']) ?>
                            <?= Html::img($post->thumbUrl, ['class' => 'img-responsive', 'alt' => $post->image_alt]) ?>
                            <?= Html::endTag('a') ?>
                        </noindex>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>