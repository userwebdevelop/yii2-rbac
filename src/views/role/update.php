<?php

/* @var $this yii\web\View */
/* @var $model common\models\Role */

$this->title = 'Редактирование роли: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование роли';
?>
<div class="role-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
