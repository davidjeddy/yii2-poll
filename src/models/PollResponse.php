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
     * @param \davidjeddy\yii2poll\Module $pollObj
     *
     * @return int
     */
    public function pollAnswerOptions(\davidjeddy\yii2poll\Module $pollObj) : int
    {
        $db = Yii::$app->db;

        foreach ($pollObj->answerOptions as $key => $value) {

            $answer = (new \yii\db\Query())
                ->select(['answers'])
                ->from('poll_response')
                ->andWhere(['question_text' => $pollObj->questionText])
                ->andWhere(['answers'       => $value])
                ->one();

            if (!$answer) {
                $db->createCommand()->insert('poll_response', [
                    'answers'       => $value,
                    'question_text' => $pollObj->questionText,
                    'value'         => 0,
                    'created_at'    => time()
                ])->execute();
            }
        }

        // remove answers that are no longer a part of the poll answer_options
        // source http://stackoverflow.com/questions/31672033/how-do-i-delete-rows-in-yii2
        // DELETE FROM `poll_response` WHERE question_text = 'Do you like PHP?' AND answers NOT IN ('Yes, No');
        return $db->createCommand()
            ->delete('poll_response',
                'question_text = :question_text AND answers NOT IN (:answerOptions)',
                [
                    ':question_text'=> $pollObj->questionText,
                    ':answerOptions'=> implode(', ', $pollObj->answerOptions)
                ]
            )
            ->execute();
    }

    /**
     * @param string $questionText
     *
     * @return array
     */
    public function getVoicesData(string $questionText)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_response WHERE question_text=:questionText')->
        bindParam(':questionText', $questionText);
        $voicesData = $command->queryAll();

        return $voicesData;
    }

    /**
     * @param string $questionText
     * @param integer $voice
     * @param array  $answerOptions
     *
     * @return yii\db\Query
     */
    public function updateAnswers(string $questionText, integer $voice, array $answerOptions) : \yii\db\Query
    {

        return Yii::$app->db->createCommand("
            UPDATE poll_response
            SET value = value +1  
            WHERE question_text = '$questionText'
                AND answers = '$answerOptions[$voice]'")
            ->execute();

    }

    /**
     * @param string $questionText
     */
    public function updateUsers(string $questionText)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE question_text=:questionText')
            ->bindParam(':questionText', $questionText);


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
     * @param string $questionText
     *
     * @return array|false
     */
    public function isVote(string $questionText)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE question_text=:questionText')
            ->bindParam(':questionText', $questionText);
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
