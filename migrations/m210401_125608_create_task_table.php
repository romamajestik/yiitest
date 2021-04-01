<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m210401_125608_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'repeater' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('now()'),
        ], $tableOptions);

        $this->addForeignKey('task-user_id-fkey', 'task', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('task-user_id-idx', 'task', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('task-user_id-fkey', 'task');
        $this->dropIndex('task-user_id-idx', 'task');
        $this->dropTable('{{%task}}');
    }
}
