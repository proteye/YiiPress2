<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_082840_coupon_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Brand */
        $this->createTable('{{%coupon_brand}}', [
            'id' => Schema::TYPE_PK,
            'advcampaign_id' => Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL',
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
            'site' => Schema::TYPE_STRING . '(255)',
            'advlink' => Schema::TYPE_STRING . '(255)',
            'view_count' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_coupon_brand_advcampaign_id', '{{%coupon_brand}}', 'advcampaign_id');
        $this->createIndex('idx_coupon_brand_slug', '{{%coupon_brand}}', 'slug');
        $this->createIndex('idx_coupon_brand_name', '{{%coupon_brand}}', 'name');
        $this->createIndex('idx_coupon_brand_status', '{{%coupon_brand}}', 'status');
        $this->addForeignKey('fk_coupon_brand_created_by', '{{%coupon_brand}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_coupon_brand_updated_by', '{{%coupon_brand}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');

        /* Brand to Category */
        $this->createTable('{{%coupon_brand_category}}', [
            'brand_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('pk_coupon_brand_category', '{{%coupon_brand_category}}', ['brand_id', 'category_id']);
        $this->createIndex('idx_coupon_brand_category_brand_id', '{{%coupon_brand_category}}', 'brand_id');
        $this->createIndex('idx_coupon_brand_category_category_id', '{{%coupon_brand_category}}', 'category_id');
        $this->addForeignKey('fk_coupon_brand_category_brand_id', '{{%coupon_brand_category}}', 'brand_id', '{{%coupon_brand}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_coupon_brand_category_category_id', '{{%coupon_brand_category}}', 'category_id', '{{%category}}', 'id', 'CASCADE', 'NO ACTION');

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
            'brand_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'adv_id' => Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL',
            'slug' => Schema::TYPE_STRING . '(160) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'short_name' => Schema::TYPE_STRING . '(160)',
            'description' => Schema::TYPE_TEXT,
            'promocode' => Schema::TYPE_STRING . '(64)',
            'promolink' => Schema::TYPE_STRING . '(255)',
            'gotolink' => Schema::TYPE_STRING . '(255)',
            'type_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'discount' => Schema::TYPE_STRING . '(64)',
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
            'recommended' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'view_count' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->createIndex('idx_coupon_brand_id', '{{%coupon}}', 'brand_id');
        $this->createIndex('idx_coupon_adv_id', '{{%coupon}}', 'adv_id');
        $this->createIndex('idx_coupon_type_id', '{{%coupon}}', 'type_id');
        $this->createIndex('idx_coupon_begin_dt', '{{%coupon}}', 'begin_dt');
        $this->createIndex('idx_coupon_end_dt', '{{%coupon}}', 'end_dt');
        $this->createIndex('idx_coupon_status', '{{%coupon}}', 'status');
        $this->addForeignKey('fk_coupon_brand_id', '{{%coupon}}', 'brand_id', '{{%coupon_brand}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_coupon_type_id', '{{%coupon}}', 'type_id', '{{%coupon_type}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_coupon_created_by', '{{%coupon}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_coupon_updated_by', '{{%coupon}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%coupon}}');
        $this->dropTable('{{%coupon_type}}');
        $this->dropTable('{{%coupon_brand_category}}');
        $this->dropTable('{{%coupon_brand}}');
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
