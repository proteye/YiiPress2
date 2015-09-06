<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $logo_url string */
/* @var $logo_alt string */
/* @var $items[] */
?>
<div class="ui menu large borderless blue inverted">
    <div class="ui container">
        <?php if (Url::to() != Yii::$app->homeUrl) {
            echo '<a href="/" rel="nofollow" class="header item">';
        } else {
            echo '<div class="header item">';
        } ?>
        <?= Html::img($logo_url, ['alt' => $logo_alt]) ?>
        <?php if (Url::to() != Yii::$app->homeUrl) {
            echo '</a>';
        } else {
            echo '</div>';
        } ?>
        <?php foreach ($items as $item) {
            $active = (Url::to() == $item['url']) ? 'active' : '';
            if ($item['items'] == null) {
                echo "<a href=\"{$item['url']}\" class=\"item $active\">{$item['label']}</a>";
            } else {
                echo "<div class=\"ui simple dropdown item hover\">";
                echo "<a href=\"{$item['url']}\">{$item['label']}</a> <i class=\"dropdown icon\"></i>";
                echo '<div class="menu">';
                foreach ($item['items'] as $item2) {
                    $active2 = (Url::to() == $item2['url']) ? 'active' : '';
                    echo "<a href=\"{$item2['url']}\" class=\"item $active2\">{$item2['label']}</a>";
                }
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
