<?php

namespace yanpapayan\blockchain\widgets;

use dosamigos\qrcode\formats\Bitcoin;
use dosamigos\qrcode\QrCode;
use yanpapayan\blockchain\ApiAdapter;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
    /** @var float */
    public $baseAmount;
    /** @var string */
    public $description;
    /** @var string */
    public $qrCodeAction;

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
        $this->view->registerJs(<<<'JS'
JS
            , View::POS_READY);

        $receivingAddress = $this->api->generateReceivingAddress(['invoice_id' => $this->invoiceId])['address'];
        $qrCodeUrl = Url::to(ArrayHelper::merge([$this->qrCodeAction], [
            'address' => $receivingAddress,
            'amount' => $this->amount
        ]));

        return $this->render($this->viewFile, [
            'api' => $this->api,
            'invoiceId' => $this->invoiceId,
            'amount' => $this->amount,
            'baseAmount' => $this->baseAmount,
            'description' => $this->description,
            'receivingAddress' => $receivingAddress,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }
}