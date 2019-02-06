<?php

namespace hessian\yii\token\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%token}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $group
 * @property string $value
 * @property string $ip ip
 * @property integer $status 状态 10 正常 0删除
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expired_at 过期时间戳
 */
class Token extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at', 'expired_at'], 'integer'],
            [['group'], 'string', 'max' => 20],
            [['group', 'value'], 'unique', 'targetAttribute' => ['group', 'value'], 'targetClass' => '\hessian\yii\token\models\Token', 'message' => 'Token already exists'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group' => Yii::t('app', 'Group'),
            'user_id' => Yii::t('app', 'User ID'),
            'value' => Yii::t('app', 'Value'),
            'ip' => Yii::t('app', 'Ip'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'expired_at' => Yii::t('app', 'Expired At'),
        ];
    }


    /**
     * 返回当前未过期的 token
     * @param string $token
     * @return Token|false
     */
    public static function findActiveByToken($token, $group = 'default')
    {
        return self::find()
            ->where(['value' => $token, 'group' => $group])
            ->andWhere(['>', 'expired_at', time()])
            ->limit(1)
            ->one();
    }


    /**
     * 返回当前用户使用的 token
     * @param int $userId
     * @return Token|false
     */
    public static function findActiveByUserId($userId, $group = 'default')
    {
        return self::find()
            ->where(['user_id' => $userId, 'group' => $group])
            ->andWhere(['>', 'expired_at', time()])
            ->limit(1)
            ->one();
    }

    public function getStatus()
    {
        return [
            '' => '全部',
            self::STATUS_ACTIVE => '正常',
            self::STATUS_DELETE => '禁用 ',
        ];
    }
}
