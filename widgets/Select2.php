<?php
namespace app\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;
use app\widgets\assets\Select2Asset;

/**
 * Class Select2
 * @package app\widgets
 */
class Select2 extends InputWidget
{
    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var array
     */
    protected $defaultClientOptions = [
//        'theme' => 'bootstrap',
//        'width' => '100%',
//        'allowClear' => false,
//        'dropdownAutoWidth' => true,
    ];

    /**
     * @var array Select items
     */
    public $items = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();

        Select2Asset::register($this->view);

        $this->options['id'] = isset($this->options['id']) ? $this->options['id'] : $this->getId();

        $jsClientOptions = Json::encode(array_merge($this->defaultClientOptions, $this->clientOptions));
        $selector = '#' . $this->options['id'];
        $this->view->registerJs("jQuery('{$selector}').select2({$jsClientOptions});", View::POS_READY, get_called_class() . $this->options['id']);

        if ($this->hasModel()) {
            return Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
        } else {
            return Html::dropDownList($this->name, $this->value, $this->items, $this->options);
        }
    }

}