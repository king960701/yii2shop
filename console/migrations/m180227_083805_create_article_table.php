<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180227_083805_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article', [
                'id' => $this->primaryKey(),
                'name'=>$this->string(50)->notNull()->comment('名称'),
                //intro	text	简介
                'intro'=>$this->text()->notNull()->comment('简介'),
                //article_category_id	int(11)	文章分类id
                'article_category_id'=>$this->integer(11)->notNull()->comment('文章分类id'),
                //sort	int(11)	排序
                'sort'=>$this->integer(11)->notNull()->comment('排序'),
                //is_deleted	int(2)	状态(0正常 1删除)
                'is_deleted'=>$this->integer(2)->notNull()->comment('状态'),
                //create_time	int(11)	创建时间
                'create_time'=>$this->integer(11)->comment('创建时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
    }
}
