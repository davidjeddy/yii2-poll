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
    public function isPollExist($pollName)
    {
        $db = \Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')
            ->bindParam(':pollName', $pollName);

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
     * @param $pollName
     *
     * @return array
     */
    public function getVoicesData($pollName)
    {
        $db = \Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_response WHERE poll_name=:pollName')
            ->bindParam(':pollName', $pollName);
        $voicesData = $command->queryAll();

        return $voicesData;
    }

    /**
     * @param $pollName
     * @param $voice
     * @param $answerOptions
     *
     * @return int
     */
    public function updateAnswers($pollName, $voice, $answerOptions)
    {

        return \Yii::$app->db->createCommand("
            UPDATE poll_response
            SET value = value +1  
            WHERE poll_name = '$pollName'
                AND answers = '$answerOptions[$voice]'")
            ->execute();

    }

    /**
     * @param $pollName
     *
     * @return int
     */
    public function updateUsers($pollName)
    {
        $db = \Yii::$app->db;

        $pollData = $db->createCommand('SELECT * FROM poll_question WHERE poll_name = :pollName')
            ->bindParam(':pollName', $pollName)
            ->queryOne();

        return $db->createCommand()
            ->insert('poll_user', [
                'poll_id'    => $pollData['id'],
                'user_id'    => $this->getUserId(),
                'created_at' => time()
        ])->execute();
    }

    /**
     * @param $pollName
     *
     * @return bool
     */
    public function isVote($pollName)
    {
        $db = \Yii::$app->db;
        $returnData = false;

        // get poll id
        $pollData = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')
            ->bindParam(':pollName', $pollName)
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
