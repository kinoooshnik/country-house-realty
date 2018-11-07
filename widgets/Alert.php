<?php

namespace app\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 */
class Alert extends \yii\bootstrap4\Widget
{
    public static $alertTypes = [
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'success' => 'alert-success',
        'info' => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $flash) {
            if (!isset(self::$alertTypes[$type])) {
                continue;
            }

            foreach ((array)$flash as $i => $message) {
                echo \yii\bootstrap4\Alert::widget([
                    'body' => $message,
                    'closeButton' => $this->closeButton,
                    'options' => array_merge($this->options, [
                        'id' => $this->getId() . '-' . $type . '-' . $i,
                        'class' => self::$alertTypes[$type] . $appendClass,
                    ]),
                ]);
            }

            $session->removeFlash($type);
        }
    }

    public static function echoAlert($alertCode, $alertMessage)
    {
        return \yii\bootstrap4\Alert::widget([
            'options' => [
                'class' => self::$alertTypes[$alertCode],
            ],
            'body' => $alertMessage,
        ]);
    }

    public static function echoAlertFromReturnMessage(\app\models\ReturnMessageInterface $returnMessage)
    {
        if ($returnMessage->getReturnMessageCode()) {
            return self::echoAlert($returnMessage->getReturnMessageCode(), $returnMessage->getReturnMessage());
        }
    }
}
