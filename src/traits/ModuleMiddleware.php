<?php

namespace userwebdevelop\yii2Rbac\traits;

use userwebdevelop\yii2Rbac\models\Permission;
use userwebdevelop\yii2Rbac\models\RolesPermission;
use userwebdevelop\yii2Rbac\models\UserRole;
use yii\web\ForbiddenHttpException;

trait ModuleMiddleware
{
    public function beforeAction($route): bool
    {
        $user = \Yii::$app->user;
        if ($user->isGuest) {
            $user->loginRequired();
            return false;
        }
        $user_id = $user->id;
        $user_roles_id = UserRole::find()->where(['id_user' => $user_id])->select('id_role')->column();
        if (in_array(1, $user_roles_id)) return true;
        $user_permissions = array_unique(RolesPermission::find()->where(['id_role' => $user_roles_id])->select('id_permission')->column());
        $hasPermission = !empty(Permission::find()->where(['id' => $user_permissions])->andWhere(['controller' => "\\" . get_class($route->controller), 'action' => $route->actionMethod])->asArray()->all());
        if (!$hasPermission) {
            throw new ForbiddenHttpException('У вас недостаточно прав на выполнение данной операции.');
        }
        return true;
    }
}
