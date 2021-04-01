<?php

use yii\db\Migration;

/**
 * Class m210401_133448_create_roles
 */
class m210401_133448_create_roles extends Migration
{
    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        /** @var \yii\rbac\DbManager $auth */
        $auth = Yii::$app->authManager;

        $user_role = $auth->createRole('user');
        $auth->add($user_role);
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $user_role);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210401_133448_create_roles cannot be reverted.\n";

        return false;
    }

}
