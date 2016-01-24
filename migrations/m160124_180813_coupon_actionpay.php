<?php

use yii\db\Schema;
use yii\db\Migration;

class m160124_180813_coupon_actionpay extends Migration
{
    public function up()
    {
        $tableOptions = null;

        $this->addColumn('{{%coupon_brand}}', 'offer_id', Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL');
        $this->createIndex('idx_coupon_brand_offer_id', '{{%coupon_brand}}', 'offer_id');
        $this->addColumn('{{%coupon}}', 'actionpay_id', Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL');
        $this->createIndex('idx_coupon_actionpay_id', '{{%coupon}}', 'actionpay_id');

    }

    public function down()
    {
        $this->dropIndex('idx_coupon_actionpay_id', '{{%coupon}}');
        $this->dropColumn('{{%coupon}}', 'actionpay_id');
        $this->dropIndex('idx_coupon_brand_offer_id', '{{%coupon_brand}}');
        $this->dropColumn('{{%coupon_brand}}', 'offer_id');
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
