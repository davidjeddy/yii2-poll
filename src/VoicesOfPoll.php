<?php

namespace davidjeddy\yii2poll;

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
    public function attributeLabels() : araay
    {
        return ['voice' => '', 'type'  => ''];
    }
}
