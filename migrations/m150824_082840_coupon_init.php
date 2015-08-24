<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_082840_coupon_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Coupon type */
        $this->createTable('{{%coupon_type}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'slug' => Schema::TYPE_STRING . '(160) NOT NULL',
            'extra' => Schema::TYPE_STRING . '(64) DEFAULT NULL',
        ], $tableOptions);

        $this->createIndex('idx_coupon_type_slug', '{{%coupon_type}}', 'slug');

        /* Coupon */
        $this->createTable('{{%coupon}}', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'type_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'value' => Schema::TYPE_STRING . '(64)',
            'begin_dt' => Schema::TYPE_INTEGER,
            'end_dt' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'meta_title' => Schema::TYPE_STRING . '(250)',
            'meta_keywords' => Schema::TYPE_STRING . '(250)',
            'meta_description' => Schema::TYPE_STRING . '(250)',
            'user_ip' => Schema::TYPE_STRING . '(20)',
            'view_count' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT 0',
            'recommended' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_coupon_category_id', '{{%coupon}}', 'category_id');
        $this->createIndex('idx_coupon_type_id', '{{%coupon}}', 'type_id');
        $this->createIndex('idx_coupon_created_by', '{{%coupon}}', 'created_by');
        $this->createIndex('idx_coupon_updated_by', '{{%coupon}}', 'updated_by');
        $this->createIndex('idx_coupon_begin_dt', '{{%coupon}}', 'begin_dt');
        $this->createIndex('idx_coupon_end_dt', '{{%coupon}}', 'end_dt');
        $this->createIndex('idx_coupon_status', '{{%coupon}}', 'status');
        $this->addForeignKey('fk_coupon_category_id', '{{%coupon}}', 'category_id', '{{%category}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_coupon_type_id', '{{%coupon}}', 'type_id', '{{%coupon_type}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_coupon_created_by', '{{%coupon}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_coupon_updated_by', '{{%coupon}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%coupon}}');
        $this->dropTable('{{%coupon_type}}');
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
