<?php

use yii\db\Schema;
use yii\db\Migration;

class m150430_121350_category_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Coupon type */
        $this->createTable('{{%category_type}}', [
            'id' => Schema::TYPE_SMALLINT . '(2) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
        ], $tableOptions);

        $this->createIndex('idx_category_type_name', '{{%category_type}}', 'name');

        $this->db->createCommand()->batchInsert('{{%category_type}}', ['name', 'description'], [
            ['category', 'Категория (рубрика)'],
            ['brand', 'Брэнд (магазин)'],
        ])->execute();

            /* Category */
        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'type_id' => Schema::TYPE_SMALLINT . '(2) DEFAULT NULL',
            'module' => Schema::TYPE_STRING . '(160) DEFAULT NULL',
            'lang' => Schema::TYPE_STRING . '(2)',
            'slug' => Schema::TYPE_STRING . '(160) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'short_description' => Schema::TYPE_STRING . '(512)',
            'description' => Schema::TYPE_TEXT,
            'image' => Schema::TYPE_STRING . '(255)',
            'image_alt' => Schema::TYPE_STRING . '(255)',
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'meta_title' => Schema::TYPE_STRING . '(250)',
            'meta_keywords' => Schema::TYPE_STRING . '(250)',
            'meta_description' => Schema::TYPE_STRING . '(250)',
            'link' => Schema::TYPE_STRING . '(255)',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_category_slug_lang', '{{%category}}', ['slug', 'lang'], true);
        $this->createIndex('idx_category_parent_id', '{{%category}}', 'parent_id');
        $this->createIndex('idx_category_module', '{{%category}}', 'module');
        $this->createIndex('idx_category_type_id', '{{%category}}', 'type_id');
        $this->createIndex('idx_category_status', '{{%category}}', 'status');
        $this->addForeignKey('fk_category_parent_id', '{{%category}}', 'parent_id', '{{%category}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_category_type_id', '{{%category}}', 'type_id', '{{%category_type}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_category_created_by', '{{%category}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_category_updated_by', '{{%category}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%category_type}}');
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
