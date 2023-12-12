<?php

use yii\db\Migration;

/**
 * Class m210225_161047_init
 */
class m210225_161047_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey(),
            'sid' => $this->string()->notNull()->unique(),
            'company_name' => $this->string(),
            'company_sid' => $this->string()->notNull(),
            'account_sid' => $this->string(),
            'url' => $this->string()->notNull(),
            'settings' => $this->json(),
            'config' => $this->json(),
        ], $tableOptions);

        $this->createIndex('idx-amo-company_sid', '{{%config}}', 'company_sid');

        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'config_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'event_sid' => $this->string(),
            'data' => $this->json(),
        ]);

        $this->createIndex('idx-log_config_id', '{{%log}}', 'config_id');
        $this->createIndex('idx_event_sid', '{{%log}}', 'event_sid');
        $this->addForeignKey('fk-log-config', '{{%log}}', 'config_id', '{{%config}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'config_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'amo_sid' => $this->integer(),
            'is_top' => $this->boolean()->notNull()->defaultValue(false)
        ]);
        $this->createIndex('idx-user_config_id', '{{%user}}', 'config_id');
        $this->addForeignKey('fk-user-config', '{{%user}}', 'config_id', '{{%config}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%account}}', [
            'id' => $this->primaryKey(),
            'config_id' => $this->integer()->notNull(),
            'amojo_id' => $this->string(),
            'scope_id' => $this->string(),
            'account_id' => $this->integer()
        ]);
        $this->createIndex('idx-account_config_id', '{{%account}}', 'config_id');
        $this->addForeignKey('fk-account-config', '{{%account}}', 'config_id', '{{%config}}', 'id', 'CASCADE', 'RESTRICT');
    }
}
