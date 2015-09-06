<?php
namespace app\modules\menu\widgets;

use Yii;
use yii\base\Widget;
use app\modules\menu\models\Menu;

class NavMenuSemanticUi extends Widget
{
    public $menu_id;
    public $logo_url;
    public $logo_alt;

    public function run()
    {
        if ($this->menu_id === null)
            return false;

        $items = Menu::getItems($this->menu_id);
        return $this->render('nav-menu-semantic-ui', [
            'items' => $items,
            'logo_url' => $this->logo_url,
            'logo_alt' => $this->logo_alt,
            'cacheId' => $this->menu_id,
        ]);
    }
}