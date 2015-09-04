<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use dosamigos\disqus\Comments;
use app\modules\blog\widgets\RecentPost;
use app\modules\blog\widgets\RecentDisqus;
use app\modules\blog\widgets\TopicPost;
use app\modules\blog\widgets\TopicCategory;
use app\modules\blog\widgets\TagCloud;
use app\modules\category\models\Category;
use app\modules\core\widgets\SearchPost;
use app\modules\core\widgets\VkGroup;

/**
 * @var yii\web\View $this
 */
$this->title = $model->meta_title ? $model->meta_title : $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description], 'meta-description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords], 'meta-keywords');

/*** Meta for Social share links ***/
// Google+
$this->registerMetaTag(['itemprop' => 'name', 'content' => $this->title]);
$this->registerMetaTag(['itemprop' => 'description', 'content' => strip_tags($model->quote)]);
$this->registerMetaTag(['itemprop' => 'image', 'content' => $model->image ? Yii::$app->request->hostInfo . $model->getThumbUrl(640, 480) : '']);
// Twitter
$this->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary']);
$this->registerMetaTag(['name' => 'twitter:site', 'content' => Yii::$app->name]);
$this->registerMetaTag(['name' => 'twitter:title', 'content' => $this->title]);
$this->registerMetaTag(['name' => 'twitter:description', 'content' => strip_tags($model->quote)]);
$this->registerMetaTag(['name' => 'twitter:creator', 'content' => Yii::$app->name]);
$this->registerMetaTag(['name' => 'twitter:image:src', 'content' => $model->image ? Yii::$app->request->hostInfo . $model->getThumbUrl(640, 480) : '']);
$this->registerMetaTag(['name' => 'twitter:domain', 'content' => Yii::$app->request->serverName]);
// Facebook
$this->registerMetaTag(['property' => 'og:type', 'content' => 'article']);
$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
$this->registerMetaTag(['property' => 'og:description', 'content' => strip_tags($model->quote)]);
$this->registerMetaTag(['property' => 'og:image', 'content' => $model->image ? Yii::$app->request->hostInfo . $model->getThumbUrl(640, 480) : '']);
$this->registerMetaTag(['property' => 'og:url', 'content' => Yii::$app->request->hostInfo . Yii::$app->request->url]);
$this->registerMetaTag(['property' => 'og:site_name', 'content' => Yii::$app->name]);

// Sticky sidebar
$script = <<<JS
    if (getWindowSize() > 1000) {
        $('#blog-content').stickyColumn({
            'column': '.sidebar',
            'content': '.content-main'
        });
    }
JS;
$this->registerJs($script);

// Breadcrumbs
foreach (Category::getBreadcrumbs($model->category->id) as $breadcrumb)
    $this->params['breadcrumbs'][] = ['label' => $breadcrumb['label'], 'url' => $breadcrumb['url']];

// Advert random
$advert = rand(0, 1);
?>

<!-- Page heading starts -->

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="h2"><?= $model->category->name ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Page Heading ends -->

<!-- Page content starts -->

