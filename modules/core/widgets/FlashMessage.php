<?php
namespace app\modules\core\widgets;

class FlashMessage extends \yii\base\Widget
{
    const SUCCESS_MESSAGE = 'success';
    const INFO_MESSAGE = 'info';
    const WARNING_MESSAGE = 'warning';
    const ERROR_MESSAGE = 'danger';

    public $type = self::INFO_MESSAGE;
    public $message = '';
    public $options = [];

    public function run()
    {
        if ($this->message == '' || $this->message == null)
            return false;
        if (!isset($this->options['class']))
            $this->options['class'] = 'alert-' . $this->type;

        return $this->render('flash-message', ['message' => $this->message, 'options' => $this->options]);
    }
}