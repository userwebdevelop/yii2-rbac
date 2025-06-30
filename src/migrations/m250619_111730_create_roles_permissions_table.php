<?php
namespace userwebdevelop\yii2Rbac\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%roles_permissions}}`.
 */
class m250619_111730_create_roles_permissions_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%roles_permissions}}', [
            'id' => $this->primaryKey(),
            'id_role' => $this->integer()->notNull(),
            'id_permission' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-roles_permissions-roles',
            '{{%roles_permissions}}',
            'id_role',
            '{{%roles}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-roles_permissions-permissions',
            '{{%roles_permissions}}',
            'id_permission',
            '{{%permissions}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-roles_permissions-id_role', '{{%roles_permissions}}', 'id_role');
        $this->createIndex('idx-roles_permissions-id_permission', '{{%roles_permissions}}', 'id_permission');
        $this->createIndex('idx-roles_permissions-composite', '{{%roles_permissions}}', ['id_role', 'id_permission'], true);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-roles_permissions-permissions', '{{%roles_permissions}}');
        $this->dropForeignKey('fk-roles_permissions-roles', '{{%roles_permissions}}');

        $this->dropIndex('idx-roles_permissions-composite', '{{%roles_permissions}}');
        $this->dropIndex('idx-roles_permissions-id_permission', '{{%roles_permissions}}');
        $this->dropIndex('idx-roles_permissions-id_role', '{{%roles_permissions}}');

        $this->dropTable('{{%roles_permissions}}');
    }
}
