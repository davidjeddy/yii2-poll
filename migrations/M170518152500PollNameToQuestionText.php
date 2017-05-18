<?php

use yii\db\Migration;

/**
 * Class M170518152500PollNameToQuestionText
 */
class M170518152500PollNameToQuestionText extends Migration
{
    /**
     *
     */
    public function safeUp()
    {
        $this->renameColumn('{{%poll_question}}', 'poll_name', 'question_text');
        $this->renameColumn('{{%poll_response}}', 'poll_name', 'question_text');

    }

    /**
     *
     */
    public function safeDown()
    {
        $this->renameColumn('{{%poll_question}}', 'question_text', 'poll_name');
        $this->renameColumn('{{%poll_response}}', 'question_text', 'poll_name');
    }
}