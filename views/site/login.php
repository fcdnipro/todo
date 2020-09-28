<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <button type="button" class="btn btn-primary registration" data-toggle="modal" data-target="#userModal" >Registration</button>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
    </div>
</div>
<!-- modal User create -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" id="create_user" action="" novalidate>
                    <input type="text" pattern="[a-zA-Z0-9]+" class="form-control" name="user_name" placeholder="Name" required/><br>
                    <input type="text" pattern="[a-zA-Z0-9_.*]+" class="form-control" name="password" placeholder="Password" required/><br>
                    <div class="invalid-tooltip">
                        User name or password not valid!
                    </div>
                </form>
                <br>
                <div class="alert alert-danger error" id="display_user_msg" role="alert" hidden="false"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="create_user" class="btn btn-primary" onclick="createUser('create_user')">Create User</button>
            </div>
        </div>
    </div>
</div>
<!-- modal User create end-->
<script>

    function createUser(userFormId) {
        let FormId = 'create_user';
        let valid = validateForm(FormId);
        if (valid) {
            $.ajax({
                url: '/create-user',
                type: "POST",
                dataType: "html",
                data: $("#" + userFormId).serialize(),
                success: function (response) {
                    let modalId = 'userModal';
                    let msgBlock = 'display_user_msg';
                    result = $.parseJSON(response);
                    msgAjax(msgBlock, result, modalId, false)

                },
                error: function (response) {
                    let msgBlock = 'display_user_msg';

                    $('#' + msgBlock).html('Ajax Error!');
                }
            });
        }
    }
    function validateForm(formId) {
        let form = document.getElementById(formId);
        form.classList.add('was-validated');

        return form.checkValidity();
    }
</script>




