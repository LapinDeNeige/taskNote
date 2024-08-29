<?php

use yii\db\Migration;

/**
 * Class m240818_135249_db_users_vk
 */
class m240818_135249_db_users_vk extends Migration
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
        echo "m240818_135249_db_users_vk cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
		$this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'created_at' => $this->string()->notNull(),
            'updated_at' => $this->string()->notNull(),
            'auth_key' => $this->string()
        ]);
		
		
    }

    public function down()
    {
        //echo "m240818_135249_db_users_vk cannot be reverted.\n";
		$this->dropTable('auth');
		$this->dropTable('user');
        
    }
   
}
