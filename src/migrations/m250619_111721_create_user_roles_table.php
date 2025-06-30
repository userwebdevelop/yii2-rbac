<?php
namespace userwebdevelop\yii2Rbac\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_roles}}`.
 */
class m250619_111721_create_user_roles_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_roles}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'id_role' => $this->integer()->notNull(),
        ]);
    
        $this->addForeignKey(
            'fk-user_roles-user',
            '{{%user_roles}}',
            'id_user',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'fk-user_roles-roles',
            '{{%user_roles}}',
            'id_role',
            '{{%roles}}',
            'id',
            'CASCADE'
        );
    
        $this->createIndex('idx-user_roles-id_user', '{{%user_roles}}', 'id_user');
        $this->createIndex('idx-user_roles-id_role', '{{%user_roles}}', 'id_role');
        $this->createIndex('idx-user_roles-composite', '{{%user_roles}}', ['id_user', 'id_role'], true);
    }
    
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_roles-roles', '{{%user_roles}}');
        $this->dropForeignKey('fk-user_roles-user', '{{%user_roles}}');
    
        $this->dropIndex('idx-user_roles-composite', '{{%user_roles}}');
        $this->dropIndex('idx-user_roles-id_role', '{{%user_roles}}');
        $this->dropIndex('idx-user_roles-id_user', '{{%user_roles}}');
    
        $this->dropTable('{{%user_roles}}');
    }
}
