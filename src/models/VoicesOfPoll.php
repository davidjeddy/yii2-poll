<?php

namespace davidjeddy\yii2poll\models;

use yii\base\Model;

/**
 * Class VoicesOfPoll
 *
 * @package davidjeddy\yii2poll
 */
class VoicesOfPoll extends Model
{
    /**
     * @var
     */
    public $voice;

    /**
     * @var
     */
    public $type;

    /**
     * @return array
     */
    public function attributeLabels() : array
    {
        return ['voice' => '', 'type'  => ''];
    }
}
