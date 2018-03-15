<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180312_082649_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
//            id	primaryKey
//goods_id	int	商品id
            'goods_id' => $this->integer(),
//amount	int	商品数量
            'amount' => $this->integer(),
//member_id	int	用户id
            'member_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cart');
    }
}