<div class="content blog">
    <div class="container" id="blog-content">
        <div class="row">
            <div class="col-md-8">
                <div class="content-main">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <div class="posts">
                    <!-- Post -->
                    <article class="entry">

                        <h1><?= $model->title ?></h1>

                        <!-- Meta details -->
                        <div class="meta">
                            <i class="fa fa-user"></i> <?= $model->user->userProfile->nick_nm ?>
                            <i class="fa fa-folder-open"></i> <?= $model->category->name ?>
                            <span class="pull-right">
                                <i class="fa fa-eye"></i> <?= $model->view_count - 1 ?>
                                <?php /*
                                <i class="fa fa-comment"></i>
                                <?= Html::a('Комментировать', $model->url . '#disqus_thread', ['data-disqus-identifier' => $model->id]) ?>
                                <a href="#comment"><?= $model->comment_count ?>
                                    Комментари<?= ($model->comment_count == 1) ? 'й' : (($model->comment_count >= 2 && $model->comment_count <= 4) ? 'я' : 'ев') ?>
                                </a>
                                */ ?>
                            </span>
                        </div>

                        <?php if ($model->image): ?>
                            <!-- Thumbnail -->
                            <div class="bthumb">
                                <?= Html::img($model->getThumbUrl(620, 310), ['class' => 'img-responsive', 'alt' => $model->image_alt]) ?>
                            </div>
                        <?php endif; ?>


                        <?= Yii::$app->params['adsenseScripts']['top'] ?>
                        <?= Yii::$app->params['adsenseScripts']['mobile'] ?>

                        <!-- Content -->
                        <?= $model->contentWithAdsense ?>
                    </article>
                    <!-- /Post -->
                    
                    <?= Yii::$app->params['adsenseScripts']['bottom'] ?>
                    <?= Yii::$app->params['adsenseScripts']['mobile'] ?>
                    
                    <!-- M1 -->
                    <noindex>
                    <div id="cpa-article-btm" style="margin-top: 10px;">
                        <a rel="nofollow" target="_blank" href="http://sprei.best-gooods.ru/?ref=15836&lnk=49167">
                            <img class="img-responsive" alt="Спрей для роста волос" src="/uploads/ad/sprej_ultra_hair_620.jpg">
                        </a>
                    </div>
                    </noindex>
                    <!-- /M1 -->

                    <div class="well">
                        <!-- Social media icons -->
                        <div class="social pull-left">
                            <h5>Поделиться <i class="fa fa-share-square-o"></i></h5>
                            <a href="http://www.facebook.com/sharer.php?u=<?= Yii::$app->request->hostInfo . Yii::$app->request->url ?>" rel="external" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                            <a href="http://twitter.com/home?status=<?= $this->title ?> <?= Yii::$app->request->hostInfo . Yii::$app->request->url ?>" rel="external" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                            <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?= Yii::$app->request->hostInfo . Yii::$app->request->url ?>&title=<?= $this->title ?>" rel="external" target="_blank"><i class="fa fa-linkedin linkedin"></i></a>
                            <a href="https://plusone.google.com/_/+1/confirm?hl=ru&url=<?= Yii::$app->request->hostInfo . Yii::$app->request->url ?>&name=<?= $this->title ?>" rel="external" target="_blank"><i class="fa fa-google-plus google-plus"></i></a>
                        </div>
                        <!-- Tags -->
                        <div class="tags pull-right">
                            <h5>Теги <i class="fa fa-tags"></i></h5>
                            <?php foreach($model->postTags as $tag): ?>
                                <a href="<?= $tag->url ?>"><?= $tag->title ?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <!-- Topic Posts widget - bottom-->
                    <?= TopicPost::widget([
                        'post_id' => $model->id,
                        'category_id' => $model->category->id,
                        'template' => 'bottom',
                    ]) ?>

                    <hr />

                    <!-- Comment section -->
                    <?php /* Comments::widget([
                        'shortname' => 'landscapeportal',
                        'identifier' => $model->id,
                        'title' => $model->title,
                        'url' => Yii::$app->request->hostInfo . $model->url,
                    ]) */ ?>

                    <div class="clearfix"></div>
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Topic Category widget -->
                    <?= TopicCategory::widget([
                        'category_id' => $model->category->id,
                    ]) ?>

                    <!-- Search Post -->
                    <?= SearchPost::widget() ?>
                    
                    <!-- Vk.com Group widget -->
                    <?= VkGroup::widget() ?>

                    <noindex>
                        <div class="advert" id="cpa-sidebar-top">
                            <a rel="nofollow" target="_blank" href="http://sad.best-gooods.ru/?ref=15836&lnk=57538">
                                <img class="img-responsive" alt="Форма для изготовления садовой дорожки" src="/uploads/ad/sad_dorozhka_260.jpg">
                            </a>
                        </div>
                    </noindex>
                    
                    <?php /*
                    <noindex>
                        <div class="advert" id="cpa-sidebar-top">
                            <!-- admitad.banner: 80da618be04a1b603bc1e617d5ef08 Travelata.ru -->
                            <script type="text/javascript">
                                try{(function(d,ad,s,ulp,subID,injectTo){

                                    // Optional settings (these lines can be removed): 
                                    ulp = "";  // - custom goto link;
                                    subID = "lp-art-rgtbar-top";  // - local banner key;
                                    injectTo = "";  // - #id of html element (ex., "top-banner").

                                    var dInject="admitad"+ad+subID+Math.round(Math.random()*100000000);
                                    injectTo=="" && d.write('<div id="'+dInject+'"></div>');
                                    s=s.replace("$",ad);s+="?inject="+(injectTo==""||!injectTo?dInject:injectTo);
                                    if(subID!="")s+="&subid="+subID;if(ulp!="")s+="&ulp="+escape(encodeURI(ulp));
                                    s="https://"+s;var j=d.createElement("script");
                                    j.type="text/javascript";j.src=s;(d.getElementsByTagName("head")[0]).appendChild(j);
                                })(window.document,"80da618be04a1b603bc1e617d5ef08","ad.admitad.com/j/$/","","","");}catch(err){}
                            </script>
                            <noscript>
                                <embed wmode="opaque" width="240" height="400" src="https://ad.admitad.com/f/80da618be04a1b603bc1e617d5ef08/" type="application/x-shockwave-flash">
                                    <noembed>
                                        <a target="_blank" rel="nofollow" href="https://ad.admitad.com/g/80da618be04a1b603bc1e617d5ef08/?i=4&subid=lp-art-rgtbar-top">
                                            <img width="240" height="400" border="0" src="https://ad.admitad.com/b/80da618be04a1b603bc1e617d5ef08/" alt="Travelata.ru"/>
                                        </a>
                                    </noembed>
                            </noscript>
                            <!-- /admitad.banner -->
                        </div>
                    </noindex>
                    */ ?>

                    <?php /*
                    <!-- Topic Posts widget - right-->
                    <?= TopicPost::widget([
                        'post_id' => $model->id,
                        'category_id' => $model->category->id,
                        'template' => 'right',
                        'limit' => 10,
                    ]) ?>

                    <!-- Recent Posts widget -->
                    <?= RecentPost::widget([
                        'post_id' => $model->id,
                    ]) ?>
                    */ ?>

                    <!-- Recent Comments widget -->
                    <?php // RecentDisqus::widget() ?>

                    <!-- Tag cloud widget -->
                    <?= TagCloud::widget() ?>

                    <noindex>
                        <div class="advert" id="cpa-sidebar-btm">
                            <a rel="nofollow" target="_blank" href="http://hairboroda.best-gooods.ru/?ref=15836&lnk=63898">
                                <img class="img-responsive" alt="Спрей для роста бороды Professional Hair System" src="/uploads/ad/boroda_260.jpg">
                            </a>
                        <?php /* if ($advert): ?>
                            <!-- admitad.banner: a918f8256c4a1b603bc193a20ab278 Дочки & Сыночки -->
                            <a target="_blank" rel="nofollow" href="https://ad.admitad.com/g/a918f8256c4a1b603bc193a20ab278/?i=4&subid=lp-art-rgtbar-btm">
                                <img width="240" height="400" border="0" src="https://ad.admitad.com/b/a918f8256c4a1b603bc193a20ab278/" alt="Дочки &amp; Сыночки"/>
                            </a>
                            <!-- /admitad.banner -->
                        <?php else: ?>
                            <!-- admitad.banner: 48e5cbc4474a1b603bc193a20ab278 Дочки & Сыночки -->
                            <a target="_blank" rel="nofollow" href="https://ad.admitad.com/g/48e5cbc4474a1b603bc193a20ab278/?i=4&subid=lp-art-rgtbar-btm">
                                <img width="240" height="400" border="0" src="https://ad.admitad.com/b/48e5cbc4474a1b603bc193a20ab278/" alt="Дочки &amp; Сыночки"/>
                            </a>
                            <!-- /admitad.banner -->
                        <?php endif; ?>
                            <script type='text/javascript'>(function() {
                                    /* Optional settings (these lines can be removed): 
                                    subID = "lp-art-rgtbar-btm";  // - local banner key;
                                    injectTo = "";  // - #id of html element (ex., "top-banner").
                                    /* End settings block 

                                    if(injectTo=="")injectTo="admitad_shuffle"+subID+Math.round(Math.random()*100000000);
                                    if(subID=='')subid_block=''; else subid_block='subid/'+subID+'/';
                                    document.write('<div id="'+injectTo+'"></div>');
                                    var s = document.createElement('script');
                                    s.type = 'text/javascript'; s.async = true;
                                    s.src = 'https://ad.admitad.com/shuffle/ab07b46228/'+subid_block+'?inject_to='+injectTo;
                                    var x = document.getElementsByTagName('script')[0];
                                    x.parentNode.insertBefore(s, x);
                                })();
                            </script>
                            <?php */ ?>
                        </div>
                    </noindex>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content ends -->