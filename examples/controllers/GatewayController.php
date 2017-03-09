<?php

namespace yanpapayan\blockchain\examples;

use common\models\Invoice;
use yanpapayan\blockchain\ApiAdapter;
use yanpapayan\blockchain\actions\ResultAction;
use yanpapayan\blockchain\events\GatewayEvent;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;

class GatewayController extends Controller
{
    /** @inheritdoc */
    public $enableCsrfValidation = false;

    /** @var string Your component configuration name */
    public $componentName = 'blockchain';

    /** @var ApiAdapter */
    protected $component;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->component = \Yii::$app->get($this->componentName);

        $this->component->on(GatewayEvent::EVENT_PAYMENT_REQUEST, [$this, 'handlePaymentRequest']);
        $this->component->on(GatewayEvent::EVENT_PAYMENT_SUCCESS, [$this, 'handlePaymentSuccess']);
    }

    public function actions()
    {
        return [
            'result' => [
                'class' => ResultAction::className(),
                'componentName' => $this->componentName,
                'redirectUrl' => ['/billing'],
            ],
            'success' => [
                'class' => ResultAction::className(),
                'componentName' => $this->componentName,
                'redirectUrl' => ['/billing'],
                'silent' => true,
            ],
            'failure' => [
                'class' => ResultAction::className(),
                'componentName' => $this->componentName,
                'redirectUrl' => ['/billing'],
                'silent' => true,
            ]
        ];
    }

    /**
     * @param GatewayEvent $event
     * @return bool
     */
    public function handlePaymentRequest($event)
    {
        $invoice = Invoice::findOne(ArrayHelper::getValue($event->gatewayData, 'invoice_id'));
        $secret = ArrayHelper::getValue($event->gatewayData, 'secret'); // random key
        $transactionHash = ArrayHelper::getValue($event->gatewayData, 'transaction_hash'); // unique key
        $address = ArrayHelper::getValue($event->gatewayData, 'address'); // receiving address
        $confirmations = ArrayHelper::getValue($event->gatewayData, 'confirmations'); // count confirmations
        $value = ArrayHelper::getValue($event->gatewayData, 'value'); // in satoshi
        $cost = $value * 0.00000001; // convert in BTC

        if (!$invoice instanceof Invoice ||
            $invoice->status != Invoice::STATUS_NEW ||
            $this->component->secret !== $secret ||
            bccomp($cost, $invoice->amount, 8) === -1 ||
            $confirmations < ApiAdapter::COUNT_CONFIRMATIONS_SUCCESS
        ) {
            return;
        }

        $invoice->debugData = VarDumper::dumpAsString($event->gatewayData);
        $event->invoice = $invoice;
        $event->handled = true;
    }

    /**
     * @param GatewayEvent $event
     * @return bool
     */
    public function handlePaymentSuccess($event)
    {
        /** @var Invoice $invoice */
        $invoice = $event->invoice;

        // TODO: invoice processing goes here
    }
}
