# yii2-rbac

Расширение RBAC для Yii2 Advanced.

## Установка

1. 
```bash
composer require userwebdevelop/yii2-rbac
```
2. 
Добавить в файл `backend/config/main.php` следующий фрагмент:
```php
'controllerMap' => [
    'role' => 'userwebdevelop\yii2Rbac\controllers\RoleController',
],
```

3. 
Добавить в файл `console/config/main.php` следующий фрагмент:
```php
'controllerMap' => [
    'yii2-rbac' => 'userwebdevelop\yii2Rbac\commands\RbacYii2Commands',
],
```

4. В файле `backend/modules/admin/Module.php` подключить трейт:
```php
use \userwebdevelop\yii2Rbac\traits\ModuleMiddleware;
```

5. Вывод сайдбара в файле `backend/views/layouts/left.php` обернуть в метод из пакета:
```php
\userwebdevelop\yii2Rbac\widgets\RBACSidebar::widget([
    // urls
    ['label' => 'Роли', 'icon' => 'users', 'url' => ['/role']], // Также нужно добавить ссылку на роли
]);
```

6. 
Команда для проведения миграций:
`php yii yii2-rbac/migrate`

7. 
Для добавления админа используется команда `php yii yii2-rbac/admin <логин, id или email админа>`

8. 
Для перевода пермишенов доабвить в `backend/config/params.php` следующий элемент:
```php
    'PERMISSION_LABELS' => [
        'actionIndex' => 'Просмотр всех',
        'actionView' => 'Просмотр одного',
        'actionCreate' => 'Создание',
        'actionUpdate' => 'Редактирование'
    ]
```
По необходимости добавлять и убирать методы

9. 
В `backend/modules/admin/user/_form.php` и в `backend/modules/admin/user/update.php` добавить следующий код:
```php
<?= $form->field($model, 'roles')->widget(Select2::classname(), [
        'data' => $model->getRolesLabels(),
        'options' => ['placeholder' => '', 'multiple' => true],
        'hideSearch' => false,
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
```

10. 
В `backend\models\EditUserForm.php` и в `common\models\User.php` подключить трейт:
```php
use \userwebdevelop\yii2Rbac\traits\UserRolesSave;
```