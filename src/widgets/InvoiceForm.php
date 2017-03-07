<?php

namespace yanpapayan\blockchain\widgets;

use yanpapayan\blockchain\ApiAdapter;
use yii\bootstrap\Widget;
use yii\web\View;

/**
 * Class InvoiceForm
 * @package yanpapayan\blockchain\widgets
 * @author Ian Kuznetsov <yankuznecov@ya.ru>
 */
class InvoiceForm extends Widget
{
    public $viewFile = 'invoice';

    /** @var ApiAdapter */
    public $api;
    /** @var integer */
    public $invoiceId;
    /** @var float */
    public $amount;
    /** @var string */
    public $description;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        assert(isset($this->api));
        assert(isset($this->invoiceId));
        assert(isset($this->amount));
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        //$this->view->registerJs("$('#{$this->formId}').submit();", View::POS_READY);

        return $this->render($this->viewFile, [
            'api' => $this->api,
            'amount' => $this->amount,
        ]);
    }
}