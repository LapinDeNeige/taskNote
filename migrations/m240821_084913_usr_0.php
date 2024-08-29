<?php

use yii\db\Migration;

/**
 * Class m240821_084913_usr_0
 */
class m240821_084913_usr_0 extends Migration
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
        echo "m240821_084913_usr_0 cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
		$this->createTable('usr_0',[
		'header' => $this->string(),
		'description' => $this->string(),
		'id' => $this->primaryKey(),
		'tag'=>$this->string(),
		'user_id'=>$this->integer()->notNull(),
		]);
    }

    public function down()
    {
        $this->dropTable('usr_0');

        return false;
    }
    
}
