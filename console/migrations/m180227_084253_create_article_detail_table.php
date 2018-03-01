<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m180227_084253_create_article_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article_detail', [
            'article_id' => $this->primaryKey(),
            'content' => $this->text()->notNull()->comment('内容'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article_detail');
    }
}
