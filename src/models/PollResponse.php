<?php

namespace davidjeddy\yii2poll\models;

use yii;

/**
 * Class PollResponse
 *
 * @package davidjeddy\yii2poll
 */
class PollResponse
{
    /**
     * poll_response table logic
     *
     * ADDS new answers dynamically.
     * REMOVES answers that are not part of $pollObj->answerOptionsData
     *
     * @param Module $pollObj
     *
     * @return yii\db\Query
     */
    public function pollAnswerOptions(\davidjeddy\yii2poll\Module $pollObj) : \yii\db\Query
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
     * @param string $pollName
     *
     * @return array
     */
    public function getVoicesData(string $pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_response WHERE poll_name=:pollName')->
        bindParam(':pollName', $pollName);
        $voicesData = $command->queryAll();

        return $voicesData;
    }

    /**
     * @param string $pollName
     * @param integer $voice
     * @param array  $answerOptions
     *
     * @return yii\db\Query
     */
    public function updateAnswers(string $pollName, integer $voice, array $answerOptions) : \yii\db\Query
    {

        return Yii::$app->db->createCommand("
            UPDATE poll_response
            SET value = value +1  
            WHERE poll_name = '$pollName'
                AND answers = '$answerOptions[$voice]'")
            ->execute();

    }

    /**
     * @param string $pollName
     */
    public function updateUsers(string $pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')
            ->bindParam(':pollName', $pollName);


        if (Yii::$app->user->getId() === null) {
            $userId = 0;
        } else {
            $userId = Yii::$app->user->getId();
        }

        $pollData = $command->queryOne();

        $db->createCommand()->insert('poll_user', [
            'poll_id' => $pollData['id'],
            'user_id' => $userId
        ])->execute();
    }

    /**
     * @param string $pollName
     *
     * @return array|false
     */
    public function isVote(string $pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')
            ->bindParam(':pollName', $pollName);
        $pollData = $command->queryOne();

        if (Yii::$app->user->getId() === null) {
            $userId = 0;
        } else {
            $userId = Yii::$app->user->getId();
        }

        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM  poll_user  WHERE user_id=' . $userId . ' AND poll_id=:pollId')
            ->bindParam(':pollId', $pollData['id']);
        return $command->queryOne();
    }

    /**
     * @return array
     */
    public function isTableExists()
    {
        $db = Yii::$app->db;
        $command = $db->createCommand("SHOW TABLES LIKE 'poll_question'");
        return $command->queryAll();
    }
}
