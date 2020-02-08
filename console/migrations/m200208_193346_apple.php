<?php

use common\models\Apple;
use yii\db\Migration;

/**
 * Class m200208_193346_apple
 */
class m200208_193346_apple extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(50)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'falled_at' => $this->timestamp()->null(),
            'status' =>$this->tinyInteger()->defaultValue(Apple::STATUS_ON_TREE),
            'size' =>  $this->decimal(3,2)->defaultValue(1)
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200208_193346_apple cannot be reverted.\n";

        return false;
    }
    */
}
