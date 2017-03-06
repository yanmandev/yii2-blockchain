<?php
/**
 * @author Ian Kuznetsov <yankuznecov@ya.ru>
 * Date: 06.03.2017
 * Time: 18:51
 */

namespace yanpapayan\blockchain;

use Blockchain\Blockchain;
use yii\base\Component;

class ApiAdapter extends Component
{
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
     * @param $invoiceId
     * @return string
     */
    public function generateSecretKey($invoiceId)
    {
        return md5($invoiceId . ':' . $this->apiKey);
    }
}