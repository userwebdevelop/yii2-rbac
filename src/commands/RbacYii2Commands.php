<?php

namespace userwebdevelop\yii2Rbac\commands;

use common\models\User;
use userwebdevelop\yii2Rbac\models\UserRole;
use yii\console\Controller;
use yii\helpers\Console;

class RbacYii2Commands extends Controller
{
    public function actionAdmin(string $login)
    {
        $user = User::find()->where(['username' => $login])->orWhere(['email' => $login])->orWhere(['id' => $login])->one();

        if (!$user) {
            $this->stderr("Пользователь с логином '$login' не найден.\n");
            return 1;
        }
        $existing = UserRole::find()
            ->where([
                'id_user' => $user->id,
                'id_role' => 1
            ])
            ->exists();

        if ($existing) {
            $this->stdout("Пользователь '$login' уже является администратором.\n");
            return 0;
        }

        $role = new UserRole();
        $role->id_user = $user->id;
        $role->id_role = 1;

        if ($role->save()) {
            $this->stdout("Пользователь '$login' назначен администратором.\n");
            return 0;
        } else {
            $this->stderr("Ошибка назначения роли администратора: " . json_encode($role->errors) . "\n");
            return 1;
        }
    }
    public function actionMigrate()
    {
        $migrationPath = \Yii::getAlias('@vendor/userwebdevelop/yii2-rbac/src/migrations');

        if (!is_dir($migrationPath)) {
            $this->stderr("Папка с миграциями не найдена: $migrationPath\n");
            return 1;
        }

        $migrate = new \yii\console\controllers\MigrateController('migrate', \Yii::$app);
        $migrate->migrationPath = null;
        $migrate->migrationNamespaces = [
            'userwebdevelop\yii2Rbac\migrations',
        ];
        $migrate->color = true;

        ob_start();
        $result = $migrate->runAction('up');
        $output = ob_get_clean();

        $this->stdout($output);

        if ($result === 0) {
            $this->stdout("Миграции успешно выполнены.\n", Console::FG_GREEN);
        } else {
            $this->stderr("Ошибка при выполнении миграций.\n");
        }

        return $result;
    }
}
