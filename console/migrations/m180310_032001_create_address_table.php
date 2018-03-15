<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180310_032001_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull()->comment('当前登录人'),
            'name'=>$this->string(50)->notNull()->comment('姓名'),
            'tel'=>$this->integer(12)->notNull()->comment('电话'),
            'province'=>$this->string()->notNull()->comment('省'),
            'city'=>$this->string()->notNull()->comment('市'),
            'county'=>$this->string()->notNull()->comment('县'),
            'address'=>$this->string()->notNull()->comment('详细地址'),
            'status'=>$this->integer(1)->comment('状态'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }
}
