<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => [$model, $profile],
        'attributes' => [
            [
                'label' => $model->getAttributeLabel('id'),
                'value' => $model->id,
            ],
            [
                'label' => $model->getAttributeLabel('username'),
                'value' => $model->username,
            ],
            [
                'label' => $model->getAttributeLabel('email'),
                'value' => $model->email,
                'format' => 'email',
            ],
            [
                'label' => $profile->getAttributeLabel('nick_nm'),
                'value' => $profile->nick_nm,
            ],
            [
                'label' => $profile->getAttributeLabel('first_nm'),
                'value' => $profile->first_nm,
            ],
            [
                'label' => $profile->getAttributeLabel('last_nm'),
                'value' => $profile->last_nm,
            ],
            [
                'label' => $profile->getAttributeLabel('patron'),
                'value' => $profile->patron,
            ],
            [
                'label' => $profile->getAttributeLabel('birth_dt'),
                'value' => $profile->birth_dt,
                'format' => 'date',
            ],
            [
                'label' => $profile->getAttributeLabel('about'),
                'value' => $profile->about,
            ],
            [
                'label' => $profile->getAttributeLabel('site'),
                'value' => $profile->site,
            ],
            [
                'label' => $profile->getAttributeLabel('address'),
                'value' => $profile->address,
            ],
            [
                'label' => $profile->getAttributeLabel('avatar'),
                'value' => $profile->avatar,
            ],
            [
                'label' => $profile->getAttributeLabel('last_visit'),
                'value' => $profile->last_visit,
                'format' => 'date',
            ],
            [
                'label' => $profile->getAttributeLabel('user_ip'),
                'value' => $profile->user_ip,
            ],
            [
                'label' => $model->getAttributeLabel('auth_key'),
                'value' => $model->auth_key,
            ],
            [
                'label' => $model->getAttributeLabel('created_at'),
                'value' => $model->created_at,
                'format' => 'date',
            ],
            [
                'label' => $model->getAttributeLabel('updated_at'),
                'value' => $model->updated_at,
                'format' => 'date',
            ],
            [
                'label' => $profile->getAttributeLabel('email_confirm'),
                'value' => $profile->email_confirm,
            ],
            [
                'label' => $model->getAttributeLabel('status'),
                'value' => $model->status,
            ],
            // 'password_hash',
            // 'password_reset_token',
        ],
    ]) ?>

</div>
