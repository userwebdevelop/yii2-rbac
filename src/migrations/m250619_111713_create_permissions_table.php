<?php
namespace userwebdevelop\yii2Rbac\migrations;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%permissions}}`.
 */
class m250619_111713_create_permissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%permissions}}', [
            'id' => $this->primaryKey(),
            'controller' => $this->string(),
            'action' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%permissions}}');
    }
}
