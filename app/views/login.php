<?php 
    /*
    *   @var $model optimy\app\models\User
    */
?>

<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="auth-title"> Sign In </h2>

	<?php $form = optimy\app\core\form\Form::instance() ?>
	    
	<?php echo $form->begin("", "post") ?>
	<?php echo $form->field($model, "email")->cssStyle("auth-field"); ?>
	<?php echo $form->field($model, "password")->fieldType("password")->cssStyle("auth-field"); ?>
	<div class="action">
		<button type="submit" class="btn btn-primary">Submit</button>
		<a class="btn btn-outline-danger" href="/">Cancel</a>
		<p><a href="/register" class="primary"><small><i>Not yet register?</i></small></a></p>
	</div>
</div>
</div>
<?php $form->end();?>
