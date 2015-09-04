<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_205616_catalog_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Company */
        $this->createTable('{{%company}}', [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(160) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'email' => Schema::TYPE_STRING . '(255) NOT NULL',
            'short_description' => Schema::TYPE_STRING . '(512)',
            'description' => Schema::TYPE_TEXT,
            'logo' => Schema::TYPE_STRING . '(255)',
            'site' => Schema::TYPE_STRING . '(255)',
            'skype' => Schema::TYPE_STRING . '(160)',
            'icq' => Schema::TYPE_STRING . '(20)',
            'link_vk' => Schema::TYPE_STRING . '(255)',
            'link_fb' => Schema::TYPE_STRING . '(255)',
            'link_in' => Schema::TYPE_STRING . '(255)',
            'rating' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'published_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_ip' => Schema::TYPE_STRING . '(20)',
            'meta_title' => Schema::TYPE_STRING . '(250)',
            'meta_keywords' => Schema::TYPE_STRING . '(250)',
            'meta_description' => Schema::TYPE_STRING . '(250)',
            'comment_status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            'view_count' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_company_slug', '{{%company}}', 'slug');
        $this->createIndex('idx_company_email', '{{%company}}', 'email');
        $this->createIndex('idx_company_created_by', '{{%company}}', 'created_by');
        $this->createIndex('idx_company_published_at', '{{%company}}', 'published_at');
        $this->createIndex('idx_company_comment_status', '{{%company}}', 'comment_status');
        $this->createIndex('idx_company_status', '{{%company}}', 'status');
        $this->addForeignKey('fk_company_created_by', '{{%company}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_company_updated_by', '{{%company}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');

        /* Company address */
        $this->createTable('{{%company_address}}', [
            'id' => Schema::TYPE_PK,
            'company_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'city_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'address' => Schema::TYPE_STRING . '(255)',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_company_address_company_id', '{{%company_address}}', 'company_id');
        $this->createIndex('idx_company_address_city_id', '{{%company_address}}', 'city_id');
        $this->addForeignKey('fk_company_address_company_id', '{{%company_address}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_company_address_city_id', '{{%company_address}}', 'city_id', '{{%geo_city}}', 'id', 'NO ACTION', 'NO ACTION');

        /* Company phone */
        $this->createTable('{{%company_phone}}', [
            'id' => Schema::TYPE_PK,
            'company_address_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'phone_country' => Schema::TYPE_SMALLINT . '(3) UNSIGNED',
            'phone_city' => Schema::TYPE_SMALLINT . ' UNSIGNED',
            'phone_number' => Schema::TYPE_INTEGER . ' UNSIGNED',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_company_phone_company_address_id', '{{%company_phone}}', 'company_address_id');
        $this->addForeignKey('fk_company_phone_company_address_id', '{{%company_phone}}', 'company_address_id', '{{%company_address}}', 'id', 'CASCADE', 'NO ACTION');

        /* Company image */
        $this->createTable('{{%company_image}}', [
            'company_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'image_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('pk_company_image', '{{%company_image}}', ['company_id', 'image_id']);
        $this->createIndex('idx_company_image_company_id', '{{%company_image}}', 'company_id');
        $this->createIndex('idx_company_image_image_id', '{{%company_image}}', 'image_id');
        $this->addForeignKey('fk_company_image_company_id', '{{%company_image}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_company_image_image_id', '{{%company_image}}', 'image_id', '{{%image}}', 'id', 'CASCADE', 'NO ACTION');

        /* Company video */
        $this->createTable('{{%company_video}}', [
            'id' => Schema::TYPE_PK,
            'company_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'url' => Schema::TYPE_STRING . '(255)',
            'description' => Schema::TYPE_TEXT,
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_company_video_company_id', '{{%company_video}}', 'company_id');
        $this->addForeignKey('fk_company_video_company_id', '{{%company_video}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%company_video}}');
        $this->dropTable('{{%company_image}}');
        $this->dropTable('{{%company_phone}}');
        $this->dropTable('{{%company_address}}');
        $this->dropTable('{{%company}}');
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
