<?php

use yii\db\Schema;
use yii\db\Migration;

class m150501_052953_image_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Image */
        $this->createTable('{{%image}}', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'parent_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'file' => Schema::TYPE_STRING . '(255) NOT NULL',
            'alt' => Schema::TYPE_STRING . '(255)',
            'description' => Schema::TYPE_TEXT,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
            'type' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_image_status', '{{%image}}', 'status');
        $this->createIndex('idx_image_type', '{{%image}}', 'type');
        $this->createIndex('idx_image_category_id', '{{%image}}', 'category_id');
        $this->addForeignKey('fk_image_category_id', '{{%image}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_image_parent_id', '{{%image}}', 'parent_id', '{{%image}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_image_created_by', '{{%image}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_image_updated_at', '{{%image}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%image}}');
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
