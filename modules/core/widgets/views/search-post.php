<?php
use yii\helpers\Url;
?>
<noindex>
    <div class="widget">
        <div class="h4">Поиск по сайту</div>
        <form action="<?= Url::toRoute(['/core/core-frontend/search']) ?>" method="get" class="form-inline" role="form">
            <div class="form-group">
                <input name="q" value="<?= $query ?>" type="text" class="form-control" id="search" placeholder="Введите текст поиска...">
            </div>
            <button type="submit" class="btn btn-default">Поиск</button>
        </form>
    </div>
</noindex>