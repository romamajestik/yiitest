<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%object}}`.
 */
class m210401_125623_create_object_table extends Migration
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

        $this->createTable('object', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'file' => $this->string(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('now()'),
        ], $tableOptions);

        $this->addForeignKey('object-user_id-fkey', 'object', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('object-user_id-idx', 'object', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('object-user_id-fkey', 'object');
        $this->dropIndex('object-user_id-idx', 'object');
        $this->dropTable('{{%object}}');
    }
}
