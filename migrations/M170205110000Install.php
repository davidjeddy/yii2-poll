<?php

use common\models\User;
use yii\db\Schema;
use yii\db\Migration;

class M170205110000Install extends Migration
{
    /**
     *
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%poll_question}}', [
            'id'             => $this->primaryKey(),
            'question_text'  => $this->string(128),
            'answer_options' => $this->text()->notNull(),
            'created_at'     => $this->integer(11)->notNull(),
            'updated_at'     => $this->integer(11),
            'deleted_at'     => $this->integer(11)
        ], $tableOptions);

        $this->createTable('{{%poll_response}}', [
            'user_id'       => $this->primaryKey(11),
            'question_text' => $this->string(128),
            'answers'       => $this->text(),
            'value'         => $this->integer(11),
            'created_at'    => $this->integer(11)->notNull(),
            'updated_at'    => $this->integer(11),
            'deleted_at'    => $this->integer(11)
        ], $tableOptions);

        $this->createTable('{{%poll_user}}', [
            'id'      => $this->primaryKey(11),
            'poll_id' => $this->integer(11),
            'user_id' => $this->integer(11)
        ], $tableOptions);
    }

    /**
     *
     */
    public function safeDown()
    {
        $this->dropTable('{{%poll_question}}');
        $this->dropTable('{{%poll_response}}');
        $this->dropTable('{{%poll_user}}');
    }
}
