<?php $form = optimy\app\core\form\Form::instance() ?>
    
<?php echo $form->begin("", "post") ?>

<?php echo $form->field($model, "userid") ?>
<?php echo $form->field($model, "title"); ?>
<?php echo $form->field($model, "content"); ?>
<?php echo $form->field($model, "filename"); ?>
<?php echo $form->field($model, "category"); ?>
<button type="submit" class="btn btn-primary">Submit</button>
<a class="btn btn-outline-danger" href="/">Cancel</a>

<?php $form->end();?>