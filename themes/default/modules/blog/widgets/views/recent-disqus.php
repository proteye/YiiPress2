<?php
use yii\helpers\Html;
?>
<?php if ($contents): ?>
    <div class="widget">
        <h4>Последние комментарии</h4>
        <ul>
            <?php foreach ($authors as $key => $author): ?>
                <li>
                    <!-- Avatar -->
                    <div class="bthumb5">
                        <?= Html::img($images[$key], ['class' => 'img-responsive', 'alt' => $author]) ?>
                    </div>
                    <!-- Content -->
                    <div class="comment-content">
                        <noindex>
                        <a href="<?= $links[$key] ?>" rel="nofollow"><strong><?= $author ?>:</strong> <?= $contents[$key] ?></a>
                        </noindex>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>