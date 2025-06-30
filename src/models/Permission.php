<?php
namespace userwebdevelop\yii2Rbac\models;

use Yii;

/**
 * This is the model class for table "{{%permissions}}".
 *
 * @property int $id
 * @property string $controller
 * @property string $action
 *
 * @property RolesPermission[] $rolesPermissions
 * @property Role[] $roles
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%permissions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'controller' => 'Controller',
            'action' => 'Action',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolesPermissions()
    {
        return $this->hasMany(RolesPermission::className(), ['id_permissions' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['id' => 'id_role'])->viaTable('{{%roles_permissions}}', ['id_permissions' => 'id']);
    }
}
