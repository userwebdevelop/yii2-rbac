<?php

namespace userwebdevelop\yii2Rbac\traits;

use userwebdevelop\yii2Rbac\models\Role;
use userwebdevelop\yii2Rbac\models\UserRole;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

trait UserRolesSave
{
    public $roles;
    public function init()
    {
        parent::init();
        $this->validators->append(Validator::createValidator('safe', $this, ['roles']));
    }
    public function getRolesLabels()
    {
        return ArrayHelper::map(Role::find()->all(), 'id', 'name');
    }
    public function saveUserRoles($model_id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            UserRole::deleteAll(['id_user' => $model_id]);
            if (!empty($this->roles)) {
                $rows = [];
                foreach ($this->roles as $id) {
                    $rows[] = [$model_id, $id];
                }
                \Yii::$app->db->createCommand()
                    ->batchInsert(UserRole::tableName(), ['id_user', 'id_role'], $rows)
                    ->execute();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::error('Ошибка сохранения связей: ' . $e->getMessage());
            throw $e;
        }
    }
    public function getRoles()
    {
        return UserRole::find()->where(['id_user' => $this->id])->select('id_role')->column();
    }
}
