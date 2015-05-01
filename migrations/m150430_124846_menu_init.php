<?php

use yii\db\Schema;
use yii\db\Migration;

class m150430_124846_menu_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Menu */
        $this->createTable('{{%menu}}', [
            'id' => Schema::TYPE_PK,
            'alias' => Schema::TYPE_STRING . '(160) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255)',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_menu_alias', '{{%menu}}', 'alias');
        $this->createIndex('idx_menu_status', '{{%menu}}', 'status');

        /* Menu Item */
        $this->createTable('{{%menu_item}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'menu_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'regular_link' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'title' => Schema::TYPE_STRING . '(160) NOT NULL',
            'href' => Schema::TYPE_STRING . '(255) NOT NULL',
            'class' => Schema::TYPE_STRING . '(160)',
            'title_attr' => Schema::TYPE_STRING . '(160)',
            'before_link' => Schema::TYPE_STRING . '(160)',
            'after_link' => Schema::TYPE_STRING . '(160)',
            'target' => Schema::TYPE_STRING . '(160)',
            'rel' => Schema::TYPE_STRING . '(160)',
            'condition_name' => Schema::TYPE_STRING . '(160)',
            'condition_denial' => Schema::TYPE_SMALLINT . '(1) DEFAULT 0',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_menu_item_menu_id', '{{%menu_item}}', 'menu_id');
        $this->createIndex('idx_menu_item_sort', '{{%menu_item}}', 'sort');
        $this->createIndex('idx_menu_item_status', '{{%menu_item}}', 'status');
        $this->addForeignKey('fk_menu_item_menu_id', '{{%menu_item}}', 'menu_id', '{{%menu}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_menu_item_parent_id', '{{%menu_item}}', 'parent_id', '{{%menu_item}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%menu_item}}');
        $this->dropTable('{{%menu}}');
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
