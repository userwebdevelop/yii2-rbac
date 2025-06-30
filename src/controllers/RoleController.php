<?php

namespace userwebdevelop\yii2Rbac\controllers;

use userwebdevelop\yii2Rbac\models\Permission;
use Yii;
use userwebdevelop\yii2Rbac\models\Role;
use userwebdevelop\yii2Rbac\models\search\RoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
{
    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@vendor/userwebdevelop/yii2-rbac/src/views/role/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('@vendor/userwebdevelop/yii2-rbac/src/views/role/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role();
        $model->allPermissions = $this->getAllPermissions();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('@vendor/userwebdevelop/yii2-rbac/src/views/role/create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->allPermissions = $this->getAllPermissions();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('@vendor/userwebdevelop/yii2-rbac/src/views/role/update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['@vendor/userwebdevelop/yii2-rbac/src/views/role/index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionUpdateRoles()
    {
        $existingData = Permission::find()->asArray()->all();
        $lastSlashPos = strrpos(self::class, '\\');
        $namespace = substr(self::class, 0, $lastSlashPos) . "\\";
        $class_files = array_filter(scandir(Yii::getAlias('@backend/modules/admin/controllers', 1)), function ($item) {
            return strpos($item, ".php");
        });
        $class_files[] = "RoleController.php";
        $class_names = array_values(array_map(function ($item) use ($namespace) {
            return $namespace . str_replace(".php", "", $item);
        }, $class_files));
        $dataToInsert = [];
        foreach ($class_names as $class) {
            $class_actions = array_filter(get_class_methods($class), function ($item) {
                return strpos($item, "action") === 0 && strpos($item, "actions") !== 0;
            });
            foreach ($class_actions as $action) {
                $dataToInsert[] = [
                    'controller' => $class,
                    'action' => $action
                ];
            }
        }
        $existingMap = [];
        foreach ($existingData as $row) {
            $key = $row['controller'] . '::' . $row['action'];
            $existingMap[$key] = $row['id'];
        }

        $toBeInserted = [];
        foreach ($dataToInsert as $item) {
            $key = $item['controller'] . '::' . $item['action'];
            if (!isset($existingMap[$key])) {
                $toBeInserted[] = [$item['controller'], $item['action']];
            } else {
                unset($existingMap[$key]);
            }
        }

        $idsToDelete = array_values($existingMap);
        if (!empty($idsToDelete)) {
            Yii::$app->db->createCommand()
                ->delete(Permission::tableName(), ['id' => $idsToDelete])
                ->execute();
        }
        if (!empty($toBeInserted)) {
            Yii::$app->db->createCommand()
                ->batchInsert(Permission::tableName(), ['controller', 'action'], $toBeInserted)
                ->execute();
        }
        Yii::$app->session->setFlash('success', 'Пермишены успешно обновлены.');
        return $this->redirect(['@vendor/userwebdevelop/yii2-rbac/src/views/role/index']);
    }
    public function getAllPermissions()
    {
        $allPermissions = Permission::find()->asArray()->all();
        $indexedPermissions = [];
        foreach ($allPermissions as $permission) {
            $indexedPermissions[$permission['controller']][$permission['id']] = Role::getPermissionLabel($permission['action']);
        }
        return $indexedPermissions;
    }
}
