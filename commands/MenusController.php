<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class MenusController
 *
 * @package app\commands
 */
class MenusController extends Controller
{
    /**
     * Commands list
     */
    public function actionIndex()
    {
        echo 'yii menus/from-category' . PHP_EOL;
        echo 'yii menus/nofollow-add' . PHP_EOL;
        echo 'yii menus/nofollow-del' . PHP_EOL;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionFromCategory()
    {
        $menu_id = (int)$this->prompt('Menu ID:', ['required' => true]);
        $regular_link = 1;

        $categories = Yii::$app->db->createCommand(
            'select c1.id, c1.parent_id, c1.name, c1.slug, group_concat(c2.slug, \'/\', c1.slug) href
            from {{%category}} c1
            left join {{%category}} c2 on c2.id = c1.parent_id
            group by c1.id'
        )->queryAll();

        foreach ($categories as $category) {
            $result = Yii::$app->db->createCommand()->insert('{{%menu_item}}', [
                'menu_id' => $menu_id,
                'parent_id' => ($category['parent_id'] == 0) ? null : $category['parent_id'],
                'regular_link' => $regular_link,
                'title' => $category['name'],
                'href' => $category['href'] ? $category['href'] : $category['slug'],
                'status' => 0,
            ])->execute()
            ;
            $this->log($result);
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionNofollowAdd()
    {
        $result = Yii::$app->db->createCommand()->update('{{%menu_item}}', ['rel' => 'nofollow'])->execute();
        $this->log($result);
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionNofollowDel()
    {
        $result = Yii::$app->db->createCommand()->update('{{%menu_item}}', ['rel' => null])->execute();
        $this->log($result);
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
