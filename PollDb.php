<?php

namespace davidjeddy\yii2poll;

use yii;

class PollDb
{

    /**
     *
     */
    public function isPollExist($pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')
            ->bindParam(':pollName', $pollName);

        $pollData = $command->queryOne();

        return (empty($pollData) ? true : false);
    }

    /**
     * [setVoicesData description]
     *
     * @deprecated 2.0.2 Use pollAnswerOptions()
     *
     * @param [type] $pollName      [description]
     * @param [type] $answerOptions [description]
     */
    public function setVoicesData($pollName, $answerOptions)
    {
        $db = Yii::$app->db;
        $count = count($answerOptions);

        for ($i = 0; $i < $count; $i++) {
            $db->createCommand()->insert('poll_response', [
                'answers'   => $answerOptions[$i],
                'poll_name' => $pollName,
                'value'     => 0,
            ])->execute();
        }
    }

    /**
     * poll_response TBO logic
     * ADDS new answers dynamically.
     * REMOVES answers that are not part of $pollObj->answerOptionsData
     *
     * @param  [type] $pollObj [description]
     *
     * @return [type]          [description]
     */
    public function pollAnswerOptions(\davidjeddy\yii2poll\Poll $pollObj)
    {
        $db = Yii::$app->db;

        foreach ($pollObj->answerOptions as $key => $value) {

            $answer = (new \yii\db\Query())
                ->select(['answers'])
                ->from('poll_response')
                ->andWhere(['poll_name' => $pollObj->pollName])
                ->andWhere(['answers' => $value])
                ->one();

            if (!$answer) {
                $db->createCommand()->insert('poll_response', [
                    'answers'   => $value,
                    'poll_name' => $pollObj->pollName,
                    'value'     => 0,
                ])->execute();
            }
        }

        // remove answers that are no longer a part of the poll answer_options
        // source http://stackoverflow.com/questions/31672033/how-do-i-delete-rows-in-yii2
        return (new \yii\db\Query())
            ->createCommand()
            ->delete('poll_response')
            ->where(['poll_name' => $pollObj->pollName])
            ->where(['NOT IN', 'answers', implode($pollObj->answerOptions, "', '")])
            ->execute();
    }

    /**
     *
     */
    public function getVoicesData($pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_response WHERE poll_name=:pollName')->
        bindParam(':pollName', $pollName);
        $voicesData = $command->queryAll();

        return $voicesData;
    }

    /**
     * [updateAnswers description]
     *
     * @version  2.0.7
     * @since  na
     *
     * @param  string  $pollName Poll name
     * @param  integer $voice Integer of chosen key
     * @param  array   $answerOptions Array fo possible options
     *
     * @return object
     */
    public function updateAnswers($pollName, $voice, $answerOptions)
    {

        return Yii::$app->db->createCommand("
            UPDATE poll_response
            SET value = value +1  
            WHERE poll_name = '$pollName'
                AND answers = '$answerOptions[$voice]'")
            ->execute();

    }

    /**
     *
     */
    public function updateUsers($pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')->
        bindParam(':pollName', $pollName);
        $userId;
        if (Yii::$app->user->getId() == null) {
            $userId = 0;
        } else {
            $userId = Yii::$app->user->getId();
        }
        $pollData = $command->queryOne();
        $command = $db->createCommand()->insert('poll_user', [
            'poll_id' => $pollData['id'],
            'user_id' => $userId
        ])->execute();
    }

    /**
     *
     */
    public function isVote($pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')->
        bindParam(':pollName', $pollName);
        $pollData = $command->queryOne();
        $userId;
        if (Yii::$app->user->getId() == null) {
            $userId = 0;
        } else {
            $userId = Yii::$app->user->getId();
        }
        $db = Yii::$app->db;
        $command = $db->createCommand("SELECT * FROM  poll_user  WHERE user_id='$userId' AND poll_id=:pollId")->
        bindParam(':pollId', $pollData['id']);
        $result = $command->queryOne();

        if ($result == null) {

            return false;
        } else {

            return true;
        }
    }

    /**
     *
     */
    public function createTables()
    {
        $db = Yii::$app->db;
        $command_1 = $db->createCommand("
            CREATE TABLE IF NOT EXISTS `poll_user` (
            `id`            int(11) NOT NULL AUTO_INCREMENT,
            `poll_id`       int(11) NOT NULL,
            `user_id`       int(11) NOT NULL,
            'created_at'    int(11) NOT NULL,
            'created_at'    int(11) NULL,
            'created_at'    int(11) NULL,
            PRIMARY KEY (`id`),
            KEY `poll_id` (`poll_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8"
        )->execute();

        $command_2 = $db->createCommand("
            CREATE TABLE IF NOT EXISTS `poll_question` (
            `id`            int(11) NOT NULL AUTO_INCREMENT,
            `poll_name`     varchar(128) NOT NULL,
            `answer_options`text NOT NULL,
            'created_at'    int(11) NOT NULL,
            'created_at'    int(11) NULL,
            'created_at'    int(11) NULL,
            PRIMARY KEY (`id`),
            KEY `poll_name` (`poll_name`(128))
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8"
        )->execute();

        $command_3 = $db->createCommand("
            CREATE TABLE IF NOT EXISTS `poll_response` (
            `id`            int(11) NOT NULL AUTO_INCREMENT,
            `poll_name`     varchar(128) NOT NULL,
            `answers`       varchar(128) CHARACTER SET utf8mb4 NOT NULL,
            `value`         int(11) NOT NULL,
            'created_at'    int(11) NOT NULL,
            'created_at'    int(11) NULL,
            'created_at'    int(11) NULL,
            PRIMARY KEY (`id`),
            KEY `poll_name` (`poll_name`(128))
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8"
        )->execute();
    }

    /**
     *
     */
    public function isTableExists()
    {
        $db = Yii::$app->db;
        $command = $db->createCommand("SHOW TABLES LIKE 'poll_question'");
        $res = $command->queryAll();

        return $res;
    }
}
