<?php

namespace yanpapayan\blockchain\events;

use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * Class GatewayEvent
 * @package yanpapayan\blockchain\events
 * @author Ian Kuznetsov <yankuznecov@ya.ru>
 */
class GatewayEvent extends Event
{
    const EVENT_PAYMENT_REQUEST = 'eventPaymentRequest';
    const EVENT_PAYMENT_SUCCESS = 'eventPaymentSuccess';

    /** @var ActiveRecord|null */
    public $invoice;

    /** @var array */
    public $gatewayData = [];
}