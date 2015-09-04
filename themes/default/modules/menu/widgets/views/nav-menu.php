<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;

NavBar::begin([
    'options' => [
        'class' => 'bs-docs-nav'
    ],
    'innerContainerOptions' => [
        'class' => '',
    ],
]);
if ($this->beginCache($cacheId, ['duration' => Yii::$app->getModule('core')->cacheTime]))
{
    echo Nav::widget([
        'options' => [
            'class' => 'navbar-nav navbar-right bs-navbar-collapse',
        ],
        'items' => $items,
    ]);
    $this->endCache();
}
NavBar::end();
