<?php
namespace app\modules\menu\widgets;

use Yii;
use yii\base\Widget;
use app\modules\menu\models\Menu;

class NavMenu extends Widget
{
    public $menu_id;

    public function run()
    {
        if ($this->menu_id === null)
            return false;

        $items = Menu::getItems($this->menu_id);
        return $this->render('nav-menu', [
            'items' => $items,
            'cacheId' => $this->menu_id,
        ]);
    }
}