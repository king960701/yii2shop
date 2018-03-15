<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m180314_062723_create_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('delivery', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'price' => $this->decimal(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('delivery');
    }
}
