<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['email-confirm', 'token' => $user->email_confirm_token]);
?>

Здравствуйте, <?= $user->username ?>!

Для подтверждения адреса пройдите по ссылке:

<?= $confirmLink ?>

Если Вы не регистрировались на нашем сайте, то просто удалите это письмо.