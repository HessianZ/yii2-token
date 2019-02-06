<?php
/**
 * User: hessian
 * Date: 2019/2/6
 */

namespace hessian\yii\token;


/**
 * 寻程数据ApiStore接口类
 */
class Token
{
    public $tokenLength = 32;
    public $defaultGroup = 'default'; // e.g. App, Site
    public $defaultTimeout = 30 * 86400; // 30 days
    public $logEnabled = true;

    /**
     * @param int $uid
     * @param string $group
     * @return models\Token;
     */
    public function createToken($userId, $group = null)
    {
        $model = new models\Token();
        $model->user_id = $userId;
        $model->group = $group ?: $this->defaultGroup;
        $model->expired_at = time() + $this->defaultTimeout;
        $model->ip = \Yii::$app->request->userIP;
        $model->value = $this->generateTokenString();
        $ret = $model->save();
        \Yii::info("CreateToken - UID:$userId GROUP:$group TOKEN:$model->value", __METHOD__);

        return $model;
    }

    /**
     * @param string $value
     * @param string $group
     * @return models\Token;
     */
    public function findToken($value, $group = null)
    {
        $condition = [
            'value' => $value,
            'group' => $group ?: $this->defaultGroup
        ];

        return models\Token::find()
          ->where($condition)
          ->andWhere(['>', 'expired_at', time()])
          ->limit(1)
          ->one();
    }

    public function generateTokenString()
    {
        return \Yii::$app->security->generateRandomString($this->tokenLength);
    }


}

