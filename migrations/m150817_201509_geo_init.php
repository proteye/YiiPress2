<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_201509_geo_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        /* Country */
        $this->createTable('{{%geo_country}}', [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(160)',
            'name' => Schema::TYPE_STRING . '(50) NOT NULL',
        ], $tableOptions);

        $this->createIndex('idx_geo_country_slug', '{{%geo_country}}', 'slug');

        /* Region */
        $this->createTable('{{%geo_region}}', [
            'id' => Schema::TYPE_PK,
            'geo_country_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(255)',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);

        $this->createIndex('idx_geo_region_geo_country_id', '{{%geo_region}}', 'geo_country_id');
        $this->createIndex('idx_geo_region_slug', '{{%geo_region}}', 'slug');
        $this->addForeignKey('fk_geo_region_geo_country_id', '{{%geo_region}}', 'geo_country_id', '{{%geo_country}}', 'id', 'CASCADE', 'NO ACTION');

        /* City */
        $this->createTable('{{%geo_city}}', [
            'id' => Schema::TYPE_PK,
            'geo_region_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'geo_country_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(255)',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);

        $this->createIndex('idx_geo_city_geo_region_id', '{{%geo_city}}', 'geo_region_id');
        $this->createIndex('idx_geo_city_geo_country_id', '{{%geo_city}}', 'geo_country_id');
        $this->createIndex('idx_geo_city_slug', '{{%geo_city}}', 'slug');
        $this->addForeignKey('fk_geo_city_geo_region_id', '{{%geo_city}}', 'geo_region_id', '{{%geo_region}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_geo_city_geo_country_id', '{{%geo_city}}', 'geo_country_id', '{{%geo_country}}', 'id', 'CASCADE', 'NO ACTION');

        /* Import from sql dump */
        include 'dump_geo_country.php';
        include 'dump_geo_region.php';
        include 'dump_geo_city.php';
    }

    public function down()
    {
        $this->dropTable('{{%geo_city}}');
        $this->dropTable('{{%geo_region}}');
        $this->dropTable('{{%geo_country}}');
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
