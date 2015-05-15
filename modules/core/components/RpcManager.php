<?php

namespace app\modules\core\components;

use Yii;
use yii\base\Component;

require_once Yii::getAlias('@app') . '/modules/core/extensions/IXR_Library/IXR_Library.php';

/**
 * XML RPC class for YiiPress
 */
class RpcManager extends Component
{
    public $pingEnable = true;
    public $pingServers = [];

    public function pingPage($pageName, $pageURL)
    {
        $siteName = $pageName . ' | ' . Yii::$app->name;
        $siteHost = Yii::$app->request->hostInfo;
        $fullPageUrl = $siteHost . $pageURL;

        if ($this->pingEnable) {
            if (!$pageURL)
                return;

            foreach ($this->pingServers as $serverUrl)
            {
                $client = new \IXR_Client($serverUrl);
                if (!$client->query('weblogUpdates.ping', $siteName, $fullPageUrl))
                    Yii::error('Ping error for ' . $serverUrl);
            }
        } else {
            Yii::info('Emulation of ping for ' . $fullPageUrl);
        }
    }
}