<?php

namespace davidjeddy\yii2poll;

use yii\base\Model;

class VoicesOfPoll extends Model{
    public $voice;
    public $type;

    public function attributeLabels()
    {
        return [
            'voice' => '',
            'type' => ''
            
        ];
    }
}
