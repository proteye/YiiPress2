<?php
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $items[] */
?>
<noindex>
<div class="ui left vertical inverted sidebar menu">
    <a href="/" rel="nofollow" class="item <?= (Url::to() == '/') ? 'active' : '' ?>">Главная</a>
    <?php foreach ($items as $item) {
        $active = (Url::to() == $item['url']) ? 'active' : '';
        echo "<a href=\"{$item['url']}\" rel=\"nofollow\" class=\"item $active\">{$item['label']}</a>";
    }
    ?>
</div>
</noindex>