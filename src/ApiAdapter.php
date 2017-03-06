<?php
/**
 * @author Ian Kuznetsov <yankuznecov@ya.ru>
 * Date: 06.03.2017
 * Time: 18:51
 */

namespace yanpapayan\blockchain;

use Blockchain\Blockchain;
use yanpapayan\blockchain\events\GatewayEvent;
use yii\base\Component;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

class ApiAdapter extends Component
{
    const LOG_CATEGORY = 'Blockchain';

    public $apiKey;
    public $xPub;
    public $callbackUrl;

    public $resultUrl;
    public $successUrl;
    public $failureUrl;

    /** @var  Blockchain */
    protected $api;

    /**
     * @inheritdoc
     */
    public function init()
    {
        assert(isset($this->apiKey));

        parent::init();

        $this->api = new Blockchain($this->apiKey);
    }

    /**
     * @param array $data
     * @return bool
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public function processResult($data)
    {
        if (!$this->checkHash($data)) {
            throw new ForbiddenHttpException('Hash error');
        }

        $event = new GatewayEvent(['gatewayData' => $data]);

        $this->trigger(GatewayEvent::EVENT_PAYMENT_REQUEST, $event);
        if (!$event->handled) {
            throw new HttpException(503, 'Error processing request');
        }

        $transaction = \Yii::$app->getDb()->beginTransaction();
        try {
            $this->trigger(GatewayEvent::EVENT_PAYMENT_SUCCESS, $event);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            \Yii::error('Payment processing error: ' . $e->getMessage(), static::LOG_CATEGORY);
            throw new HttpException(503, 'Error processing request');
        }

        return true;
    }

    /**
     * @param $invoiceId
     * @return string
     */
    public function generateSecretKey($invoiceId)
    {
        return md5($invoiceId . ':' . $this->apiKey);
    }

    public function checkHash($data)
    {
    }
}