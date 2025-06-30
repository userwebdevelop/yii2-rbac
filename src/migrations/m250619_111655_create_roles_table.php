<?php
namespace userwebdevelop\yii2Rbac\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%roles}}`.
 */
class m250619_111655_create_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%roles}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
        $this->batchInsert("{{%roles}}", ['id', 'name'], [["1", 'SuperAdmin']]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%roles}}');
    }
}
