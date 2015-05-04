<?php

use yii\db\Schema;
use yii\db\Migration;

class m150430_121350_category_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Category */
        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'lang' => Schema::TYPE_STRING . '(2)',
            'alias' => Schema::TYPE_STRING . '(160) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'short_description' => Schema::TYPE_STRING . '(512)',
            'description' => Schema::TYPE_TEXT,
            'image' => Schema::TYPE_STRING . '(255)',
            'image_alt' => Schema::TYPE_STRING . '(255)',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'meta_title' => Schema::TYPE_STRING . '(250)',
            'meta_keywords' => Schema::TYPE_STRING . '(250)',
            'meta_description' => Schema::TYPE_STRING . '(250)',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_category_alias_lang', '{{%category}}', ['alias', 'lang'], true);
        $this->createIndex('idx_category_parent_id', '{{%category}}', 'parent_id');
        $this->createIndex('idx_category_status', '{{%category}}', 'status');
        $this->addForeignKey('fk_category_parent_id', '{{%category}}', 'parent_id', '{{%category}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
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
