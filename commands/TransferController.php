<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\db\Query;
use app\modules\category\models\Category;

class TransferController extends Controller
{
    public function actionIndex()
    {
        echo 'yii transfer/all' . PHP_EOL;
        echo 'yii transfer/category' . PHP_EOL;
        echo 'yii transfer/post' . PHP_EOL;
        echo 'yii transfer/tag' . PHP_EOL;
        echo 'yii transfer/image' . PHP_EOL;
    }

    public function actionAll()
    {
        $this->actionCategory();
        $this->actionPost();
        $this->actionTag();
        $this->actionImage();
    }

    public function actionCategory()
    {
        $query = new Query;
        $rows = $query->select('*')
            ->from('yp_category_copy')
            ->all()
        ;
        foreach ($rows as $row) {
            $model = new Category();
            $model->id = $row['id'];
            $model->parent_id = ($row['parent_id'] == 0) ? null : $row['parent_id'];
            $model->lang = 'ru';
            $model->slug = $row['url'];
            $model->name = $row['title'];
            $model->short_description = null;
            $model->description = $row['content'];
            $model->image = null;
            $model->image_alt = null;
            $model->created_at = $row['created_at'];
            $model->updated_at = $row['updated_at'];
            $model->meta_title = $row['meta_title'];
            $model->meta_keywords = $row['meta_keywords'];
            $model->meta_description = $row['meta_description'];
            $model->status = $row['status'];
            $this->log($model->save());
        }
    }

    public function actionPost()
    {
        $query = new Query;
        $rows = $query->select('p.*, pm.meta_value views_count, img.path image, img.alt image_alt')
            ->from('yp_post_copy p')
            ->leftJoin('yp_post_meta_copy pm', 'pm.post_id = p.id AND pm.meta_key = "views_count"')
            ->leftJoin('yp_post_meta_copy pmc', 'pmc.post_id = p.id AND pmc.meta_key = "main_image_id"')
            ->leftJoin('yp_image_copy img', 'img.id = pmc.meta_value')
            ->where('p.url != "index"')
            ->all()
        ;
        foreach ($rows as $row) {
            $result = Yii::$app->db->createCommand()->insert('yp_post', [
                // 'id' => $row['id'],
                'category_id' => ($row['category_id'] == 0) ? null : $row['category_id'],
                'lang' => 'ru',
                'slug' => $row['url'],
                'title' => $row['title'],
                'quote' => $row['announce'],
                'content' => $row['content'],
                'image' => $row['image'],
                'image_alt' => $row['image_alt'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'published_at' => $row['updated_at'],
                'created_by' => 1,
                'updated_by' => 1,
                'user_ip' => '192.168.10.4',
                'link' => null,
                'meta_title' => $row['meta_title'],
                'meta_keywords' => $row['meta_keywords'],
                'meta_description' => $row['meta_description'],
                'access_type' => 1,
                'comment_status' => 1,
                'status' => $row['status'],
                'views_count' => $row['views_count'],
            ])->execute()
            ;
            $this->log($result);
        }
    }

    public function actionImage()
    {
        $query = new Query;
        $rows = $query->select('*')
            ->from('yp_image_copy')
            ->where('description = ""')
            ->all()
        ;
        foreach ($rows as $row) {
            $result = Yii::$app->db->createCommand()->insert('yp_image', [
                // 'id' => $row['id'],
                'name' => 'from_widget',
                'description' => 'From Imperavi or CKEditor widget.',
                'file' => $row['path'],
                'user_id' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ])->execute()
            ;
            $this->log($result);
        }
    }

    public function actionTag()
    {
        /* Tag */
        $query = new Query;
        $rows = $query->select('*')
            ->from('yp_term_copy')
            ->all()
        ;
        foreach ($rows as $row) {
            $result = Yii::$app->db->createCommand()->insert('yp_tag', [
                // 'id' => $row['id'],
                'title' => $row['name'],
                'slug' => $row['url'],
            ])->execute()
            ;
            $this->log($result);
        }

        /* Post_Tag */
        $query = new Query;
        $rows = $query->select('post_id, term_taxonomy_id tag_id')
            ->from('yp_post_taxonomy_copy')
            ->all()
        ;
        foreach ($rows as $row) {
            $result = Yii::$app->db->createCommand()->insert('yp_post_tag', [
                'post_id' => $row['post_id'] - 1,
                'tag_id' => $row['tag_id'],
            ])->execute()
            ;
            $this->log($result);
        }
    }

    /**
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }
}
