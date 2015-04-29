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
            'user_id' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->addPrimaryKey('pk_setting', '{{%setting}}', ['module_id', 'param_key']);
        $this->createIndex('idx_setting_module_id_param_key', '{{%setting}}', ['module_id', 'param_key'], true);
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
