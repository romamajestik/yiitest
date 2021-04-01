<?php
namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string|null $repeater
 * @property int $user_id
 * @property string|null $created_at
 *
 * @property User $user
 * @property TaskEntity[] $taskEntities
 * @property Entity[] $entities
 */
class Task extends \yii\db\ActiveRecord
{
    public $entities_arr;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'user_id'], 'required'],
            [['repeater'], 'string'],
            [['user_id'], 'integer'],
            [['created_at', 'entities_arr'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'repeater' => 'Repeater',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'entities_arr' => 'Entities',
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
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskEntities()
    {
        return $this->hasMany(TaskEntity::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::class, ['id' => 'object_id'])->via('taskEntities');
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->unlinkAll('entities', true);
        if ($this->entities_arr) {
            foreach ($this->entities_arr as $item) {
                if ($e = Entity::findOne($item))
                    $this->link('entities', $e);
            }
        }
    }
}
