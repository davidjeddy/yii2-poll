<?php

namespace davidjeddy\poll;

use \Yii;
use \yii\db\Query;

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
     * @param $questionText string
     *
     * @return bool
     */
    public function doesPollExist($questionText)
    {
        $val = Yii::$app->db->createCommand('SELECT * FROM poll_question WHERE question_text = :questionText')
            ->bindParam(':questionText', $questionText)
            ->queryOne();

        return (!empty($val) ? true : false);
    }

    /**
     * poll_response TBO logic
     * ADDS new answers dynamically.
     * REMOVES answers that are not part of $pollObj->answerOptionsData
     *
     * @param PollWidget $pollObj
     * @return null
     */
    public function pollAnswerOptions(\davidjeddy\poll\PollWidget $pollObj)
    {
        $returnData = null;

        try {
            $db = Yii::$app->db;

            foreach ($pollObj->answerOptions as $key => $value) {

                $returnData = (new \yii\db\Query())
                    ->select(['answers'])
                    ->from('poll_response')
                    ->andWhere(['question_text' => $pollObj->questionText])
                    ->andWhere(['answers' => $value])
                    ->one();

                if (!$returnData) {
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
            $answerText =  '("' . implode('", "', $pollObj->answerOptions) . '")';
            $model = $db->createCommand('
              DELETE FROM poll_response
              WHERE question_text = :question_text
                AND answers NOT IN :answer_text');
            $model->bindParam(':question_text', $pollObj->questionText);
            $model->bindParam(':answer_text', $answerText);
            $model->execute();

        } catch (\Exception $e) {
            throw new $e('Unable to get poll question\'s answers.');
        }

        return $returnData;
    }

    /**
     * @param $questionText
     *
     * @return array
     */
    public function getVoicesData($questionText)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll_response WHERE question_text = :questionText')
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
        return Yii::$app->db->createCommand('
            UPDATE poll_response
            SET value = value + 1  
            WHERE question_text = :questionText
                AND answers = :answerOptions')
            ->bindParam(':questionText', $questionText)
            ->bindParam(':answerOptions', $answerOptions[$voice])
            ->execute();
    }

    /**
     * @param $questionText
     *
     * @return int
     */
    public function updatePollUsers($questionText)
    {
        $pollData = Yii::$app->db->createCommand('
                SELECT *
                FROM poll_question
                WHERE question_text = :questionText')
            ->bindParam(':questionText', $questionText)
            ->queryOne();

        return Yii::$app->db->createCommand('
                INSERT INTO poll_user (poll_id, user_id, created_at)
                VALUES (:poll_id, :user_id, :created_at)')
            ->bindParam(':poll_id', $pollData['id'])
            ->bindParam(':user_id', $this->getUserId())
            ->bindParam(':created_at', time())
        ->execute();
    }

    /**
     * @param $questionText
     *
     * @return bool
     */
    public function isVote($questionText)
    {
        $db = Yii::$app->db;
        $returnData = false;

        // get poll id
        $pollData = $db->createCommand('
                SELECT *
                FROM poll_question
                WHERE question_text = :questionText')
            ->bindParam(':questionText', $questionText)
            ->queryOne();

        $command = $db->createCommand('
                SELECT *
                FROM  poll_user 
                WHERE user_id = :userId
                AND poll_id = :pollId')
            ->bindPara(':userId', $this->getUserId())
            ->bindParam(':pollId', $pollData['id']);

        if ($command->queryOne()) {
            $returnData = true;
        }

        return $returnData;
    }

    /**
     * Check that all three tables are in the db
     *
     * @return int
     */
    public function doTablesExist()
    {
        $counter = 0;

        $results[] = Yii::$app->db->createCommand("SHOW TABLES LIKE 'poll_question'")->queryOne();
        $results[] = Yii::$app->db->createCommand("SHOW TABLES LIKE 'poll_response'")->queryOne();
        $results[] = Yii::$app->db->createCommand("SHOW TABLES LIKE 'poll_user'")->queryOne();

        foreach($results as $result) {
            if (is_array($result)) {
                $counter++;
            }
        }

        return $counter;
    }

    /**
     * @param PollWidget $poll
     *
     * @return int
     */
    public function saveNewPoll(\davidjeddy\poll\PollWidget $poll)
    {
        return \Yii::$app->db->createCommand()
            ->insert('poll_question', [
                'answer_options' => serialize($poll->answerOptions),
                'question_text'  => $poll->questionText
            ])
            ->execute();
    }

    /**
     * @param PollWidget $poll
     *
     * @return mixed
     */
    public function getPollQuestionData(\davidjeddy\poll\PollWidget $poll)
    {
        $data = Yii::$app->db->createCommand('SELECT * FROM poll_question WHERE question_text = :questionText')
            ->bindParam(':questionXText', $poll->questionText)
            ->queryOne();

        $returnData = unserialize($data['answer_options']);

        return $returnData;
    }

    /**
     * @param PollWidget $param
     */
    public function getDbData(\davidjeddy\poll\PollWidget $param)
    {
        $data = Yii::$app->db->createCommand('
                SELECT *
                FROM poll_question
                WHERE question_text = :questionText
            ')
            ->bindParam(':questionText', $param->questionText)
            ->queryOne();

        return unserialize($data['answer_options']);
    }

    /**
     * @return int
     */
    private function getUserId()
    {
        $userId = (!empty(Yii::$app->user->getId()) ? Yii::$app->user->getId() : 0);

        return (integer)$userId;
    }
}
