<?php

use yii\db\Schema;
use yii\db\Migration;

class m150501_143903_blog_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Post */
        $this->createTable('{{%post}}', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'lang' => Schema::TYPE_STRING . '(2)',
            'url' => Schema::TYPE_STRING . '(160) NOT NULL',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'quote' => Schema::TYPE_STRING . '(512)',
            'content' => Schema::TYPE_TEXT,
            'image' => Schema::TYPE_STRING . '(255)',
            'create_user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'update_user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'published_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'create_user_ip' => Schema::TYPE_STRING . '(20)',
            'link' => Schema::TYPE_STRING . '(255)',
            'meta_title' => Schema::TYPE_STRING . '(250)',
            'meta_keywords' => Schema::TYPE_STRING . '(250)',
            'meta_description' => Schema::TYPE_STRING . '(250)',
            'access_type' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            'comment_status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_post_url_lang', '{{%post}}', ['url', 'lang'], true);
        $this->createIndex('idx_post_category_id', '{{%post}}', 'category_id');
        $this->createIndex('idx_post_url', '{{%post}}', 'url');
        $this->createIndex('idx_post_lang', '{{%post}}', 'lang');
        $this->createIndex('idx_post_create_user_id', '{{%post}}', 'create_user_id');
        $this->createIndex('idx_post_published_at', '{{%post}}', 'published_at');
        $this->createIndex('idx_post_access_type', '{{%post}}', 'access_type');
        $this->createIndex('idx_post_comment_status', '{{%post}}', 'comment_status');
        $this->createIndex('idx_post_status', '{{%post}}', 'status');
        $this->addForeignKey('fk_post_category_id', '{{%post}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'NO ACTION');

        /* Tag */
        $this->createTable('{{%tag}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'url' => Schema::TYPE_STRING . '(160) NOT NULL',
        ], $tableOptions);

        $this->createIndex('idx_tag_url', '{{%tag}}', 'url', true);

        /* Post to Tag */
        $this->createTable('{{%post_tag}}', [
            'post_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tag_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('pk_post_tag', '{{%post_tag}}', ['post_id', 'tag_id']);
        $this->createIndex('idx_post_tag_post_id', '{{%post_tag}}', 'post_id');
        $this->createIndex('idx_post_tag_tag_id', '{{%post_tag}}', 'tag_id');
        $this->addForeignKey('fk_post_tag_post_id', '{{%post_tag}}', 'post_id', '{{%post}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_post_tag_tag_id', '{{%post_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%post_tag}}');
        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%post}}');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
