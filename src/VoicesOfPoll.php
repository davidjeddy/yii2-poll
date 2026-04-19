<?php

namespace davidjeddy\poll;

use yii\base\Model;

/**
 * Class VoicesOfPoll
 *
 * @author David J Eddy <me@davidjeddy.com>
 *
 * @package davidjeddy\poll
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
    public function attributeLabels()
    {
        return [
            'voice' => '',
            'type'  => ''

        ];
    }
}
