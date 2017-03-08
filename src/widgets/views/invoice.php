<?php

use yii\helpers\Html;

/**
 * @author Yan Kuznecov
 *
 * @var \yii\web\View $this
 * @var \yanpapayan\blockchain\ApiAdapter $api
 * @var integer $invoiceId
 * @var float $amount
 * @var float $baseAmount
 * @var string $qrCodeUrl
 * @var string $description
 * @var string $receivingAddress
 */
?>

<div class="blockchain-invoice-example">
    <p><?= $description ?></p>
    <table width="100%">
        <tr>
            <td>
                <?= Html::img($qrCodeUrl, ['id' => 'qr-code', 'width' => 200, 'height' => 200]) ?>
            </td>
            <td>
                <p>
                <ul>
                    <li><?= $amount ?> BTC</li>
                    <li><?= $baseAmount ?> EUR</li>
                </ul>
                </p>
                <strong> <?= $receivingAddress ?></strong>
            </td>
        </tr>
    </table>
</div>