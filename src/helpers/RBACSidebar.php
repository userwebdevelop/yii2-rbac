<?php

namespace userwebdevelop\yii2Rbac\helpers;
use userwebdevelop\yii2Rbac\models\Permission;
use userwebdevelop\yii2Rbac\models\RolesPermission;
use userwebdevelop\yii2Rbac\models\UserRole;
use Yii;

class RBACSidebar
{
    public static function sidebar($items)
    {
        $user = Yii::$app->user;
        $user_roles_id = UserRole::find()->where(['id_user' => $user->id])->select('id_role')->column();
        if (in_array(1, $user_roles_id)) return $items;
        $user_permissions = array_unique(RolesPermission::find()->where(['id_role' => $user_roles_id])->select('id_permission')->column());
        $permissions = Permission::find()->where(['id' => $user_permissions])->andWhere(['action' => 'actionIndex'])->select("controller")->column();
        foreach ($items as $key => $item) {
            $className = "app\modules\admin\controllers\\" . str_replace(" ", "", ucwords(str_replace("-"," ", end(explode("/", $item['url'][0])))) . "Controller");
            if (!in_array($className, $permissions)) unset($items[$key]);
        }
        return $items;
    }
}
