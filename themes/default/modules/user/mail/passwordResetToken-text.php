<?php

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/password-reset', 'token' => $user->password_reset_token]);
?>
Здравствуйте, <?= $user->username ?>!

Для сброса пароля пройдите по ссылке:

<?= $resetLink ?>
