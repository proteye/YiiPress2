<?php
/* @var $this yii\web\View */
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= $host ?>/</loc>
        <lastmod><?= date(DATE_W3C, $last_date) ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <?php foreach ($items as $item): ?>
        <?php foreach ($item['model'] as $model): ?>
            <url>
                <loc><?= $host . $model->url ?></loc>
                <lastmod><?= date(DATE_W3C, isset($model->published_at) ? $model->published_at : $model->updated_at) ?></lastmod>
                <changefreq><?= $item['changefreq'] ?></changefreq>
                <priority><?= $item['priority'] ?></priority>
            </url>
        <?php endforeach; ?>
    <?php endforeach; ?>
</urlset>