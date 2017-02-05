<?php

namespace davidjeddy\poll;

/**
 * Class PollDb
 *
 * @author David J Eddy <me@davidjeddy.com>
 *
 * @package davidjeddy\poll
 */
class PollDb
{
    /**
     *
     */
    public function isPollExist($questionText)
    {
        $db = \Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE question_text=:questionText')
            ->bindParam(':questionText', $questionText);

        $pollData = $command->queryOne();

        return (empty($pollData) ? true : false);
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
        $db = \Yii::$app->db;

        foreach ($pollObj->answerOptions as $key => $value) {

            $answer = (new \yii\db\Query())
                ->select(['answers'])
                ->from('poll_response')
                ->andWhere(['question_text' => $pollObj->questionText])
                ->andWhere(['answers' => $value])
                ->one();

            if (!$answer) {
                $db->createCommand()->insert('poll_response', [
                    'answers'   => $value,
                    'question_text' => $pollObj->questionText,
                    'value'     => 0,
                ])->execute();
            }
        }

        // remove answers that are no longer a part of the poll answer_options
        // source http://stackoverflow.com/questions/31672033/how-do-i-delete-rows-in-yii2
        return (new \yii\db\Query())
            ->createCommand()
            ->delete('poll_response')
            ->where(['question_text' => $pollObj->questionText])
            ->where(['NOT IN', 'answers', implode($pollObj->answerOptions, "', '")])
            ->execute();
    }

    /**
     * @param $questionText
     *
     * @return array
     */
    public function getVoicesData($questionText)
    {
        $db = \Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_response WHERE question_text=:questionText')
            ->bindParam(':questionText', $questionText);
        $voicesData = $command->queryAll();

        return $voicesData;
    }

    /**
     * @param $questionText
     * @param $voice
     * @param $answerOptions
     *
     * @return int
     */
    public function updateAnswers($questionText, $voice, $answerOptions)
    {

        return \Yii::$app->db->createCommand("
            UPDATE poll_response
            SET value = value +1  
            WHERE question_text = '$questionText'
                AND answers = '$answerOptions[$voice]'")
            ->execute();

    }

    /**
     * @param $questionText
     *
     * @return int
     */
    public function updateUsers($questionText)
    {
        $db = \Yii::$app->db;

        $pollData = $db->createCommand('SELECT * FROM poll_question WHERE question_text = :questionText')
            ->bindParam(':questionText', $questionText)
            ->queryOne();

        return $db->createCommand()
            ->insert('poll_user', [
                'poll_id'    => $pollData['id'],
                'user_id'    => $this->getUserId(),
                'created_at' => time()
        ])->execute();
    }

    /**
     * @param $questionText
     *
     * @return bool
     */
    public function isVote($questionText)
    {
        $db = \Yii::$app->db;
        $returnData = false;

        // get poll id
        $pollData = $db->createCommand('SELECT * FROM poll_question WHERE question_text=:questionText')
            ->bindParam(':questionText', $questionText)
            ->queryOne();

        $command = $db->createCommand("SELECT * FROM  poll_user  WHERE user_id=" . $this->getUserId() . " AND poll_id=:pollId")
            ->bindParam(':pollId', $pollData['id']);

        if ($command->queryOne()) {
            $returnData = true;
        }

        return $returnData;
    }

    /**
     * @return array
     */
    public function isTableExists()
    {
        $db = \Yii::$app->db;
        $command = $db->createCommand("SHOW TABLES LIKE 'poll_question'");
        $res = $command->queryAll();

        return $res;
    }

    /**
     * @return int
     */
    private function getUserId()
    {
        $userId = (!empty(\Yii::$app->user->getId()) ? \Yii::$app->user->getId() : 0);

        return (integer)$userId;
    }
}
