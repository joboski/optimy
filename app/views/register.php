<h2>Sign Up</h2>

<?php $form = optimy\app\core\form\Form::instance() ?>
	
<?php echo $form->begin("", "post") ?>

<div class="row">
	<div class="col"><?php echo $form->field($model, "firstname") ?></div>
	<div class="col"><?php echo $form->field($model, "lastname"); ?></div>
</div>

<?php echo $form->field($model, "email"); ?>
<?php echo $form->field($model, "password"); ?>
<?php echo $form->field($model, "confirmPassword"); ?>
<button type="submit" class="btn btn-primary">Submit</button>
<a class="btn btn-outline-danger" href="/register">Cancel</a>

<?php $form->end();?>
