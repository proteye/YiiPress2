<?php

use yii\db\Schema;
use yii\db\Migration;

class m150501_141825_page_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Page */
        $this->createTable('{{%page}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'lang' => Schema::TYPE_STRING . '(2)',
            'url' => Schema::TYPE_STRING . '(160) NOT NULL',
            'alias' => Schema::TYPE_STRING . '(160)',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'layout' => Schema::TYPE_STRING . '(250)',
            'view' => Schema::TYPE_STRING . '(250)',
            'meta_title' => Schema::TYPE_STRING . '(250)',
            'meta_keywords' => Schema::TYPE_STRING . '(250)',
            'meta_description' => Schema::TYPE_STRING . '(250)',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
            'access_type' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_page_url_lang', '{{%page}}', ['url', 'lang'], true);
        $this->createIndex('idx_page_status', '{{%page}}', 'status');
        $this->createIndex('idx_page_access_type', '{{%page}}', 'access_type');
        $this->createIndex('idx_page_created_by', '{{%page}}', 'created_by');
        $this->createIndex('idx_page_category_id', '{{%page}}', 'category_id');
        $this->createIndex('idx_page_sort', '{{%page}}', 'sort');
        $this->addForeignKey('fk_page_category_id', '{{%page}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_page_parent_id', '{{%page}}', 'parent_id', '{{%page}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_page_created_by', '{{%page}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_page_updated_by', '{{%page}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%page}}');
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
