<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Role */
/* @var $form yii\widgets\ActiveForm */

$checkedArr = $model->id == 1 ? ['checked' => true] : [];
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'required' => true]) ?>
    <?php if (isset($model->id)): ?>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-primary select-all">Выбрать все</button>
            <button type="button" class="btn btn-sm btn-outline-danger deselect-all">Убрать все</button>
        </div>
        <div class="row">
            <?php
            foreach ($model->allPermissions as $controller => $actions): ?>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <strong><?= end(explode("\\", $controller)) ?></strong>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-outline-primary select-all-on-controller">Выбрать все</button>
                                <button type="button" class="btn btn-sm btn-outline-danger deselect-all-on-controller">Убрать все</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            foreach ($actions as $id => $action):
                                echo $form->field($model, "checkbox_permissions[$id]")->checkbox(array_merge(['label' => $action, 'class' => 'permission-checkbox', 'disabled' => $model->id === 1], $checkedArr));
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Вернуться к списку', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
if ($model->id !== 1) {
    $this->registerJs(
        <<<JS
    $('.select-all-on-controller').on('click', function () {
        var card = $(this).closest('.card');
        var checkboxes = card.find('.permission-checkbox');
        checkboxes.prop('checked', true);
    });
    $('.select-all').on('click', function () {
        var checkboxes = $('.permission-checkbox');
        checkboxes.prop('checked', true);
    });
    $('.deselect-all-on-controller').on('click', function () {
        var card = $(this).closest('.card');
        var checkboxes = card.find('.permission-checkbox');
        checkboxes.prop('checked', false);
    });
    $('.deselect-all').on('click', function () {
        var checkboxes = $('.permission-checkbox');
        checkboxes.prop('checked', false);
    });
    JS,
        \yii\web\View::POS_READY
    );
}
?>