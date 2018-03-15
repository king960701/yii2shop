<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m180309_030608_create_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->defaultValue(0),
            'password_hash' => $this->string()->notNull()->comment('密码'),

            'email' => $this->string()->notNull()->unique()->comment('邮件'),
            'tel' => $this->string(12)->notNull()->comment('电话'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('修改时间'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('member');
    }
}
