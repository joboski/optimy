<?php 
    /*
    *   @var $model optimy\app\models\User
    */
?>

<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->
    <h2> Sign In </h2>

	<?php $form = optimy\app\core\form\Form::instance() ?>
	    
	<?php echo $form->begin("", "post") ?>
	<?php echo $form->field($model, "email"); ?>
	<?php echo $form->field($model, "password")->passwordField(); ?>

	<button type="submit" class="btn btn-primary">Submit</button>
	<a class="btn btn-outline-danger" href="/">Cancel</a>
	<p><a href="/register" class="primary"><small><i>Not yet register?</i></small></a></p>
</div>
</div>
<?php $form->end();?>
