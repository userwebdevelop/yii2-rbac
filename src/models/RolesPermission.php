<?php
namespace userwebdevelop\yii2Rbac\models;

use Yii;

/**
 * This is the model class for table "{{%roles_permissions}}".
 *
 * @property int $id
 * @property int $id_role
 * @property int $id_permissions
 *
 * @property Permission $permissions
 * @property Role $role
 */
class RolesPermission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%roles_permissions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_role', 'id_permissions'], 'required'],
            [['id_role', 'id_permissions'], 'integer'],
            [['id_role', 'id_permissions'], 'unique', 'targetAttribute' => ['id_role', 'id_permissions']],
            [['id_permissions'], 'exist', 'skipOnError' => true, 'targetClass' => Permission::className(), 'targetAttribute' => ['id_permissions' => 'id']],
            [['id_role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['id_role' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_role' => 'Id Role',
            'id_permissions' => 'Id Permissions',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasOne(Permission::className(), ['id' => 'id_permissions']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'id_role']);
    }
}
