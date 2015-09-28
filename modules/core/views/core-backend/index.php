<?php
/* @var $this yii\web\View */
?>
<h1>Последние события</h1>

<?php if ($log_arr): ?>
<h2>Импорт купонов</h2>
<p>
    Дата: <?= $log_arr[3] ?><br/>
    <?= $log_arr[2] ?><br/>
    <?= $log_arr[1] ?><br/>
    <?= $log_arr[0] ?><br/>
</p>
<?php endif; ?>