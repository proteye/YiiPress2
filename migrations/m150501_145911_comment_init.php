<?php

use yii\db\Schema;
use yii\db\Migration;

class m150501_145911_comment_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Comment with Nested Sets */
        $this->createTable('{{%comment}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'model' => Schema::TYPE_STRING . '(160) NOT NULL',
            'model_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(160) NOT NULL',
            'email' => Schema::TYPE_STRING . '(160) NOT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_ip' => Schema::TYPE_STRING . '(20)',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'depth' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->createIndex('idx_comment_model_model_id', '{{%comment}}', ['model', 'model_id']);
        $this->createIndex('idx_comment_model', '{{%comment}}', 'model');
        $this->createIndex('idx_comment_model_id', '{{%comment}}', 'model_id');
        $this->createIndex('idx_comment_user_id', '{{%comment}}', 'user_id');
        $this->createIndex('idx_comment_parent_id', '{{%comment}}', 'parent_id');
        $this->createIndex('idx_comment_status', '{{%comment}}', 'status');
        $this->createIndex('idx_comment_tree', '{{%comment}}', 'tree');
        $this->createIndex('idx_comment_lft', '{{%comment}}', 'lft');
        $this->createIndex('idx_comment_rgt', '{{%comment}}', 'rgt');
        $this->createIndex('idx_comment_depth', '{{%comment}}', 'depth');
        $this->addForeignKey('fk_comment_parent_id', '{{%comment}}', 'parent_id', '{{%comment}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_comment_user_id', '{{%comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%comment}}');
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
