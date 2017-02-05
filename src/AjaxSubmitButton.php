<?php

namespace davidjeddy\poll;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * AjaxSubmitButton renders an ajax button which is very similar to ajaxSubmitButton from \Yii1.
 *
 * Example:
 *
 * ```php
 * <?= Html::beginForm(); ?>
 *
 * <?php
 * AjaxSubmitButton::begin([
 *     'label'       =>'Poll 1',
 *     'ajaxOptions' =>
 *        [
 *            'cache'   => false,
 *            'success' => new \yii\web\JsExpression('function(html){ $("#output").html(html); }'),
 *            'type'    =>'POST',
 *            'url'     =>'country/getinfo',
 *        ],
 *     'options' => ['type' => 'submit'],
 * ]);
 * AjaxSubmitButton::end();
 * ?>
 *
 * <?= Html::endForm(); ?>
 * ```
 *
 * @author Oleg Martemjanov <demogorgorn@gmail.com>
 * @author David J Eddy <me@davidjeddy.com>
 */
class AjaxSubmitButton extends Widget
{
    /**
     * @var array
     */
    public $ajaxOptions = [];

    /**
     * @var array
     */
    public $ajaxOverride = [];

    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

    /**
     * @var string the tag to use to render the button
     */
    public $tagName = 'button';

    /**
     * @var string the button label
     */
    public $label = 'Button';

    /**
     * @var boolean whether the label should be HTML-encoded.
     */
    public $encodeLabel = true;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        if (!isset($this->options['id'])) {

            $this->options['id'] = $this->getId();
        }
    }

    /**
     *
     */
    public function run()
    {
        parent::run();

        echo Html::tag($this->tagName, $this->encodeLabel ? Html::encode($this->label) : $this->label, $this->options);

        if (!empty($this->ajaxOptions)) {

            $this->registerAjaxScript();
        }
    }

    /**
     *
     */
    protected function registerAjaxScript()
    {
        $view = $this->getView();

        if (!isset($this->ajaxOptions['type'])) {

            $this->ajaxOptions['type'] = new \yii\web\JsExpression('$(this).parents("form").attr("method")');
        }

        if (!isset($this->ajaxOptions['url'])) {

            $this->ajaxOptions['url'] = new \yii\web\JsExpression('$(this).parents("form").attr("action")');
        }

        if (!isset($this->ajaxOptions['data']) && isset($this->ajaxOptions['type'])) {
            $this->ajaxOptions['data'] = new \yii\web\JsExpression('$(this).parents("form").serialize()');
            $this->ajaxOptions = Json::encode($this->ajaxOptions);

            $view->registerJs("$( '#" . $this->options['id'] . "' ).click(function() {
                    $.ajax(" . $this->ajaxOptions . ");
                    return false;
                });"
            );
        }
    }
}
