<?php
use yii\helpers\Html;
?>

<?php if ($model): ?>

    <div class="topic-posts-btm">
        <div class="topic-posts-header">Материалы по теме</div>

        <div class="posts pblock">
            <div class="row">
                <?php foreach ($model as $post): ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="entry">
                        <?php if ($post->image): ?>
                            <div class="bthumb2">
                               <a href="<?= $post->url ?>" rel="bookmark"><img src="<?= $post->getThumbUrl(287, 197) ?>" alt=""/></a>
                            </div>
                        <?php endif; ?>
                            <div class="h2"><a href="<?= $post->url ?>" rel="bookmark"><?= $post->title ?></a></div>
                            <div class="meta">
                                <i class="fa fa-user"></i> <?= $post->user->username ?>
                                <span class="pull-right"><i class="fa fa-eye"></i> <?= $post->view_count ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

<?php endif; ?>