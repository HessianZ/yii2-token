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
        $model->ip = ip2long(\Yii::$app->request->userIP);
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

    /**
     * @param string $tokenStr Token value
     * @return int|false
     */
    public function expireToken($tokenStr)
    {
        $conditions = ['token' => $tokenStr];
        $token = models\Token::findOne($conditions);
        if ($token) {
            $token->status = models\Token::STATUS_DELETE;
            \Yii::info("ExpireToken - UID:{$token->user_id} GROUP:{$token->group} TOKEN:{$token->value}", __METHOD__);
            return $token->update();
        } else {
            \Yii::info("ExpireToken - Token not found '$tokenStr'", __METHOD__);
            return false;
        }
    }

    /**
     * @param int $userId User ID
     * @param string $group Token group, null for all
     * @return int The number of rows updated
     */
    public function expireUserTokens($userId, $group = null)
    {
        $conditions = ['userId' => $userId];
        if ($group) {
            $conditions['group'] = $group;
        }
        $data = ['status' => models\Token::STATUS_DELETE, 'updated_at' => time()];
        $expiredRows = models\Token::updateAll($data, $conditions);

        \Yii::info("ExpireUserTokens - UserId:$userId Group:$group - Expired:$expiredRows", __METHOD__);

        return $expiredRows;
    }

    public function generateTokenString()
    {
        return \Yii::$app->security->generateRandomString($this->tokenLength);
    }


}

