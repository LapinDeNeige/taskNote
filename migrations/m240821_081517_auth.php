<?php

use yii\db\Migration;

/**
 * Class m240821_081517_auth
 */
class m240821_081517_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240821_081517_auth cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
		$this->createTable('auth',[
			'id'=>$this->primaryKey(),
			'Name'=>$this->string(),
			'password'=>$this->string(),
		
		])
    }

    public function down()
    {
        //echo "m240821_081517_auth cannot be reverted.\n";
		$this->dropTable('auth');
        return false;
    }
    
}
