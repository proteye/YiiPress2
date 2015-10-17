<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\BackendAsset;
use app\modules\core\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

BackendAsset::register($this);
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
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Главная', 'url' => ['/core/core-backend']],
                    [
                        'label' => 'Структура',
                        'items' => [
                            ['label' => 'Категории', 'url' => ['/category/category-backend']],
                            ['label' => 'Страницы', 'url' => ['/page/page-backend']],
                        ],
                    ],
                    [
                        'label' => 'Блог',
                        'items' => [
                            ['label' => 'Посты', 'url' => ['/blog/post-backend']],
                            ['label' => 'Тэги', 'url' => ['/blog/tag-backend']],
                        ],
                    ],
                    [
                        'label' => 'Меню',
                        'items' => [
                            ['label' => 'Меню', 'url' => ['/menu/menu-backend']],
                            ['label' => 'Пункты', 'url' => ['/menu/menu-item-backend']],
                        ],
                    ],
                    [
                        'label' => 'Модули',
                        'items' => [
                            ['label' => 'Комментарии', 'url' => ['/comment/comment-backend']],
                            ['label' => 'Изображения', 'url' => ['/image/image-backend']],
                        ],
                    ],
                    ['label' => 'Пользователи', 'url' => ['/user/user-backend']],
                    ['label' => 'Настройки', 'url' => ['/core/setting-backend']],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Войти', 'url' => ['/user/user-frontend/login']] :
                        ['label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/user/user-frontend/logout'],
                            'linkOptions' => ['data-method' => 'post']],
                ],
            ]);
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
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
