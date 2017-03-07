<?php
/**
 * @author Ian Kuznetsov <yankuznecov@ya.ru>
 * Date: 07.03.2017
 * Time: 19:09
 */

namespace yanpapayan\blockchain\actions;

use dosamigos\qrcode\formats\Bitcoin;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use yii\base\Action;

class QrCodeAction extends Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @param $address
     * @param $amount
     */
    public function run($address, $amount)
    {
        $format = new Bitcoin(['address' => $address, 'amount' => $amount]);
        return QrCode::png($format->getText(), false, Enum::QR_ECLEVEL_L, 6);
    }
}