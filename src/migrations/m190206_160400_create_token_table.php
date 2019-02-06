<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%token}}`.
 */
class m190206_160400_create_token_table extends Migration
{
    public $tableOptions;

    public $tokenTable = '{{%token}}';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        switch (Yii::$app->db->driverName) {
            case 'mysql':
                $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tokenTable, [
            'id' => $this->primaryKey(),
            'group' => $this->string(20)->defaultValue(null)->comment('分组'),
            'user_id' => $this->integer()->notNull(),
            'value' => $this->string(120)->notNull(),
            'ip' => $this->string(120)->notNull()->comment('ip'),
            'status' => $this->tinyInteger(1)->defaultValue(10)->comment('状态 10 正常 0删除'),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
            'updated_at' => $this->integer()->unsigned()->defaultValue(null),
            'expired_at' => $this->integer()->unsigned()->defaultValue(null)->comment('过期时间戳'),
        ], $this->tableOptions);
        $this->createIndex('idx_group_token', $this->tokenTable, ['group', 'token'], true);
        $this->createIndex('idx_token_expired_at', $this->tokenTable, ['value', 'group', 'expired_at']);
        $this->createIndex('idx_user_id', $this->tokenTable, 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tokenTable);
    }
}
