<?php

namespace yanpapayan\blockchain\actions;

use yanpapayan\blockchain\ApiAdapter;
use yii\base\Action;
use yii\base\InvalidConfigException;

class ResultAction extends Action
{
    /** @var string */
    public $componentName;

    /** @var string */
    public $redirectUrl;

    /** @var bool */
    public $silent = false;

    /** @var ApiAdapter */
    private $api;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->api = \Yii::$app->get($this->componentName);
        if (!$this->api instanceof ApiAdapter) {
            throw new InvalidConfigException('Invalid component configuration');
        }

        parent::init();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function run()
    {
        try {
            return $this->api->processResult(\Yii::$app->request->getQueryParams());
        } catch (\Exception $e) {
            if (!$this->silent) {
                throw $e;
            }
        }

        if (isset($this->redirectUrl)) {
            return \Yii::$app->response->redirect($this->redirectUrl);
        }
    }
}