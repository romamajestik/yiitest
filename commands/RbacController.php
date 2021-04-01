<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\VarDumper;
use app\models\User;
use yii\rbac\DbManager;

/**
 * Class RbacController
 * @package app\commands
 */
class RbacController extends Controller
{
    public $defaultAction = 'assign';

    /**
     * @param $username
     * @return int
     * @throws \yii\base\Exception
     */
    public function actionCreateUser($username)
    {
        if (User::find()->where(['username' => $username])->exists()) {
            $this->stdout("This user name is already been taken\n", Console::FG_RED);
            return 0;
        }

        $user = new User([
            'username' => $username,
            'email' => $username."@email.ru",
        ]);

//        $password = Yii::$app->security->generateRandomString(10);
        $password = $username;
        $user->setPassword($password);
        $user->generateAuthKey();

        if($user->save()) {
            $this->stdout("New user created successfully\n", Console::FG_GREEN);
            $this->stdout("{$user->username}:{$password}\n", Console::FG_GREEN);
            $this->stdout("id = ".$user->id."\n", Console::FG_YELLOW);
        } else {
            $this->stdout("Unable to create new user\n", Console::FG_RED);
            echo VarDumper::dumpAsString($user->getErrors());
            return 0;
        }

        return 0;
    }

    /**
     * @param $id
     * @param string $role
     * @return int
     * @throws Exception
     */
    public function actionAssign($id, $role = 'admin')
    {
        $user = $this->findUser($id);

        /** @var DbManager $auth */
        $auth = Yii::$app->authManager;

        $user_role = $auth->getRole(trim($role));
        if (!$user_role) {
            $this->stdout("Роли {$role} не существует\n", Console::FG_RED);
            return 1;
        }

        if($auth->getAssignment($user_role->name, $user->id)) {
            $this->stdout("Роль {$role} уже связана с {$user->username}\n", Console::FG_YELLOW);
            return 0;
        }

        if ($auth->assign($user_role, $user->id)) {
            $this->stdout("Роль {$role} связана с {$user->username}\n", Console::FG_GREEN);
            return 0;
        }

        return 1;
    }

    /**
     * @param $name
     * @return int|string
     * @throws \Exception
     */
    public function actionGetUser($name)
    {
        $rows = [];
        /** @var User[] $users */
        $users = User::find()->andWhere(['like', 'username', $name])->all();
        if (!$users) {
            $this->stdout("Пользователей по маске {$name} не найдено\n", Console::FG_YELLOW);
            return 0;
        }

        foreach ($users as $user) {
            $rows []= "{$user->id}: {$user->username} ({$user->email})";
        }

        $this->stdout("Найденные записи\n", Console::FG_YELLOW);
        echo implode("\n", $rows);
        echo "\n";
        return 0;
    }

    /**
     * @param $id
     * @return User
     * @throws Exception
     */
    protected function findUser($id)
    {
        $model = User::findOne($id);
        if ($model === null) {
            throw new Exception('Прользователь не найден');
        }

        return $model;
    }

}