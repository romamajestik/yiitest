<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_task_object}}`.
 */
class m210401_125641_create_task_object_table extends Migration
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

        $this->createTable('task_object', [
            'task_id' => $this->integer()->notNull(),
            'object_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('now()'),
        ], $tableOptions);

        $this->addForeignKey('task_object-task_id-fkey', 'task_object', 'task_id', 'task', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('task_object-task_id-idx', 'task_object', 'task_id');
        $this->addForeignKey('task_object-object_id-fkey', 'task_object', 'object_id', 'object', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('task_object-object_id-idx', 'task_object', 'object_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('task_object-task_id-fkey', 'task_object');
        $this->dropIndex('task_object-task_id-idx', 'task_object');
        $this->dropForeignKey('task_object-object_id-fkey', 'task_object');
        $this->dropIndex('task_object-object_id-idx', 'task_object');
        $this->dropTable('{{%task_object}}');
    }
}
