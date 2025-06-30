<?php
namespace userwebdevelop\yii2Rbac\models;

use Yii;
use common\models\User;
/**
 * This is the model class for table "{{%roles}}".
 *
 * @property int $id
 * @property string $name
 *
 * @property RolesPermission[] $rolesPermissions
 * @property Permission[] $permissions
 * @property UserRole[] $userRoles
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    public $allPermissions;
    public $checkbox_permissions;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%roles}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['checkbox_permissions'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название роли',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolesPermissions()
    {
        return $this->hasMany(RolesPermission::className(), ['id_role' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permission::className(), ['id' => 'id_permissions'])->viaTable('{{%roles_permissions}}', ['id_role' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['id_role' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])->viaTable('{{%user_roles}}', ['id_role' => 'id']);
    }
    public static function getPermissionLabel($label)
    {
        $labels = \Yii::$app->params['PERMISSION_LABELS'] ?? [];
        return $labels[$label] ?? $label;
    }
    public function afterFind()
    {
        parent::afterFind();

        $rolePermissions = RolesPermission::find()
            ->where(['id_role' => $this->id])
            ->select('id_permission')
            ->column();
        $selectedPermissions = Permission::find()->where(['id' => $rolePermissions])->asArray()->all();
        $formPermissions = [];
        foreach ($selectedPermissions as $selectedPermission) {
            $formPermissions[$selectedPermission['id']] = 1;
        }
        $this->checkbox_permissions = $formPermissions;
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $permissions_id = [];
        foreach ($this->checkbox_permissions as $permissionId => $isChecked) {
            if ((int)$isChecked === 1) {
                $permissions_id[] = (int)$permissionId;
            }
        }
        Yii::$app->db->createCommand()
            ->delete(RolesPermission::tableName(), ['id_role' => $this->id])
            ->execute();
        if (!empty($permissions_id)) {
            $rows = [];
            foreach ($permissions_id as $permissionId) {
                $rows[] = [$this->id, $permissionId];
            }
            Yii::$app->db->createCommand()
                ->batchInsert(RolesPermission::tableName(), ['id_role', 'id_permission'], $rows)
                ->execute();
        }

        return true;
    }
}
