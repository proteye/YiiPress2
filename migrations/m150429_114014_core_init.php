<?php

use yii\db\Schema;
use yii\db\Migration;

class m150429_114014_core_init extends Migration
{
    public function up()
    {
        /* Setting */
        $tableOptions = null;

        $this->createTable('{{%setting}}', [
            'module_id' => Schema::TYPE_STRING . '(64) NOT NULL',
            'param_key' => Schema::TYPE_STRING . '(128) NOT NULL',
            'param_value' => Schema::TYPE_STRING . '(255)',
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->addPrimaryKey('pk_setting', '{{%setting}}', ['module_id', 'param_key']);
        $this->createIndex('idx_setting_module_id_param_key', '{{%setting}}', ['module_id', 'param_key'], true);
        $this->createIndex('idx_setting_module_id', '{{%setting}}', 'module_id');
        $this->createIndex('idx_setting_param_key', '{{%setting}}', 'param_key');
        $this->addForeignKey('fk_setting_created_by', '{{%setting}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_setting_updated_by', '{{%setting}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%setting}}');
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
