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
    'rbac-yii2' => 'userwebdevelop\yii2Rbac\commands\RbacYii2Commands',
],
```

4. В файле `backend/modules/admin/Module.php` подключить трейт:
```php
use userwebdevelop\yii2Rbac\traits\ModuleMiddleware;
```

5. Вывод сайдбара обернуть в метод из пакета:
```php
\userwebdevelop\yii2Rbac\widgets\RBACSidebar::widget([
    // urls
]);
```

6. 
Команда для проведения миграций:
`php yii yii2-rbac/migrate`

7. 
Для добавления админа используется команда `php yii yii2-rbac <логин, id или email админа>`