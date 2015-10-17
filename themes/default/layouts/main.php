<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\modules\menu\models\Menu;
use yii\helpers\ArrayHelper;
use app\modules\core\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            $menu_id = 'top-menu';
            $static = [
            ];
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
        if ($this->beginCache($menu_id, ['duration' => Yii::$app->getModule('core')->cacheTime])) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => ArrayHelper::merge(Menu::getItems($menu_id), $static),
            ]);
            $this->endCache();
        }
            NavBar::end();
        ?>
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Yii::$app->name ?> <?= date('Y') ?> <?= Html::a('Карта сайта', '/sitemap') ?></p>
            <p class="pull-right">Powered by <?= Html::a('YiiPress2', 'http://yiipress.ru', ['target' => 'blank']) ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
