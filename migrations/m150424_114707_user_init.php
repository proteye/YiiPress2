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
            'username' => Schema::TYPE_STRING . '(250) NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'email_confirm_token' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_user_username', '{{%user}}', 'username');
        $this->createIndex('idx_user_email', '{{%user}}', 'email');
        $this->createIndex('idx_user_status', '{{%user}}', 'status');

        /* User Profile */
        $tableOptions = null;

        $this->createTable('{{%user_profile}}', [
            'user_id' => Schema::TYPE_PK,
            'nick_nm' => Schema::TYPE_STRING . '(255)',
            'first_nm' => Schema::TYPE_STRING . '(255)',
            'last_nm' => Schema::TYPE_STRING . '(255)',
            'patron' => Schema::TYPE_STRING . '(255)',
            'about' => Schema::TYPE_STRING . '(255)',
            'avatar' => Schema::TYPE_STRING . '(255)',
            'address' => Schema::TYPE_STRING . '(512)',
            'site' => Schema::TYPE_STRING . '(255)',
            'birth_dt' => Schema::TYPE_INTEGER,
            'user_ip' => Schema::TYPE_STRING . '(20)',
            'last_visit' => Schema::TYPE_INTEGER,
            'email_confirm' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',

        ], $tableOptions);

        //$this->addPrimaryKey('pk_user_meta', '{{%user_meta}}', ['user_id', 'meta_key']);
        $this->addForeignKey('fk_user_profile_user_id', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_user_profile_nick_nm', '{{%user_profile}}', 'nick_nm');
    }

    public function down()
    {
        $this->dropTable('{{%user_profile}}');
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
