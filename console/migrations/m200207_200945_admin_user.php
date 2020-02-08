<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m200207_200945_admin_user
 */
class m200207_200945_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $transaction = $this->getDb()->beginTransaction();
        /**
         * @var $user User
         */
        $user = \Yii::createObject([
            'class'    => User::class,
            'scenario' => 'create',
            'email'    => 'admin',
            'username' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $user->generateAuthKey();
        if (!$user->insert(false)) {
            $transaction->rollBack();
            return false;
        }
        //$user->confirm();
        $transaction->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200207_200945_admin_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200207_200945_admin_user cannot be reverted.\n";

        return false;
    }
    */
}
