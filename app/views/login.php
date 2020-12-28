<?php 
    /*
    *   @var $model optimy\app\models\User
    */
?>


<h2>Sign Up</h2>

<?php $form = optimy\app\core\form\Form::instance() ?>
    
<?php echo $form->begin("", "post") ?>
<?php echo $form->field($model, "email"); ?>
<?php echo $form->field($model, "password")->passwordField(); ?>

<button type="submit" class="btn btn-primary">Submit</button>
<a class="btn btn-outline-danger" href="/">Cancel</a>

<?php $form->end();?>
