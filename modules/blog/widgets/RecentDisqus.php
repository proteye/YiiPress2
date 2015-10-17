<?php
namespace app\modules\blog\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class RecentDisqus extends Widget
{
    public $num_items = 5;
    public $hide_avatars = 0;
    public $avatar_size = 32;
    public $excerpt_length = 80;

    public function run()
    {
        // Recent Comments
        $params = [
            'num_items' => $this->num_items,
            'hide_avatars' => $this->hide_avatars,
            'avatar_size' => $this->avatar_size,
            'excerpt_length' => $this->excerpt_length,
        ]
        ;
        // Get recent comments
        $data = file_get_contents("http://<disqus_name>.disqus.com/recent_comments_widget.js?num_items={$params['num_items']}&hide_avatars={$params['hide_avatars']}&avatar_size={$params['avatar_size']}&excerpt_length={$params['excerpt_length']}");

        // Images
        preg_match_all('/src=\"(.+?)\"/si', $data, $matches);
        $images = $matches[1];
        // Authors
        preg_match_all('/<img.*?">(<\/a>)?(.*?)<span/si', $data, $matches);
        $authors = $matches[2];
        foreach ($authors as $key => $author) {
            preg_match_all('/<a.*>(.+)<\/a>/i', $author, $matches);
            if (isset($matches[1][0])) {
                $authors[$key] = $matches[1][0];
            } else {
                $authors[$key] = preg_replace('/[\s\\\]+/i', '', Html::encode($author));
            }
        }
        // Contents
        preg_match_all('/<span class=\"dsq-widget-comment\">(.+?)<\/span>/si', $data, $matches);
        $contents = $matches[1];
        // Links
        preg_match_all('/&nbsp;<a href="(.+?#comment.+?)">/si', $data, $matches);
        $links = $matches[1];

        return $this->render('recent-disqus', ['images' => $images, 'authors' => $authors, 'contents' => $contents, 'links' => $links]);
    }
}