<?php
use yii\helpers\Html;
?>

<?php if ($model): ?>

    <div class="widget">
        <h4><?= $parent->name ?></h4>
        <ul>
            <?php foreach ($model as $category): ?>
                <li>
                    <noindex>
                    <?= Html::a($category->name, $category->url, ['rel' => 'nofollow']) ?>
                    </noindex>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>