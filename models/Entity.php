<?php
namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "object".
 *
 * @property int $id
 * @property string $name
 * @property string|null $file
 * @property int $user_id
 * @property string|null $created_at
 *
 * @property User $user
 * @property TaskEntity[] $taskEntities
 * @property Task[] $tasks
 */
class Entity extends \yii\db\ActiveRecord
{
    public $tasks_arr;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'object';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'tasks_arr'], 'safe'],
            [['name', 'file'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'file' => 'File',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'tasks_arr' => 'Tasks',
        ];
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[TaskObjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskEntities()
    {
        return $this->hasMany(TaskEntity::class, ['object_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['id' => 'task_id'])->via('taskEntities');
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->unlinkAll('tasks', true);
        if ($this->tasks_arr) {
            foreach ($this->tasks_arr as $item) {
                if ($task = Task::findOne($item))
                    $this->link('tasks', $task);
            }
        }
    }
}
