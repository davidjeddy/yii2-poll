<?php

use common\models\User;
use yii\db\Schema;
use yii\db\Migration;

class M170205110001DemoPoll extends Migration
{
    /**
     *
     */
    public function safeUp()
    {
        //insert($table, $columns)
        $this->insert('{{%poll_question}}', [
            'question_text'  => 'Poll 1',
            'answer_options' => 'a:2:{i:0;s:3:\"Yes\";i:1;s:2:\"No\";}',
            'created_at'     => time()
        ]);
        $this->insert('{{%poll_question}}', [
            'question_text'  => 'Poll 2',
            'answer_options' => 'a:4:{i:0;s:3:\"Foo\";i:1;s:3:\"Bar\";i:2;s:8:\"hello...\";i:3;s:18:\"~!@#$%^&*()_+world\";}',
            'created_at'     => time()
        ]);
        $this->insert('{{%poll_question}}', [
            'question_text'  => 'Poll 3',
            'answer_options' => 'a:4:{i:0;s:8:\"option 1\";i:1;s:8:\"option 2\";i:2;s:8:\"option 3\";i:3;s:8:\"option 4\";}',
            'created_at'     => time()
        ]);
        $this->insert('{{%poll_question}}', [
            'question_text'  => 'Poll 4',
            'answer_options' => 'a:6:{i:0;s:4:\"qwer\";i:1;s:4:\"asdf\";i:2;s:4:\"zxcv\";i:3;s:3:\"rty\";i:4;s:3:\"dfg\";i:5;s:3:\"cvb\";}',
            'created_at'     => time()
        ]);
        $this->insert('{{%poll_question}}', [
            'question_text'  => 'Poll 5',
            'answer_options' => 'a:2:{i:0;s:4:\"qwer\";i:1;s:4:\"zxcv\";}',
            'created_at'     => time()
        ]);
    }

    /**
     *
     */
    public function safeDown()
    {
        $this->truncateTable('{{%poll_question}}');
        $this->truncateTable('{{%poll_response}}');
        $this->truncateTable('{{%poll_user}}');
    }
}
