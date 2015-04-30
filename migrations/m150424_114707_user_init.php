<?php

use yii\db\Schema;
use yii\db\Migration;

class m150424_114707_user_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        
        /* User */
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',

            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createIndex('idx_user_username', '{{%user}}', 'username');
        $this->createIndex('idx_user_email', '{{%user}}', 'email');
        $this->createIndex('idx_user_status', '{{%user}}', 'status');

        /* User Meta */
        $tableOptions = null;

        $this->createTable('{{%user_meta}}', [
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'meta_key' => Schema::TYPE_STRING . '(255) NOT NULL',
            'meta_value' => Schema::TYPE_TEXT,
        ], $tableOptions);

        $this->addPrimaryKey('pk_user_meta', '{{%user_meta}}', ['user_id', 'meta_key']);
        $this->addForeignKey('fk_user_meta_user_id', '{{%user_meta}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_user_meta_user_id_meta_key', '{{%user_meta}}', ['user_id', 'meta_key'], true);
    }

    public function down()
    {
        $this->dropTable('{{%user_meta}}');
        $this->dropTable('{{%user}}');
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
