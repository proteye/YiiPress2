<?php
/* @var $this yii\web\View */
?>
<h1>Последние события</h1>

<?php if ($log_admitad): ?>
<h2>Импорт купонов Admitad</h2>
<p>
    Дата: <?= $log_admitad[4] ?><br/>
    <?= $log_admitad[3] ?><br/>
    <?= $log_admitad[2] ?><br/>
    <?= $log_admitad[1] ?><br/>
    <?= $log_admitad[0] ?><br/>
</p>
<?php endif; ?>

<?php if ($log_actionpay): ?>
    <h2>Импорт купонов ActionPay</h2>
    <p>
        Дата: <?= $log_actionpay[4] ?><br/>
        <?= $log_actionpay[3] ?><br/>
        <?= $log_actionpay[2] ?><br/>
        <?= $log_actionpay[1] ?><br/>
        <?= $log_actionpay[0] ?><br/>
    </p>
<?php endif; ?>
