<?php 
	/*
	*	@var $model optimy\app\models\User
	*/
?>

<div class="wrapper fadeInDown">
  	<div id="formContent">
		<h2 class="auth-title">Registration</h2>

		<?php $form = optimy\app\core\form\Form::instance() ?>
			
		<?php echo $form->begin("", "post") ?>

		<?php echo $form->field($model, "firstname")->cssStyle("auth-field"); ?>
		<?php echo $form->field($model, "lastname")->cssStyle("auth-field"); ?>

		<?php echo $form->field($model, "email")->cssStyle("auth-field"); ?>
		<?php echo $form->field($model, "password")->fieldType("password")->cssStyle("auth-field"); ?>
		<?php echo $form->field($model, "confirmPassword")->fieldType("password")->cssStyle("auth-field"); ?>
	<div class="action">
		<button type="submit" class="btn btn-primary">Submit</button>
		<a class="btn btn-outline-danger" href="/">Cancel</a>
	</div>
		<?php $form->end();?>
	</div>
</div>
